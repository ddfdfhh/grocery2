<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api as RazorApi;
use \Carbon\Carbon;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrderId($amount, $user_id)
    {
       $setting = \DB::table('settings')->first();
        $api = new RazorApi($setting->razor_pay_api_key, $setting->razor_pay_secret_key);

        $orderData = [
            'receipt' => 'rcpt' . time(),
            'amount' => $setting->razor_pay_live=='Yes'?$amount*100:100, // 39900 rupees in paise
            'currency' => 'INR',
            'notes' => ["user_id" => $user_id],
            'partial_payment' => false,
        ];

        $razorpayOrder = $api->order->create($orderData);

        return $razorpayOrder['id'];

    }
    public function storePayment(Request $r)
    {
        $setting = \DB::table('settings')->first();
        $api = new RazorApi($setting->razor_pay_api_key, $setting->razor_pay_secret_key);
      
        $payment_id = $r->has('payment_id') ? $r->payment_id : null;
        $razororderId = $r->has('razor_order_id') ? $r->razor_order_id : null;
        $orderId = $r->has('order_id') ? $r->order_id : null;
        $signature = $r->has('signature') ? $r->signature : null;
        $wallet = $r->has('wallet_name') ? $r->wallet_name : null;
        if (!empty($payment_id) && !empty($orderId) && !empty($signature)) {
            $order_row = \DB::table('orders')->where('id', $orderId)->first();
            try {
                $attributes = array(
                    'razorpay_order_id' => $razororderId,
                    'razorpay_payment_id' => $payment_id,
                    'razorpay_signature' => $signature,
                );
                $api->utility->verifyPaymentSignature($attributes);

                \DB::table('payments')->insert([
                    'amount' => $order_row->net_payable,
                    'user_id' => $order_row->user_id,
                    'razorpay_order_id' => $razororderId,
                    'payment_id' => $payment_id,
                    'order_id' => $orderId, 'status' => 'Success',
                ]);
                \DB::table('orders')->whereId($order_row->id)->whereUserId($order_row->user_id)->update(['paid_status' => 'Paid']);
                return response()->json(['data' => 'Payment succesfully stored '], 201);
            } catch (SignatureVerificationError $e) {
                $response = 'failure';
                $error = 'Razorpay Error : ' . $e->getMessage();
                \DB::table('payments')->insert([
                    'amount' => $order_row->net_payable,
                    'user_id' => $order_row->user_id,
                    'razorpay_order_id' => $razororderId,
                    'payment_id' => $payment_id,
                    'order_id' => $orderId, 'status' => 'Signature Mismatch',
                ]);
                \DB::table('orders')->whereId($order_row->id)->whereUserId($order_row->user_id)->update(['paid_status' => 'Failed']);
                return response()->json(['data' => 'Payment can not be verified '], 403);
            }
        } elseif (!empty($wallet)) {
            $order_row = \DB::table('orders')->whereId($orderId)->first();
            \DB::table('payments')->insert([
                'amount' => $order_row->net_payable,
                'user_id' => $order_row->user_id,
                'order_id' => $orderId,
                'wallet_name' => $wallet,
            ]);
            \DB::table('orders')->whereId($orderId)->whereUserId($order_row->user_id)->update(['paid_status' => 'Paid']);
            return response()->json(['data' => 'Payment completed '], 201);
        } else {
            \DB::table('system_errors')->insert([
                'error' =>'Wallet payment Failed',
                'created_at' => Carbon::now(),
                'which_function' => 'Payment Contrller store payment function wallet pay',
            ]);
            return response()->json(['data' => 'Payment failed'], 400);
        }

    }

}
