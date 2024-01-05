<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       
        $setting = \DB::table('settings')->first();
        $delivery_slots=json_decode($setting->delivery_slots,true);
        $delivery_slots=array_column($delivery_slots,'name');
        $setting->delivery_slots=$delivery_slots;
        return response()->json(['data' => $setting], 200);

    }
   

}
