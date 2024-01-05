<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        $user_id=\Auth::guard('api')->user()->id;
        $pending_count= \App\Models\Order->whereDriverId($user_id)->where('delivery_status','Order Placed')->count();
        $delivered_count= \App\Models\Order->whereDriverId($user_id)->where('delivery_status','Delivered')->count();
        $return_count= \App\Models\Order->whereDriverId($user_id)->where('delivery_status','LIKE','%Exchange%')->count();
        return response()->json(['data'=>['pending_count'=>$pending_count,
        'delivered_count'=>$delivered_count,'return_count'=>$return_count]],200);
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
public function show_return_items($id)
    {
        
        $order = \App\Models\Order::with(['user' => function ($q) {
            $q->select('id', 'name', 'phone', 'lat', 'lang', 'address', 'pincode');
        }, 'driver' => function ($q) {
            $q->select('id', 'name', 'phone');
        }, 'return_items'])->whereId($id)->first();

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

        $phone = $r->phone;
        $status = $r->has('status') ? $r->status : 'Order Placed';
        $user = \App\Models\User::where([
            'phone' => $phone,

        ])->role('Driver')->first();
        // $page=$r->page;
        $orders = [];
        if (!is_null($user)) {
            $orders = \App\Models\Order::select('orders.id', 'orders.uuid', 'orders.net_payable', 'orders.created_at', 'orders.paid_status',
                'orders.delivery_status','orders.otp', 'orders.payment_method', 'orders.slot_date', 'orders.slot_time', 'orders.no_of_items', 'orders.driver_id', 'orders.user_id')
                ->with('user:id,name,address,pincode,phone,lat,lang')->whereDriverId($user->id)
                ->when(!empty($status), function ($query) use ($status) {
                    if (str_contains($status, 'Exchange')) {
                        return $query->where('delivery_status', 'LIKE', '%Exchange%');
                    } else {
                        return $query->whereDeliveryStatus($status);
                    }

                })->latest()->paginate(3);

        }

        return response()->json($orders, 200);

    }
    public function order_cancel(Request $r)
    {

        $phone = $r->phone;
        $id = $r->id;
        $user = \Auth::guard('api')->user();
        $row = \App\Models\Order::whereId($id)->whereDriverId($user->id)->first();
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
                'delivery_status' => $order_status_from_post,'cancelled_by'=>'Driver',
                'order_delivery_updates' => json_encode($delivery_updates),
            ]);
        }

        return response()->json(['data' => 'order Canceled '], 200);

    }
    public function confirm_order_delivery(Request $r)
    {

      //  $phone = $r->phone;
        $id = $r->order_id;

        $user = \Auth::guard('api')->user();
        dlog('driver user', $user->name);
        $row = \App\Models\Order::whereId($id)->whereDriverId($user->id)->first();
        $order_status_from_post = 'Delivered';
        if (!is_null($row)) {
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

            return response()->json(['data' => 'order Canceled '], 200);
        }
        else
        return response()->json(['message' => 'Some Error occurred '], 400);

    }
}
