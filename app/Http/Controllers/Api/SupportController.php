<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listCustomerQueries(Request $request)
    {

        $email = $request->email;
        $phone = $request->phone;

        $user = \DB::table('users')->whereEmail($email)->wherePhone($phone)->first();
        $list = \DB::table('support')->where(
            'user_id', $user->id,

        )->get();
        Log::info('list {id}',['id'=>json_encode($list)]);
        return response()->json(['data' => $list], 200);

    }
    public function createQuery(Request $request)
    {
        $request->validate( ['message' => 'required|max:455']);
        $message = $request->message;
        $email = $request->email;
        $phone = $request->phone;
       
        $user = \DB::table('users')->whereEmail($email)->wherePhone($phone)->first();
        \DB::table('support')->insert([
            'user_id' => $user->id,
            'message' => $message,
        ]);
        return response()->json(['data' => 'Queries recieved successfully'], 200);

    }
    public function customerReply(Request $request)
    {

        $support_row_id = $request->support_id;
        $message = $request->message;
        $user = \DB::table('users')->whereEmail($email)->wherePhone($phone)->first();
        \DB::table('support')->whereId($support_row_id)->update([
            'customer_reponse' => json_encode(['time' => date("Y-m-d H:i:s"), 'message' => $message]),

        ]);
        return response()->json(['data' => 'Reply added successfully'], 200);

    }

}
