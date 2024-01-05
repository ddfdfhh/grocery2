<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload1(Request $request)
    {
       $file_name1 = '';
        $file_name2 = '';
        $file_name3 = '';
        $file_name4 = '';
        $qr_image = '';
        $request->validate([
            'image1' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'image2' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'image3' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'image4' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'qr_image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);
        \DB::beginTransaction();
        try{

        chmod(Storage::path('public/returns'),0755);
        if ($request->hasFile('image1')) {
            $filenameWithExt = $request->file('image1')->getClientOriginalName();

            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image1')->getClientOriginalExtension();

            $file_name1 = $filename . '_' . time() . '.' . $extension;

            $path = $request->file('image1')->storeAs('public/returns', $file_name1);
        }
        if ($request->hasFile('image2')) {
            $filenameWithExt = $request->file('image2')->getClientOriginalName();

            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image2')->getClientOriginalExtension();

            $file_name2 = $filename . '_' . time() . '.' . $extension;

            $path = $request->file('image2')->storeAs('public/returns', $file_name2);
        }
        if ($request->hasFile('image3')) {
            $filenameWithExt = $request->file('image3')->getClientOriginalName();

            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image3')->getClientOriginalExtension();

            $file_name3 = $filename . '_' . time() . '.' . $extension;

            $path = $request->file('image3')->storeAs('public/returns', $file_name3);
        }
        if ($request->hasFile('image4')) {
            $filenameWithExt = $request->file('image4')->getClientOriginalName();

            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image4')->getClientOriginalExtension();

            $file_name4 = $filename . '_' . time() . '.' . $extension;

            $path = $request->file('image4')->storeAs('public/returns', $file_name4);
        }
        if ($request->hasFile('qr_image')) {
            $filenameWithExt = $request->file('qr_image')->getClientOriginalName();

            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('qr_image')->getClientOriginalExtension();

            $qr_image = $filename . '_' . time() . '.' . $extension;

            $path = $request->file('qr_image')->storeAs('public/returns', $file_name4);
        }
        $reason = $request->reason;
        $details = $request->details;
        $order_id = $request->order_id;
        $item_id = $request->item_id;
        $item_row = \DB::table('order_items')->whereId($item_id)->first();
        $order_row = \DB::table('orders')->whereId($item_row->order_id)->first();
        $all_item_qty = \DB::table('order_items')->whereOrderId($item_row->order_id)->sum('qty');
        \DB::table('return_items')->upsert([
            'product_id' => $item_row->product_id,
            'variant_id' => $item_row->variant_id,
            'product_name' => $item_row->name,
            'variant_name' => $item_row->variant_name,
            'unit' => $item_row->unit,
            'details' => $details,
            'qty' => $request->qty, 'reason' => $reason,
            'per_unit_price' => $item_row->sale_price,
            'original_image' => $item_row->image,
            'order_item_id' => $item_id, 'details' => $details,
            'user_id' => $item_row->user_id,
            'first_image' => $file_name1, 'second_image' => $file_name2, 'third_image' => $file_name3,
             'fourth_image' => $file_name4,
            'type' => $request->type,
        ], 'order_item_id');
        if ($request->type == 'Return') {
            dlog('okok return','plplpl');
            $refud_row = \DB::table('refund')->whereUserId($item_row->user_id)->whereOrderId($order_id)->first();
            if (!is_null($refud_row)) {
                \DB::table('refund')->whereUserId($item_row->user_id)->whereOrderId($order_id)
                    ->increment('amount', ($item_row->net_cart_amount / $item_row->qty) * $request->qty)
                    ->update([
                        'where_to_refund' => $request->where_to_refund,
                        'bank_name' => $request->bank_name,
                        'account_number' => $request->account_number,
                        'account_holder' => $request->account_holder,
                        'ifsc' => $request->ifsc, 'upi' => $request->upi,
                        'qr_image'=>empty($qr_image)?$refud_row->qr_image:$qr_image
                    ]);
            } else {
                \DB::table('refund')->insert([
                    'user_id' => $item_row->user_id,
                    'order_id' => $order_id,
                    'amount' => ($item_row->net_cart_amount / $item_row->qty) * $request->qty,
                    'where_to_refund' => $request->where_to_refund,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'account_holder' => $request->account_holder,
                    'ifsc' => $request->ifsc, 'upi' => $request->upi,
                    'qr_image'=>$qr_image
                ]);

            }
        }
        if ($request->type == 'Return') {
            \DB::table('order_items')->whereId($item_id)->update(['returned_qty' => $request->qty]);
        } else {
            \DB::table('order_items')->whereId($item_id)->update(['exchanged_qty' => $request->qty]);
        }

        $status_to_update = $request->type . ' Requested';
        \DB::table('orders')->whereId($item_row->order_id)->update([
            'delivery_status' => $status_to_update, 'status_update_date' => date('Y-m-d H:i:s'),

        ]);

        $delivery_updates = $order_row->order_delivery_updates != null ? json_decode($order_row->order_delivery_updates, true) : [];
        if (!empty($delivery_updates)) {
            $insert_ar = [];
            $status_names = array_column($delivery_updates, 'name');

            if (in_array($status_to_update, $status_names)) {

                foreach ($delivery_updates as $k => $v) {
                    $delivery_updates[$k]['date'] = date('Y-m-d H:i:s');
                    $delivery_updates[$k]['message'] = '';

                }
            } else {
                array_push($delivery_updates, [
                    'name' => $status_to_update,
                    'date' => date('Y-m-d H:i:s'),
                    'message' => '',
                ]);

            }
            \DB::table('orders')->whereId($order_row->id)->update(['order_delivery_updates' => json_encode($delivery_updates)]);
        } else {
            $updates_ar = [
                ['name' => $status_to_update,
                    'date' => date('Y-m-d H:i:s'),
                    'message' => ''],
            ];
            \DB::table('orders')->whereId($order_row->id)->update(['order_delivery_updates' =>
                json_encode($updates_ar)]);
        }

        \DB::commit();
        return response()->json(['message' => 'Return request submitted successfully'], 200);
    }
    catch(\Exception $ex){
        \DB::rolback();
        \DB::table('system_errors')->insert([
            'error'=>$ex->getMessage().'=== at line'.$ex->getLine(),
            'which_function'=>'Submit Return Request'
        ]);
        return response()->json(['message' => 'Failed to submit the return request '.$ex->getMessage()], 400);
    }
       

    }

}
