<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
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

        $email = $r->email;
        $phone = $r->phone;
        try {
            $user = \App\Models\User::where([
                'email' => $email,
                //   'phone' => $phone,

            ])->first();
            $user_id = $user->id;
            $cart_items = \DB::table('carts')->whereUserId($user_id)->get();

            $applied_coupons_names = [];
            $cartValueAndShippingDiscountresult = null;
            if (!empty($cart_items[0])) {
                $cart_session_id = $cart_items[0]->cart_session_id;
                $p = applyAppliedCouponsTableToCart($cart_items, $cart_session_id, $user_id, false);
                $cartValueAndShippingDiscountresult = $p['cartValueAndrShippingDiscountresult'];
                $applied_coupons_names = $p['applied_coupons_names'];
            }
            //  dd(cart_update_arr);

            $minimu_cart_offer = getOnylCartMinimumAmountOffers($cart_items, $user_id);
            $eligible_offer = getEligibleOffers($cart_items, $user_id);
            $applied_coupons_names = remove_duplicate_coupon_names($applied_coupons_names);
            return response()->json(['data' => $cart_items,
                'cart_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['cart_amount_discount'] : 0.0,
                'shipping_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['shipping_discount'] : 0.0,
                'applicable_offers' => !empty($eligible_offer) ? remove_duplicate_coupon_names1($eligible_offer) : null,
                'minimum_cart_amount_offer' => !empty($minimu_cart_offer) ? $minimu_cart_offer[0] : null,
                'applied_coupons' => !empty($applied_coupons_names) ? $applied_coupons_names : null,

            ], 200);
        } catch (\Exception $ex) {
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Cart Index function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }
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
        $cart_session_id = null;
       
        \DB::beginTransaction();
        try
        {
            if (!empty($cart_id)) {
                $row = Cart::whereId($cart_id)->first();
                if ($post['qty'] == 0 || empty($post['qty'])) {
                    $row->delete();
                } else {
                    $qty = $r->qty;
                    $row->update([
                        'qty' => $qty,
                        'net_cart_amount' => $qty * $r->sale_price,
                        'total_discount' => ($r->price - $r->sale_price) * $qty,
                        'total_tax' => $r->sale_price * (($r->sgst + $r->sgst + $r->igst) / 100) * $qty,
                    ]);

                }
                $cart_session_id = $row->cart_session_id;
            } else {
                $qty = $post['qty'];
                if (empty($post['variant_id'])) {
                    $prod_row = \DB::table('products')->whereId($post['product_id'])->first();
                    $avaialble_stock = $prod_row->quantity;
                    if ($avaialble_stock < $qty) {
                        return response()->json(['message' => 'No more product is available ,Please try later when stock is back'], 400);
                    }

                } else {
                    $prod_row = \DB::table('product_variants')->whereId($post['variant_id'])->first();
                    $avaialble_stock = $prod_row->quantity;
                    if ($avaialble_stock < $qty) {
                        return response()->json(['message' => 'No more product is available ,Please try later when stock is back'], 400);
                    }
                }
                $post['is_combo'] = 'No';

                $sale_price = $post['sale_price'];
                $price = $post['price'];
                $latest_cart_item = Cart::whereUserId($user_id)->latest()->first();
                $cart_session_id = is_null($latest_cart_item) ? $user_id . uniqid() : $latest_cart_item->cart_session_id;
                $post['cart_session_id'] = $cart_session_id;
                $post['user_id'] = $user_id;
                $post['net_cart_amount'] = $qty * $sale_price;
                $post['total_discount'] = ($price - $sale_price) * $qty;
                $post['total_tax'] = $sale_price * (($post['sgst'] + $post['sgst'] + $post['igst']) / 100) * $qty;
                $row = Cart::create($post);

            }

            $cart_items = \DB::table('carts')->whereCartSessionId($cart_session_id)->whereUserId($user_id)->get();
            /***belwo one  will add automtic coupons to table with insert and update_ar in the applied coupon table */
            $this->createAppliedCoupons($cart_items, $user_id);

            $applied_coupons_names = [];
            $cartValueAndShippingDiscountresult = null;
            if (!empty($cart_items)) {
                $cart_session_id = $cart_items[0]->cart_session_id;
                /***bewlo one line will apply applied coupons to cart items */
                $p = applyAppliedCouponsTableToCart($cart_items, $cart_session_id, $user_id);
                $cartValueAndrShippingDiscountresult = $p['cartValueAndrShippingDiscountresult'];
                $applied_coupons_names = $p['applied_coupons_names'];
            }
            $cart_items = \DB::table('carts')->whereCartSessionId($cart_session_id)->whereUserId($user_id)->get();

            $minimu_cart_offer = getOnylCartMinimumAmountOffers($cart_items, $user_id);
            $eligible_offer = getEligibleOffers($cart_items, $user_id);

            \DB::commit();
            $applied_coupons_names = remove_duplicate_coupon_names($applied_coupons_names);
            return response()->json(['data' => $cart_items,
                'cart_discount' => $cartValueAndrShippingDiscountresult ? $cartValueAndrShippingDiscountresult['cart_amount_discount'] : 0.0,
                'shipping_discount' => $cartValueAndrShippingDiscountresult ? $cartValueAndrShippingDiscountresult['shipping_discount'] : 0.0,
                'applicable_offers' => !empty($eligible_offer) ? remove_duplicate_coupon_names1($eligible_offer) : null,

                'minimum_cart_amount_offer' => !empty($minimu_cart_offer) ? $minimu_cart_offer[0] : null,
                'applied_coupons' => !empty($applied_coupons_names) ? $applied_coupons_names : null,

            ], 201);

        } catch (\Exception $ex) {
            \DB::rollback();
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Cart Saving/updating',
            ]);
            return response()->json(['message' => $ex->getMessage()], 400);

        }
    }

    public function createAppliedCoupons($cart_items, $user_id)
    {
        $applicable_coupon_rows = [];
        $cart_session_id = $cart_items[0]->cart_session_id;
        $cart_update_arr_for_product_offer_detail = [];

        $valid_coupons = \App\Models\Coupon::where([
            'discount_method' => 'Automatic',
        ])->whereStatus('Active')->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())->orderBy('minimum_order_amount', 'DESC')->get();
        if (!empty($valid_coupons->toArray())) {
            foreach ($valid_coupons as $coupon_row) {

                $result = checkCouponApplicabilityForInsert($cart_items, $user_id, $coupon_row);

                $applicable_coupon_rows = array_merge($applicable_coupon_rows, $result['applicable_coupon_rows']);
                $cart_update_arr_for_product_offer_detail = array_merge($cart_update_arr_for_product_offer_detail, $result['cart_update_arr_for_product_offer_detail']);

            }
            /***applicable_coupon_rows== wo coupons rows hai jo ki applied table mein insert hone ke yogya ha****/
            \DB::table('applied_coupons')->whereCartSessionId($cart_session_id)->whereUserId($user_id)
                ->whereCouponMethod('Automatic')->delete();

            insertApplicableCouponsForInsertIntoAppliedTableWithProductOfferTextUpdate($cart_session_id, $user_id,
                $applicable_coupon_rows, $cart_update_arr_for_product_offer_detail, $cart_items
            );

        }

        return;
    }

    public function applyCouponCode(Request $r)
    {

        $cart_session_id = $r->cart_session_id;
        $coupon_code = $r->coupon_code;
        $email = $r->email;
        $phone = $r->phone;
        try {
            $user = \App\Models\User::where([
                'email' => $email,
                'phone' => $phone,

            ])->first();
            $coupon_row = \App\Models\Coupon::where([
                'coupon_code' => $coupon_code, 'discount_method' => 'Coupon Code',

            ])->whereStatus('Active')->first();
            if (is_null($coupon_row)) {
                return response()->json(['message' => 'Coupon is invalid'], 400);
            }
            $cart_update_arr = [];
            $cart_insert_arr = [];
            $coupon_id = $coupon_row->id;
            $user_id = $user->id;
            $applicable_coupon_rows = [];
            $cart_update_arr_for_product_offer_detail = [];
            $cart_items = \DB::table('carts')->whereUserId($user_id)->get();
            $result = checkCouponApplicabilityForInsert($cart_items, $user_id, $coupon_row);
            $applicable_coupon_rows = $result['applicable_coupon_rows'];
         
            if (empty($applicable_coupon_rows)) {
                return response()->json(['message' => 'Coupon conditions not satisfied,so not applied.Please read coupon terms & conditions'], 400);
            }

            $cart_update_arr_for_product_offer_detail = $result['cart_update_arr_for_product_offer_detail'];
            $exist_count = \DB::table('applied_coupons')->whereCartSessionId($cart_session_id)->whereUserId($user_id)
                ->whereCouponMethod('Coupon Code')->whereCouponId($coupon_id)->count();
            if ($exist_count > 0) {
                return response()->json(['message' => 'Coupon is already used'], 400);
            }

            /***creating array for update or insert in cart items due to above coupon when applied   */

            insertApplicableCouponsForInsertIntoAppliedTableWithProductOfferTextUpdate($cart_session_id, $user_id,
                $applicable_coupon_rows, $cart_update_arr_for_product_offer_detail, $cart_items
            );
          
            $cart_items = \DB::table('carts')->whereUserId($user_id)->get();

            $applied_coupons_names = [];
            $cartValueAndShippingDiscountresult = null;
            if (!empty($cart_items)) {
                $cart_session_id = $cart_items[0]->cart_session_id;
                $p = applyAppliedCouponsTableToCart($cart_items, $cart_session_id, $user_id);

                $cartValueAndShippingDiscountresult = $p['cartValueAndrShippingDiscountresult'];
                $applied_coupons_names = $p['applied_coupons_names'];
            }
            //  dd(cart_update_arr);
            $applied_coupons_names = remove_duplicate_coupon_names($applied_coupons_names);
            $minimu_cart_offer = getOnylCartMinimumAmountOffers($cart_items, $user_id);
            $eligible_offer = getEligibleOffers($cart_items, $user_id);
            $exist = \DB::table('applied_coupons')->whereCartSessionId($cart_session_id)->whereUserId($user_id)
                ->whereCouponId($coupon_id)->count();
            $is_coupon_applied = $exist > 0 ? true : false;

            $cart_items = \DB::table('carts')->whereUserId($user_id)->get();

            if ($is_coupon_applied) {
                return response()->json(['data' => $cart_items,
                    'cart_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['cart_amount_discount'] : 0.0,
                    'shipping_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['shipping_discount'] : 0.0,
                    'applicable_offers' => !empty($eligible_offer) ? remove_duplicate_coupon_names1($eligible_offer) : null,

                    'minimum_cart_amount_offer' => !empty($minimu_cart_offer) ? $minimu_cart_offer[0] : null,
                    'applied_coupons' => !empty($applied_coupons_names) ? $applied_coupons_names : null,
                    'coupon_response' => 'Coupon Code ' . $coupon_row->coupon_code . ' applied successfully',

                ], 200);
            } else {
                return response()->json([
                    'message' => \Session::has('error') ? session('error') : 'Coupon failed to be applied'], 400);
            }
        } catch (\Exception $ex) {
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Apply Coupon Code function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }

    }
    public function removeCoupon(Request $r)
    {
        $cart_session_id = $r->cart_session_id;
     
        $coupon_code = $r->coupon_code;
        $email = $r->email;
        $phone = $r->phone;
        try {
            $user = \App\Models\User::where([
                'email' => $email,
                'phone' => $phone,

            ])->first();
            $user_id = $user->id;
            $coupon_row = \App\Models\Coupon::where([
                'coupon_code' => $coupon_code, 'discount_method' => 'Coupon Code',

            ])->first();
         
            $applied_coupon_rows = null;

            if (is_null($coupon_row)) {
                return response()->json(['message' => 'Coupon is invalid'], 400);
            } else {
                $applied_coupon_rows = \DB::table('applied_coupons')->where([
                    'coupon_id' => $coupon_row->id, 'coupon_method' => 'Coupon Code',
                    'user_id' => $user->id, 'cart_session_id' => $cart_session_id,

                ])->get();
                \DB::enableQueryLog();
                \DB::table('applied_coupons')->where([
                    'coupon_id' => $coupon_row->id, 'coupon_method' => 'Coupon Code',
                    'user_id' => $user->id, 'cart_session_id' => $cart_session_id,

                ])->delete();
                dlog('delete log',\DB::getQueryLog());
                \DB::table('coupon_usage_by_customers')->whereUserId($user_id)->whereCouponId($coupon_row->id)->decrement('count', 1);
            }
            $cart_items = \DB::table('carts')->whereUserId($user_id)->get();
            $update_ar = [];
            $insert_ar = [];
            foreach ($applied_coupon_rows as $g) {

                if ($g->insert_ar != null) {
                    $insert_ar = array_merge($insert_ar, [...json_decode($g->insert_ar, true)]);
                }

                if ($g->update_ar != null) {
                    $update_ar = array_merge($update_ar, [...json_decode($g->update_ar, true)]);
                }

            }

            if (!empty($update_ar)) {
                $cartInstance = new \App\Models\Cart;
                $updates = [];
                foreach ($update_ar as $p) {
                    $item_id = $p['id'];
                    $filteres = [];
                    $g = $cart_items->toArray();
                    foreach ($g as $v) {
                        if ($v->id == $item_id) {
                            $filteres[] = $v;
                        }
                    }

                    foreach ($filteres as $related_cart_item) {
                        $t = $related_cart_item;
                        $item_qty_present_in_cart = $t->qty;
                        $price = $t->price;

                        $net_cart_amount = $t->sale_price * $t->qty;
                        $updates[] = ['id' => $t->id,
                            'discount_type' => null,
                            'discount' => 0,
                            'total_discount' => ($t->price - $t->sale_price) * $t->qty,
                            'net_cart_amount' => $net_cart_amount,
                            'product_discount_offer_detail' => '',
                            'is_combo' => 'No',
                            'discount_applies_on_qty' => null,
                        ];
                    }
                }
                foreach ($updates as $y) {
                    \DB::table('carts')->whereId($y['id'])->update($y);

                }

            }

            if (!empty($insert_ar)) {
                $deleteable_cart_ids = [];
                foreach ($insert_ar as $y) {
                    $prod_id = $y['product_id'];
                    $g = $cart_items->toArray();

                    foreach ($g as $v) {
                        if ($v->product_id == $prod_id && $v->cart_session_id = $cart_session_id &&
                            $v->user_id == $user_id && $v->is_combo == 'Yes') {

                            $deleteable_cart_ids[] = $v->id;
                        }
                    }

                    \DB::table('carts')->whereIn('id', $deleteable_cart_ids)->delete();

                }
            }

            $applied_coupons_names = [];
            $cartValueAndShippingDiscountresult = null;
            if (!empty($cart_items)) {
                $cart_session_id = $cart_items[0]->cart_session_id;
                $p = applyAppliedCouponsTableToCart($cart_items, $cart_session_id, $user_id, false);
                $cartValueAndShippingDiscountresult = $p['cartValueAndrShippingDiscountresult'];
                $applied_coupons_names = $p['applied_coupons_names'];
            }
            //  dd(cart_update_arr);

            $minimu_cart_offer = getOnylCartMinimumAmountOffers($cart_items, $user_id);
            $eligible_offer = getEligibleOffers($cart_items, $user_id);

            $cart_items = \DB::table('carts')->whereUserId($user_id)->get();
            $applied_coupons_names = remove_duplicate_coupon_names($applied_coupons_names);
            return response()->json(['data' => $cart_items,
                'cart_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['cart_amount_discount'] : 0.0,
                'shipping_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['shipping_discount'] : 0.0,
                'applicable_offers' => !empty($eligible_offer) ? remove_duplicate_coupon_names1($eligible_offer) : null,

                'minimum_cart_amount_offer' => !empty($minimu_cart_offer) ? $minimu_cart_offer[0] : null,
                'applied_coupons' => !empty($applied_coupons_names) ? $applied_coupons_names : null,

            ], 200);
        } catch (\Exception $ex) {
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Revmoe Coupon function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }

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
        \DB::beginTransaction();
        try {
            $cart_item = \DB::table('carts')->whereId($id)->first();
            $user_id = $cart_item->user_id;
            /**affected_by_coupon_ids shows the id in applied_coupon row not coupon id */
            $affected_coupon_ids = !empty($cart_item->affected_by_coupon_ids)
            ? json_decode($cart_item->affected_by_coupon_ids, true) : [];
            $cart_items = \DB::table('carts')->whereUserId($cart_item->user_id)
                ->where('id', '!=', $id)
                ->where('cart_session_id', $cart_item->cart_session_id)->get();
            $deletable_row_ids = [];
            $item_product_id = $cart_item->product_id;
            $affected_coupon_ids_row = null;
          
            if (!empty($affected_coupon_ids)) {
                $affected_coupon_ids_row = \DB::table('applied_coupons')->whereIn('id', $affected_coupon_ids)->get();
            } else {
                $affected_coupon_ids_row = \DB::table('applied_coupons')->where('due_to_product_id', $item_product_id)->get();
            }

            $insert_ar = [];
            $update_ar = [];
          
            foreach ($affected_coupon_ids_row as $g) {

                if (!empty($g->due_to_product_id) && $g->due_to_product_id == $item_product_id) {
                    array_push($deletable_row_ids, $g->id);
                  
                } else {
                    $should_delete_this_id = true;
                   
                    if (!empty($cart_items)) {
                        foreach ($cart_items as $t) {

                            $item_affected_coupon_ids = !empty($t->affected_by_coupon_ids)
                            ? json_decode($t->affected_by_coupon_ids, true) : [];

                            if (!empty($item_affected_coupon_ids)) {
                                if (in_array($g->coupon_id, $item_affected_coupon_ids)) {

                                    $should_delete_this_id = false;
                                    break;

                                }

                            }
                        }
                      
                        if ($should_delete_this_id) {
                            if ($g->insert_ar != null) {
                                $insert_ar = array_merge($insert_ar, [...json_decode($g->insert_ar, true)]);
                            }
        
                            if ($g->update_ar != null) {
                                $update_ar = array_merge($update_ar, [...json_decode($g->update_ar, true)]);
                            }
        
                            array_push($deletable_row_ids, $g->id);
                        }
                    }
                }
               
            }

            if (!empty($deletable_row_ids)) {
                \DB::table('applied_coupons')->whereIn('id', $deletable_row_ids)->delete();
            }
            if (!empty($insert_ar)) {
                $insert_ar = array_map(function ($element) {
                    return (array) $element;
                }, $insert_ar);

                \DB::table('carts')->whereIn('product_id', array_column($insert_ar, 'product_id'))->delete();
            }
            if (!empty($update_ar)) {
                \DB::table('carts')->whereIn('product_id', array_column($update_ar, 'product_id'))->delete();
            }
            $carts = \DB::table('carts')->whereId($id)->delete();
            \DB::commit();
            $cart_items = \DB::table('carts')->whereUserId($user_id)->get();

            $applied_coupons_names = [];
            $cartValueAndShippingDiscountresult = null;
            if (!empty($cart_items[0])) {
                $cart_session_id = $cart_items[0]->cart_session_id;
                $p = applyAppliedCouponsTableToCart($cart_items, $cart_session_id, $user_id, false);
                $cartValueAndShippingDiscountresult = $p['cartValueAndrShippingDiscountresult'];
                $applied_coupons_names = $p['applied_coupons_names'];
            }
            //  dd(cart_update_arr);

            $minimu_cart_offer = getOnylCartMinimumAmountOffers($cart_items, $user_id);
            $eligible_offer = getEligibleOffers($cart_items, $user_id);
            $applied_coupons_names = remove_duplicate_coupon_names($applied_coupons_names);
            return response()->json(['data' => $cart_items,
                'cart_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['cart_amount_discount'] : 0.0,
                'shipping_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['shipping_discount'] : 0.0,
                'applicable_offers' => !empty($eligible_offer) ? remove_duplicate_coupon_names1($eligible_offer) : null,
                'minimum_cart_amount_offer' => !empty($minimu_cart_offer) ? $minimu_cart_offer[0] : null,
                'applied_coupons' => !empty($applied_coupons_names) ? $applied_coupons_names : null,

            ], 200);

        } catch (\Exception $ex) {
            \DB::rollback();
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Cart Delete  function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }
    }
}
