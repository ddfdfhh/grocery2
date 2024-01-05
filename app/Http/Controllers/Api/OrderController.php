<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Controller;
use Batch;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        //
    }

    public function show($id)
    {
        $order = \App\Models\Order::with(['user' => function ($q) {
            $q->select('id', 'name', 'phone', 'lat', 'lang', 'address', 'pincode');
        }, 'driver' => function ($q) {
            $q->select('id', 'name', 'phone');
        }, 'items'])->whereId($id)->first();

        return response()->json(['data' => $order], 200);
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

        $setting = \DB::table('settings')->first();
        $shipping_charge = $setting != null ? $setting->delivery_charge : 0;
        $email = $r->email;
        $phone = $r->phone;
        $cart_level_discount = $r->cart_level_discount;
        $shipping_discount = $r->shipping_discount;
        $shipping_cost = $shipping_charge - $shipping_discount;

        $user = \App\Models\User::where([
            'email' => $email,
            'phone' => $phone,

        ])->first();
        $user_id = $user->id;
        $cart_session_id = $r->cart_session_id;

        $carts = \DB::table('carts')->whereUserId($user_id)->where('cart_session_id', $cart_session_id)->get()->toArray();
        $order_items_ar = [];
        $total_items = 0;
        $total_discount = 0;
        $net_payable = 0;
        $total_amount_after_discount = 0;
        $total_amount = 0;
        $total_tax = 0;
        $product_qty_update_ar = [];
        $variant_qty_update_ar = [];
        foreach ($carts as $t) {

            unset($t->id);
            unset($t->category_id);
            unset($t->created_at);
            unset($t->updated_at);
            unset($t->discount_applies_on_qty);
            unset($t->affected_by_coupon_ids);
            $total_items += 1;

            $order_items_ar[] = (array) $t;
            $total_discount += $t->total_discount;
            $total_amount_after_discount += $t->net_cart_amount;
            $total_amount += $t->price * $t->qty;
            $total_tax += $t->total_tax;
            $product_qty_update_ar[] = ['id' => $t->product_id, 'quantity' => ['-', $t->qty]];
            if (!empty($t->variant_id)) {

                $variant_qty_update_ar[] = ['id' => $t->variant_id, 'quantity' => ['-', $t->qty]];
            }

        }
        //   dd($order_items_ar);
        $orderid = null;
        \DB::beginTransaction();
        try
        {

            $prodInstance = new \App\Models\Product;
            Batch::update($prodInstance, $product_qty_update_ar, 'id');
            if (!empty($variant_qty_update_ar)) {
                $prodInstance1 = new \App\Models\ProductVariant;
                Batch::update($prodInstance1, $variant_qty_update_ar, 'id');
            }
            if (!empty($order_items_ar)) {
                \DB::table('order_items')->whereCartSessionId($cart_session_id)->whereUserId($user_id)->delete();
                \DB::table('orders')->whereCartSessionId($cart_session_id)->whereUserId($user_id)->delete();
                unset($order_items_ar['max_qty_allowed']);

                \DB::table('order_items')->insert($order_items_ar);
                $net_payable = $total_amount_after_discount + $total_tax + $shipping_cost - $cart_level_discount;
                $razor_orderId = null;
                if ($r->payment_method == 'Online') {
                    $report = new PaymentController;
                    $razor_orderId = $report->createOrderId($net_payable, $user_id);
                }
                $order_id = date('ymdhi') . $user_id;
                $status_updates = [
                    [
                        'name' => 'Order Placed',
                        'message' => '', 'date' => date('Y-m-d H:i:s'),
                    ],
                ];
                $order_ar = [
                    'total_amount' => $total_amount,
                    'total_amount_after_discount' => $total_amount_after_discount,
                    'cart_session_id' => $cart_session_id,
                    'net_payable' => $net_payable,
                    'shipping_cost' => $shipping_cost,
                    'total_tax' => $total_tax,
                    'total_discount' => $total_discount,
                    'user_id' => $user_id,
                    'slot_time' => $r->slot_time,
                    'no_of_items' => $total_items,
                    'slot_date' => date("Y-m-d", strtotime($r->slot_date)),
                    'uuid' => $order_id,
                    'cart_level_discount' => $cart_level_discount,
                    'payment_method' => $r->payment_method,
                    'razorpay_order_id' => $razor_orderId,
                    'order_delivery_updates' => json_encode($status_updates),
                ];
                $orderid = \DB::table('orders')->insertGetId($order_ar);

                \DB::table('order_items')->whereCartSessionId($cart_session_id)->update(['order_id' => $orderid]);
            }

           // if ($r->payment_method != 'Online') {

                \DB::table('carts')->whereUserId($user_id)->whereCartSessionId($cart_session_id)->delete();
//}
            $applied_coupons = \DB::table('applied_coupons')->where(['cart_session_id' => $cart_session_id, 'user_id' => $user_id])->get();
            $coupon_usage_updates = [];
            if (count($applied_coupons->toArray()) > 0) {
                foreach ($applied_coupons as $h) {
                    $coupon_id = $h->coupon_id;
                    // if (!empty($coupon_usage_updates)) {
                    //     if (!in_array($coupon_id, array_keys($coupon_usage_updates))) {
                    //         $coupon_usage_updates[$coupon_id] = 1;
                    //     } else {
                    //         $coupon_usage_updates[$coupon_id] = $coupon_usage_updates[$coupon_id] + 1;
                    //     }

                    // } else {
                    $coupon_usage_updates[$coupon_id] = 1;
                    // }

                }
                if (!empty($coupon_usage_updates)) {
                    foreach ($coupon_usage_updates as $coupon_id => $count) {
                        if (\DB::table('coupon_usage_by_customers')->whereUserId($user_id)->whereCouponId($coupon_id)->exists()) {
                            \DB::table('coupon_usage_by_customers')->whereUserId($user_id)->whereCouponId($coupon_id)
                                ->increment('count', 1);
                        } else {
                            \DB::table('coupon_usage_by_customers')->insert([
                                'user_id' => $user_id, 'coupon_id' => $coupon_id, 'count' => 1,
                            ]);
                        }
                    }
                }
            }
            \DB::commit();
            return response()->json(['data' => $r->payment_method == 'Online' ? ['orderId' => $razor_orderId, 'user_id' => $user_id, 'table_order_id' => $orderid] : 'Placed order successfully'], 201);

        } catch (\Exception $ex) {
            \DB::rollback();
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Order place Saving',
            ]);
            return response()->json(['message' => $ex->getMessage()], 400);

        }
    }

    public function getCoupons()
    {
        $coupons = \DB::table('coupons')->whereStatus('Active')->get();
        $coupon_ar = [];
        if (count($coupons->toArray()) > 0) {

            foreach ($coupons as $c) {
                $add_coupon = false;
                $today = date("Y-m-d H:i:s");
                $start = $c->start_date;
                $end = $c->end_date;
                if (!empty($start) && (strtotime($start) <= strtotime($today))) {
                    $add_coupon = true;
                    if (!empty($end)) {
                        if (strtotime($end) >= strtotime($today)) {
                            $add_coupon = true;
                        } else {
                            $add_coupon = false;

                        }

                    }

                }if ($add_coupon) {
                    $coupon_ar[] = $c;
                }
            }

        }
        return response()->json(['data' => $coupon_ar], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
    public function destroy($id)
    {
        //
    }
    public function update_order_status()
    {
        $column = $r->column;
        $status = $r->status;
        $id = $r->order_id;
        \DB::table('orders')->whereId($id)->update([$column => $status]);
    }
    public function order_history(Request $r)
    {
        $email = $r->email;
        $phone = $r->phone;
        $status = null;
        $user = \App\Models\User::where([
            'email' => $email,
            'phone' => $phone,

        ])->first();
        $orders = [];
        if (!is_null($user)) {
            $orders = \App\Models\Order::whereUserId($user->id)
                ->when(!empty($status), function ($query) use ($status) {
                    return $query->where('delivery_status', $status);
                })
                ->latest()->get()->toArray();

        }
        return response()->json(['data' => $orders], 200);

    }
    public function order_cancel(Request $r)
    {
        $email = $r->email;
        $phone = $r->phone;
        $id = $r->id;
        $user = \App\Models\User::where([
            'email' => $email,
            'phone' => $phone,

        ])->first();
        $row = \App\Models\Order::whereId($id)->whereUserId($user->id)->first();
        $order_status_from_post = 'Cancelled';
        $delivery_updates = $row->order_delivery_updates != null ? json_decode($row->order_delivery_updates, true) : [];
        if (!empty($delivery_updates)) {
            $insert_ar = [];
            $status_names = array_column($delivery_updates, 'name');

            if (in_array($order_status_from_post, $status_names)) {

                foreach ($delivery_updates as $k => $v) {
                    $delivery_updates[$k]['date'] = date('Y-m-d H:i:s');
                   

                }
            } else {
                array_push($delivery_updates, [
                    'name' => $order_status_from_post,
                    'date' => date('Y-m-d H:i:s'),
                    'message' => '',
                ]);

            }
            \DB::table('orders')->whereId($row->id)->update([
                'status_update_date' => date('Y-m-d H:i:s'),
                'delivery_status' => $order_status_from_post,
                'order_delivery_updates' => json_encode($delivery_updates),
            ]);
        }

        return response()->json(['data' => 'Order Cancelled '], 200);

    }
}
