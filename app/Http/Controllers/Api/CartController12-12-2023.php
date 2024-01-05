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
        return response()->json(['data' => $cart_items,
            'cart_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['cart_amount_discount'] : 0.0,
            'shipping_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['shipping_discount'] : 0.0,
            'applicable_offers' => !empty($eligible_offer) ? $eligible_offer : null,
            'minimum_cart_amount_offer' => !empty($minimu_cart_offer) ? $minimu_cart_offer[0] : null,
            'applied_coupons' => !empty($applied_coupons_names) ? $applied_coupons_names : null,

        ], 200);
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
        // Log::info('cart data {id}', ['id' => json_encode($r->all())]);
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

            $post['is_combo'] = 'No';
            $qty = $post['qty'];
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

        $this->createAppliedCoupons($cart_items, $user_id);

        $applied_coupons_names = [];
        $cartValueAndShippingDiscountresult = null;
        if (!empty($cart_items)) {
            $cart_session_id = $cart_items[0]->cart_session_id;
            $p = applyAppliedCouponsTableToCart($cart_items, $cart_session_id, $user_id);
            $cartValueAndrShippingDiscountresult = $p['cartValueAndrShippingDiscountresult'];
            $applied_coupons_names = $p['applied_coupons_names'];
        }
        //  dd(cart_update_arr);

        $minimu_cart_offer = getOnylCartMinimumAmountOffers($cart_items, $user_id);
        $eligible_offer = getEligibleOffers($cart_items, $user_id);
        return response()->json(['data' => $cart_items,
            'cart_discount' => $cartValueAndrShippingDiscountresult ? $cartValueAndrShippingDiscountresult['cart_amount_discount'] : 0.0,
            'shipping_discount' => $cartValueAndrShippingDiscountresult ? $cartValueAndrShippingDiscountresult['shipping_discount'] : 0.0,
            'applicable_offers' => !empty($eligible_offer) ? $eligible_offer : null,
            'minimum_cart_amount_offer' => !empty($minimu_cart_offer) ? $minimu_cart_offer[0] : null,
            'applied_coupons' => !empty($applied_coupons_names) ? $applied_coupons_names : null,

        ], 201);
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
            dlog('valid coupons',$valid_coupons);
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

        $user = \App\Models\User::where([
            'email' => $email,
            'phone' => $phone,

        ])->first();
        $coupon_row = \App\Models\Coupon::where([
            'coupon_code' => $coupon_code, 'discount_method' => 'Coupon Code',

        ])->whereStatus('Active')->first();
        if (is_null($coupon_row)) {
            return response()->json(['data' => 'Coupon is invalid'], 400);
        }
        $cart_update_arr = [];
        $cart_insert_arr = [];
        $coupon_id = $coupon_row->id;
        $user_id = $user->id;
        $applicable_coupon_rows = [];
        $cart_update_arr_for_product_offer_detail = [];
        $cart_items = \DB::table('carts')->whereUserId($user_id)->get();
        $result = checkCouponApplicabilityForInsert($cart_items, $user_id, $coupon_row);

        $applicable_coupon_rows = array_merge($applicable_coupon_rows, $result['applicable_coupon_rows']);
        $cart_update_arr_for_product_offer_detail = array_merge($cart_update_arr_for_product_offer_detail, $result['cart_update_arr_for_product_offer_detail']);
        $exist_count = \DB::table('applied_coupons')->whereCartSessionId($cart_session_id)->whereUserId($user_id)
            ->whereCouponMethod('Coupon Code')->whereCouponId($coupon_id)->count();
        if ($exist_count > 0) {
            return response()->json(['data' => 'Coupon is already used'], 400);
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
                'applicable_offers' => !empty($eligible_offer) ? $eligible_offer : null,
                'minimum_cart_amount_offer' => !empty($minimu_cart_offer) ? $minimu_cart_offer[0] : null,
                'applied_coupons' => !empty($applied_coupons_names) ? $applied_coupons_names : null,
                'coupon_response' => 'Coupon Code ' . $coupon_row->coupon_code . ' applied successfully',

            ], 200);
        } else {
            return response()->json([
                'data' => \Session::has('error') ? session('error') : 'Coupon failed to be applied'], 400);
        }

    }
    public function removeCoupon(Request $r)
    {
        $cart_session_id = $r->cart_session_id;
        $coupon_code = $r->coupon_code;
        $email = $r->email;
        $phone = $r->phone;

        $user = \App\Models\User::where([
            'email' => $email,
            'phone' => $phone,

        ])->first();
        $user_id = $user->id;
        $coupon_row = \App\Models\Coupon::where([
            'coupon_code' => $coupon_code, 'discount_method' => 'Coupon Code',

        ])->first();
        $applied_coupon_row = null;

        if (is_null($coupon_row)) {
            return response()->json(['data' => 'Coupon is invalid'], 400);
        } else {
            $applied_coupon_row = \DB::table('applied_coupons')->where([
                'coupon_id' => $coupon_row->id, 'coupon_method' => 'Coupon Code',
                'user_id' => $user->id, 'cart_session_id' => $cart_session_id,

            ])->first();
            \DB::table('applied_coupons')->where([
                'id' => $applied_coupon_row->id, 'coupon_method' => 'Coupon Code',
                'user_id' => $user->id, 'cart_session_id' => $cart_session_id,

            ])->delete();
        }
        $cart_items = \DB::table('carts')->whereUserId($user_id)->get();
        $update_ar = $applied_coupon_row->update_ar != null ? json_decode($applied_coupon_row->update_ar, true) : null;
        $insert_ar = $applied_coupon_row->insert_ar != null ? json_decode($applied_coupon_row->insert_ar, true) : null;
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

        return response()->json(['data' => $cart_items,
            'cart_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['cart_amount_discount'] : 0.0,
            'shipping_discount' => $cartValueAndShippingDiscountresult ? $cartValueAndShippingDiscountresult['shipping_discount'] : 0.0,
            'applicable_offers' => !empty($eligible_offer) ? $eligible_offer : null,
            'minimum_cart_amount_offer' => !empty($minimu_cart_offer) ? $minimu_cart_offer[0] : null,
            'applied_coupons' => !empty($applied_coupons_names) ? $applied_coupons_names : null,

        ], 200);

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
        $cart_item = \DB::table('carts')->whereId($id)->first();
        $affected_coupon_ids = !empty($cart_item->affected_coupon_ids)
        ? json_decode($cart_item->affected_coupon_ids, true) : [];
        $cart_items = \DB::table('carts')->whereUserId($cart_item->user_id)
            ->where('id', '!=', $cart_item)
            ->where('cart_session_id', $cart_item->cart_session_id)->get();
        $deletable_coupon_ids = [];
        foreach ($affected_coupon_id as $g) {
            $should_delete_this_id = true;
            foreach ($cart_items as $t) {
                $item_affected_coupon_ids = !empty($cart_item->affected_coupon_ids)
                ? json_decode($cart_item->affected_coupon_ids, true) : [];
                if (!empty($item_affected_coupon_ids)) {
                    if (in_array($g, $item_affected_coupon_ids)) {
                        $should_delete_this_id = false;
                    }

                }
            }
            if ($should_delete_this_id) {
                array_push($deletable_coupon_ids, $g);
            }

        }
        if (!empty($deletable_coupon_ids)) {
            \DB::table('applied_coupons')->whereIn('id', $deletable_coupon_ids)->delete();
        }
        $carts = \DB::table('carts')->whereId($id)->delete();

        return response()->json(['data' => 'delete auccessfully'], 200);
    }
}
