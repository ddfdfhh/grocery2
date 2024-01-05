<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \Carbon\Carbon;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        Log::info("cart init index called");
        $email = $r->email;
        $phone = $r->phone;

        $user = \App\Models\User::where([
            'email' => $email,
            'phone' => $phone,

        ])->first();
        $user_id = $user->id;
        $carts = \DB::table('carts')->whereUserId($user_id)->get()->toArray();

        return response()->json(['data' => $carts], 200);
    }

    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    public function store(Request $r)
    {

        //.dd('hi');

        $post = $r->all();
        $email = $r->email;
        $phone = $r->phone;

        $user = \App\Models\User::where([
            'email' => $email,
            'phone' => $phone,

        ])->first();
        $user_id = $user->id;
        $cart_id = $post['id'];
        if (!empty($cart_id)) {
            $row = Cart::whereId($cart_id)->first();
            if ($post['qty'] == 0 || empty($post['qty'])) {
                $row->delete();
            } else {
                $row->update([
                    'qty' => $r->qty]);
                $row->refresh();
                $rules = getProductQtyRules($row, $user_id, 'Automatic');
                if (!empty($rules)) {
                    $p = getNetAmountAfterIndividualDiscountForSingleCartItem($row, $rules);
                    \DB::insert('applied_coupons')->insert([
                        'coupon_id' => $rules[0]['coupon_id'],
                        'cart_session_id' => $cart_session_id,
                        'coupon_method' => 'Automatic',
                        'user_id' => $user_id,
                        'coupon_type' => 'Individual Quantity',
                    ]);
                    $row->update([

                        'discount_rules' => !empty($rules) ? json_encode($rules) : null,
                        'net_cart_amount' => $p['net_cart_amount'], 'total_discount' => $p['total_discount'],
                        'product_discount_offer_detail' => !empty($rules) ? $rules[0]['offer_text'] : null,
                        'total_tax' => $p['total_tax'],
                    ]);
                }

            }

        } else {

            $post['is_combo'] = 'No';

            $latest_cart_item = Cart::whereUserId($user_id)->latest()->first();
            $cart_session_id = is_null($latest_cart_item) ? $user_id . uniqid() : $latest_cart_item->cart_session_id;
            $post['cart_session_id'] = $cart_session_id;
            $post['user_id'] = $user_id;
            $post['net_cart_amount'] = 0;
            $post['total_discount'] = 0;
            $post['total_tax'] = 0;
            $row = Cart::create($post);
            // $row->qty = $row->qty - 1;
            $rules = getProductQtyRules($row, $user_id, 'Automatic');
            if (!empty($rules)) {

                $p = getNetAmountAfterIndividualDiscountForSingleCartItem($row, $rules);
                \DB::insert('applied_coupons')->insert([
                    'coupon_id' => $rules[0]['coupon_id'],
                    'cart_session_id' => $cart_session_id,
                    'coupon_method' => 'Automatic',
                    'coupon_type' => 'Individual Quantity',
                    'user_id' => $user_id,
                ]);
                $row->update([
                    // 'qty' => $row->qty + 1,
                    'discount_rules' => !empty($rules) ? json_encode($rules) : null,
                    'net_cart_amount' => $p['net_cart_amount'], 'total_discount' => $p['total_discount'],
                    'product_discount_offer_detail' => !empty($rules) ? $rules[0]['offer_text'] : null,
                    'total_tax' => $p['total_tax'],

                ]);
            }

        }
        $cart_items = \DB::table('carts')->whereCartSessionId($cart_session_id)->whereUserId($user_id)->get();

        $this->applyAutomaticBogoOffer($cart_items, $user_id, 'Automatic');

        $carts = \DB::table('carts')->where('cart_session_id', $row->cart_session_id)->get()->toArray();
        $cart_coupon_selected = $this->applyAutomaticCartDiscount($cart_items, $user_id);

        return response()->json(['data' => $carts], 201);
    }
    public function applyAutomaticBogoOffer($cart_items, $user_id)
    {
        $cart_session_id = $cart_items[0]->cart_session_id;
        $combo_product_as_offer = getBogoOffers($cart_items, $user_id, 'Automatic');
        $cart_product_ids = array_column($cart_items->toArray(), 'product_id');
        $is_cart_changed = false;
        $offers_applied = [];
        $coupon_ids_applied = [];
        if (!empty($combo_product_as_offer)) {

            $prod_ids = array_keys($combo_product_as_offer);
            if (!empty($prod_ids)) {
                $cart_offer_insert_ar = [];
                $prod_rec = \DB::table('products')->whereIn('id', $prod_ids)->get();
                if (!empty(count($prod_rec->toArray()))) {
                    foreach ($prod_rec as $t) {
                        $coupon_ids = !empty($t->coupon_ids) ? json_decode($t->coupon_ids) : [];
                        if (!in_array($t->id, $cart_product_ids)) {

                            $discount_type = $combo_product_as_offer[$t->id]['discount_type'];
                            $discount_value = $combo_product_as_offer[$t->id]['discount'];
                            $discount_amount = $discount_type == 'Flat' ? $discount_value : ($t->price * $discount_value / 100);
                            $qty_to_add = $combo_product_as_offer[$t->id]['qty'] * $combo_product_as_offer[$t->id]['multiple'];
                            $net_cart_amount = ($t->price - $discount_amount) * $qty_to_add;
                            Log::info(' discount_amount {id}', ['id' => $discount_amount]);
                            Log::info(' price {id}', ['id' => $t->price]);
                            Log::info(' net mao{id}', ['id' => $net_cart_amount]);

                            // array_push($coupon_ids, $combo_product_as_offer[$t->id]['coupon_id']);
                            $cart_offer_insert_ar[] = [
                                'product_id' => $t->id,
                                'user_id' => $user_id,
                                'name' => $t->name,
                                'sgst' => $t->sgst ?? 0,
                                'cgst' => $t->cgst ?? 0,
                                'igst' => $t->igst ?? 0,
                                'price' => $t->price,
                                'qty' => $qty_to_add,
                                'sale_price' => $t->price - $discount_amount,
                                'discount_type' => $discount_type,
                                'discount' => $discount_value,
                                'category_id' => $t->category_id,
                                'is_combo' => 'Yes',
                                'cart_session_id' => $cart_session_id,
                                'unit' => $t->unit, 'total_discount' => $discount_amount * $qty_to_add,
                                'net_cart_amount' => $net_cart_amount,
                                // 'coupon_ids' => json_encode($coupon_ids),
                            ];
                            $coupon_ids_applied[] = [$combo_product_as_offer[$t->id]['coupon_id']];
                            Log::info('cart inser offer {id}', ['id' => $cart_offer_insert_ar]);

                        } else {
                            $prev_qty = \DB::table('carts')->whereCartSessionId($row->cart_session_id)->whereUserId($user_id)->whereProductId($t->id)->first()->qty;
                            $new_qty_added = $combo_product_as_offer[$t->id]['qty'] * $combo_product_as_offer[$t->id]['multiple'];
                            $total_qty = $new_qty_added + $prev_qty;
                            $discount_type = $combo_product_as_offer[$t->id]['discount_type'];
                            $discount_value = $combo_product_as_offer[$t->id]['discount'];
                            $discount_amount = $discount_type == 'Flat' ? $discount_value : ($t->price * $discount_value / 100);
                            $net_cart_amount = ($t->price - $discount_amount) * $new_qty_added;
                            if ($total_qty > $new_qty_added) {
                                $net_cart_amount += ($t->sale_price) * ($total_qty - $new_qty_added);
                            }
                            // array_push($coupon_ids, $combo_product_as_offer[$t->id]['coupon_id']);
                            \DB::table('carts')->whereCartSessionId($row->cart_session_id)->whereUserId($user_id)->whereProductId($t->id)->update([
                                'qty' => $prev_qty > $new_qty_added ? $prev_qty : $new_qty_added,
                                'discount' => $combo_product_as_offer[$t->id]['discount'],
                                'discount_type' => $combo_product_as_offer[$t->id]['discount_type'],
                                'discount_applies_on_qty' => $new_qty,
                                'is_combo' => 'Yes', 'total_discount' => (($t->price - $t->sale_price) * ($total_qty - $new_qty_added)) + $discount_amount * $new_qty_added,
                                'net_cart_amount' => $net_cart_amount,
                                // 'coupon_ids' => json_encode($coupon_ids),
                            ]);
                            $coupon_ids_applied[] = [$combo_product_as_offer[$t->id]['coupon_id']];
                        }
                    }
                    if (!empty($cart_offer_insert_ar)) {
                        $is_cart_changed = true;
                        \DB::table('carts')->insert($cart_offer_insert_ar);
                    }

                }

            }
        } else {
            $is_cart_changed = true;
            \DB::table('carts')->where(['user_id' => $user_id, 'is_combo' => 'Yes',
                'cart_session_id' => $cart_session_id])->delete();
        }
        if (!empty($coupon_ids_applied)) {
            foreach ($coupon_ids_applied as $c_id) {
                \DB::insert('applied_coupons')->insert([
                    'coupon_id' => $c_id,
                    'user_id' => $user_id,
                    'cart_session_id' => $cart_session_id,
                    'coupon_method' => 'Automatic',
                    'coupon_type' => 'BOGO',
                ]);
            }
        }

    }
    public function applyAutomaticCartDiscount($cart_items, $user_id)
    {
        $cart_coupons = \App\Models\Coupon::where([
            'coupon_code' => $coupon_code, 'discount_method' => 'Automatic',

        ])->whereStatus('Active')->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())->orderBy('minimum_order_amount', 'DESC')->get();
        $coupon_selected = null;
        foreach ($cart_coupons as $coupon_row) {
            $coupon_eligibility = checkCouponEligibilityByCustomerGrpAndUseLimit($user_id, $coupon_row);
            if ($coupon_eligibility['is_eligible']) {
                $coupon_categories = $coupon_row->category_id != null ? array_column(json_decode($coupon_row->category_id, true), 'id') : [];
                $coupon_product_ids = $coupon_row->product_id != null ? array_column(json_decode($coupon_row->product_id, true), 'id') : [];
                $minimum_cart_amount_required = $coupon_row->minimum_order_amount;
                $sum_of_cart_amount = getCartAmountTotalConditionally($coupon_row->include_or_exclude, $cart_items, $coupon_categories, $coupon_product_ids);
                if ($sum_of_cart_amount > 0) {
                    if ($minimum_cart_amount_required != null && $sum_of_cart_amount < $minimum_cart_amount_required) {
                        continue;
                    } else {
                        $coupon_selected = $coupon_row;
                        break;
                    }
                }

            }
        }
        return $coupon_selected;
    }

    public function applyCouponCode(Request $r)
    {
        $cart_session_id = $r->cart_session_id;
        $coupon_code = $r->coupon_code;
        $email = $r->email;
        $phone = $r->phone;

        $user = \App\Models\User::where([
            'email' => $email,
            'phone' => $phone,

        ])->first();
        $coupon_row = \App\Models\Coupon::where([
            'coupon_code' => $coupon_code, 'discount_method' => 'Coupon Code',

        ])->whereStatus('Active')->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())->first();
        if (is_null($coupon_row)) {
            return response()->json(['data' => 'Coupon is invalid'], 400);
        }
        $coupon_id = $coupon_row->id;
        $user_id = $user->id;

        $coupon_discounts = [];
        $can_coupon_be_used = false;
        $error_message = '';
        $is_cart_updated = false;
        if (!is_null($coupon_row)) {

            $coupon_eligibility = checkCouponEligibilityByCustomerGrpAndUseLimit($user_id, $coupon_row);
            if (!$coupon_eligibility['is_eligible']) {
                return response()->json(['data' => $coupon_eligibility['error']], 400);
            } else {
                $coupon_applied_exist = \DB::table('applied_coupons')
                    ->whereUserId($user_id)->whereCouponType($coupon_row->type)
                    ->where('cart_session_id', $cart_session_id)->whereCouponId($coupon_id)->exists();
                if ($coupon_applied_exist) {
                    return response()->json(['data' => 'Coupon already applied'], 400);
                }

                $coupon_type = $coupon_row->type;
                $categories = $coupon_row->category_id != null ? array_column(json_decode($coupon_row->category_id, true), 'id') : [];
                $coupon_product_ids = $coupon_row->product_id != null ? array_column(json_decode($coupon_row->product_id, true), 'id') : [];
                $cart_items = \DB::table('cart')
                    ->whereUserId($user_id)
                    ->where('cart_session_id', $cart_session_id)->get();
                //$cart_product_ids = array_column($cart_items->toArray(), 'product_id');
                /***Validate For MinumCart Amount Requirement */
                $minimum_cart_amount_required = $coupon_row->minimum_order_amount;
                $sum_of_cart_amount = getCartAmountTotalConditionally($coupon_row->include_or_exclude, $cart_items, $categories, $coupon_product_ids);
                if ($minimum_cart_amount_required != null && $sum_of_cart_amount < $minimum_cart_amount_required) {
                    return response()->json(['data' => 'Minimum Cart Amount should be greater than ' . $minimum_cart_amount_required], 400);
                }

                $update_arr = [];
                $insert_arr = [];
                if ($coupon_type == 'Bulk') {

                    foreach ($cart_items as $t) {

                        $should_proceed = shouldCartItemIncludeForCoupon($coupon_row->include_or_exclude, $cart_items, $categories, $coupon_product_ids);
                        if ($should_proceed) {
                            $item_qty_present_in_cart = $t->qty;
                            $price = $t->price;
                            //array_push($existing_item_coupon_ids, $coupon_id);
                            $disc = $coupon_row->discount_type != null && $coupon_row->discount != null
                            ? ($coupon_row->discount_type == 'Flat'
                                ? $coupon_row->discount
                                : ($price * $coupon_row->discount / 100))
                            : 0;
                            $new_sale_price = $t->price - $disc;
                            $net_cart_amount = $new_sale_price * $t->qty;
                            $update_arr[] = ['id' => $t->id,
                                'discount_type' => $coupon_row->discount_type,
                                'discount' => $coupon_row->discount,
                                'net_cart_amount' => $net_cart_amount,
                                'product_discount_offer_detail' => $coupon_row->name,
                                'total_discount' => $disc * $t->qty, 'sale_price' => $new_sale_price,
                                //  'is_bulk_coupon_applied' => 'Yes',

                            ];
                        }

                    }
                    if (!empty($update_arr)) {
                        $cartInstance = new \App\Models\Cart;

                        Batch::update($cartInstance, $update_arr, 'id');
                        \DB::insert('applied_coupons')->insert([
                            'coupon_id' => $coupon_id,
                            'cart_session_id' => $cart_session_id,
                            'coupon_method' => $coupon_row->discount_method,
                            'coupon_type' => 'Bulk',
                            'user_id' => $user_id,
                        ]);
                        $updated_cart = \DB::table('carts')->where(['user_id' => $user_id,
                            'cart_session_id' => $row->cart_session_id])->get();
                        return response()->json(['data' => ['coupon_type' => 'Bulk',
                            'value' => $updated_cart, 'free_shipping' => $coupon_row->free_shipping]], 200);

                    } else {
                        return response()->json(['data' => ['coupon_type' => 'Bulk',
                            'value' => [], 'free_shipping' => 'No']], 200);
                    }

                } elseif ($coupon_type == 'Individual Quantity') {
                    foreach ($cart_items as $row) {
                        $should_proceed = shouldCartItemIncludeForCoupon($coupon_row->include_or_exclude, $cart_items, $categories, $coupon_product_ids);
                        $rules = getProductQtyRules($row, $user_id, 'Coupon Code');
                        if (!empty($rules) && $should_proceed) {

                            $p = getNetAmountAfterIndividualDiscountForSingleCartItem($row, $rules);
                            $coupon_ids = !empty($row->coupon_ids) ? json_decode($row->coupon_ids, true) : [];
                            array_push($coupon_ids, $rules[0]['coupon_id']);
                            $update_arr[] = ['id' => $row->id,
                                'discount_rules' => !empty($rules) ? json_encode($rules) : null,
                                'net_cart_amount' => $p['net_cart_amount'], 'total_discount' => $p['total_discount'],
                                'product_discount_offer_detail' => !empty($rules) ? $rules[0]['offer_text'] : null,
                                'total_tax' => $p['total_tax'],

                            ];

                        }
                    }
                    if (!empty($update_arr)) {
                        $cartInstance = new \App\Models\Cart;
                        $is_cart_updated = true;
                        Batch::update($cartInstance, $update_arr, 'id');
                        \DB::insert('applied_coupons')->insert([
                            'coupon_id' => $coupon_id,
                            'cart_session_id' => $cart_session_id,
                            'coupon_method' => $coupon_row->discount_method,
                            'coupon_type' => 'Individual Quantity', 'user_id' => $user_id,
                        ]);
                        $updated_cart = \DB::table('carts')->where(['user_id' => $user_id,
                            'cart_session_id' => $cart_session_id])->get();
                        return response()->json(['data' => ['coupon_type' => 'Individual Quantity',
                            'value' => $updated_cart, 'free_shipping' => $coupon_row->free_shipping]], 200);
                    } else {
                        return response()->json(['data' => ['coupon_type' => 'Individual Quantity',
                            'value' => [], 'free_shipping' => 'No']], 200);
                    }

                } elseif ($coupon_type == 'BOGO') {
                    $combo_product_as_offer = getBogoOfferForSingleCoupon($coupon_row, $cart_session_id, $user_id, 'Coupon Code');
                    if (!empty($combo_product_as_offer)) {

                        $prod_ids = array_keys($combo_product_as_offer);
                        if (!empty($prod_ids)) {
                            $cart_offer_insert_ar = [];
                            $prod_rec = \DB::table('products')->whereIn('id', $prod_ids)->get();
                            if (!empty(count($prod_rec->toArray()))) {
                                foreach ($prod_rec as $t) {
                                    $coupon_ids = !empty($t->coupon_ids) ? json_decode($t->coupon_ids) : [];
                                    if (!in_array($t->id, $cart_product_ids)) {

                                        $discount_type = $combo_product_as_offer[$t->id]['discount_type'];
                                        $discount_value = $combo_product_as_offer[$t->id]['discount'];
                                        $discount_amount = $discount_type == 'Flat' ? $discount_value : ($t->price * $discount_value / 100);
                                        $qty_to_add = $combo_product_as_offer[$t->id]['qty'] * $combo_product_as_offer[$t->id]['multiple'];
                                        $net_cart_amount = ($t->price - $discount_amount) * $qty_to_add;

                                        array_push($coupon_ids, $combo_product_as_offer[$t->id]['coupon_id']);
                                        $cart_offer_insert_ar[] = [
                                            'product_id' => $t->id,
                                            'user_id' => $user_id,
                                            'name' => $t->name,
                                            'sgst' => $t->sgst ?? 0,
                                            'cgst' => $t->cgst ?? 0,
                                            'igst' => $t->igst ?? 0,
                                            'price' => $t->price,
                                            'qty' => $qty_to_add,
                                            'sale_price' => $t->price - $discount_amount,
                                            'discount_type' => $discount_type,
                                            'discount' => $discount_value,
                                            'category_id' => $t->category_id,
                                            'is_combo' => 'Yes',
                                            'cart_session_id' => $row->cart_session_id,
                                            'unit' => $t->unit, 'total_discount' => $discount_amount * $qty_to_add,
                                            'net_cart_amount' => $net_cart_amount,
                                            // 'coupon_ids' => json_encode($coupon_ids),
                                        ];
                                        Log::info('cart inser offer {id}', ['id' => $cart_offer_insert_ar]);
                                    } else {
                                        $prev_qty = \DB::table('carts')->whereCartSessionId($row->cart_session_id)->whereUserId($user_id)->whereProductId($t->id)->first()->qty;
                                        $new_qty_added = $combo_product_as_offer[$t->id]['qty'] * $combo_product_as_offer[$t->id]['multiple'];
                                        $total_qty = $new_qty_added + $prev_qty;
                                        $discount_type = $combo_product_as_offer[$t->id]['discount_type'];
                                        $discount_value = $combo_product_as_offer[$t->id]['discount'];
                                        $discount_amount = $discount_type == 'Flat' ? $discount_value : ($t->price * $discount_value / 100);
                                        $net_cart_amount = ($t->price - $discount_amount) * $new_qty_added;
                                        if ($total_qty > $new_qty_added) {
                                            $net_cart_amount += ($t->sale_price) * ($total_qty - $new_qty_added);
                                        }
                                        array_push($coupon_ids, $combo_product_as_offer[$t->id]['coupon_id']);
                                        \DB::table('carts')->whereCartSessionId($row->cart_session_id)->whereUserId($user_id)->whereProductId($t->id)->update([
                                            'qty' => $prev_qty > $new_qty_added ? $prev_qty : $new_qty_added,
                                            'discount' => $combo_product_as_offer[$t->id]['discount'],
                                            'discount_type' => $combo_product_as_offer[$t->id]['discount_type'],
                                            'discount_applies_on_qty' => $new_qty,
                                            'is_combo' => 'Yes', 'total_discount' => (($t->price - $t->sale_price) * ($total_qty - $new_qty_added)) + $discount_amount * $new_qty_added,
                                            'net_cart_amount' => $net_cart_amount,
                                            'coupon_ids' => json_encode($coupon_ids),
                                        ]);

                                    }
                                }
                                if (!empty($cart_offer_insert_ar)) {
                                    \DB::table('carts')->insert($cart_offer_insert_ar);
                                    \DB::insert('applied_coupons')->insert([
                                        'coupon_id' => $coupon_id,
                                        'cart_session_id' => $cart_session_id,
                                        'coupon_method' => $coupon_row->discount_method,
                                        'coupon_type' => 'BOGO', 'user_id' => $user_id,
                                    ]);
                                    $is_cart_updated = true;
                                }

                            }

                        }
                    } else {
                        \DB::table('carts')->where(['user_id' => $user_id, 'is_combo' => 'Yes',
                            'cart_session_id' => $row->cart_session_id])->delete();
                        $is_cart_updated = true;
                    }
                    if ($is_cart_updated) {
                        $updated_cart = \DB::table('carts')->where(['user_id' => $user_id,
                            'cart_session_id' => $row->cart_session_id])->get();
                        return response()->json(['data' => ['coupon_type' => 'BOGO',
                            'value' => $updated_cart, 'free_shipping' => $coupon_row->free_shipping]], 200);
                    } else {
                        return response()->json(['data' => ['coupon_type' => 'BOGO',
                            'value' => [], 'free_shipping' => 'No']], 200);
                    }

                } elseif ($coupon_type == 'Cart') {

                    $resp_ar = applyCartLevelDiscountOffer($coupon_row, $cart_sum, $cart_session_id, $user_id);

                    return response()->json(['data' => $resp_ar], 200);
                } elseif ($coupon_type == 'Shipping') {
                    $resp_ar = applyShippingOffer($coupon_row, $cart_session_id, $user_id);

                    return response()->json([
                        'data' => $resp]
                        , 200);
                }
            }
        }
        return response()->json(['data' => $coupon_discounts], 200);
    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r, $id)
    {
        Log::info("cart delete called {id}", ['id' => $id]);
        $carts = \DB::table('carts')->whereId($id)->delete();

        return response()->json(['data' => 'delete auccessfully'], 200);
    }
}
