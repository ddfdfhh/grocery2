<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \Carbon\Carbon;
class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $coupons = \DB::table('coupons')->select('id','name','details','discount_method','coupon_code','show_in_front')->whereStatus('Active')
        ->whereDate('start_date', '<=', Carbon::now())
        ->whereDate('end_date', '>=', Carbon::now())
        ->get();
       return response()->json(['data' => $coupons], 200);

    }
   

}
