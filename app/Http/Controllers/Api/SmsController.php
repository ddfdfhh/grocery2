<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use \Carbon\Carbon;
class SmsController extends Controller
{


    public function smsBalance(){
        http://sms.bulkssms.com/getbalance.jsp?user=OMGHUB&key=e3ed681724XX&accusage=1
        $senderId = 'OMGHUB';
            $response = Http::get('http://sms.bulkssms.com/getbalance.jsp?user=OMGHUB&key=e3ed681724XX&accusage=1');
            dd($response->body());
     
    }
}