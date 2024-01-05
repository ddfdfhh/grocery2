<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use \Carbon\Carbon;
class AuthController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $r)
    {

        try {
            $r->validate([

                'phone' => 'required|numeric|digits:10',
                'role'=>'nullable|string'

            ]);
            $email = $r->has('email') ? $r->email : null;
            $phone = $r->has('phone') ? $r->phone : null;
            $role = $r->has('role') ? $r->role : null;
            dlog('loggin',$r->all());
            $user = \App\Models\User::when(!is_null($email), function ($q) use ($email) {
                return $q->whereEmail($email);
            })->when(!is_null($phone), function ($q) use ($phone) {
                return $q->wherePhone($phone);
            })->first();
            // ->when(!is_null($role), function ($q) use ($role) {
            //     return $q->hasRole($role);
            // })->first();
            if (!is_null($user)) {
                if($phone!='9839837312'){/****For demo account for googple play*****/
                        $otp = rand(100000, 999999);
                        if (\DB::table('user_otps')->where(['phone' => $phone])->exists()) {
                            \DB::table('user_otps')->where(['phone' => $phone])->delete();
                        }
                        \DB::table('user_otps')->insert(['phone' => $phone, 'otp' => $otp, 'created_at' => date("Y-m-d H:i:s")]);
        
                        try {
                            $setting = \DB::table('settings')->first();
                            if ($setting->sms_live != 'Yes') {
                                $resp = $this->mail($user->email, 'OTP Verification', "Your Opt is -" . $otp);
                            } else {
                                 sendSms($phone, $otp,'No');
                            }
        
                        } catch (\Exception $ex) {
        
                        }
                }
                return response()->json([
                    'message' => 'User valid',

                ], 200);
            } else {
                return response()->json([
                    'message' => 'Phone number is not registered,Please login using registered phone number  ',

                ], 400);
            }
        } catch (\Exception $ex) {
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'User Login function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }

    }
    public function resend_otp(Request $r)
    {
        try {
            $phone = $r->phone;
            if (!empty($phone)) {
                $otp = rand(100000, 999999);
                if (\DB::table('user_otps')->where(['phone' => $phone])->exists()) {
                    \DB::table('user_otps')->where(['phone' => $phone])->delete();
                }
                \DB::table('user_otps')->insert(['phone' => $phone, 'otp' => $otp, 'created_at' => date("Y-m-d H:i:s")]);
                try {
                    $setting = \DB::table('settings')->first();
                    if ($setting->sms_live != 'Yes') {
                        $resp = $this->mail($user->email, 'OTP Verification', "Your Opt is -" . $otp);
                    } else {
                         dlog('sendin','okok');
                        sendSms($phone, $otp,'No');
                    }

                } catch (\Exception $ex) {
                dlog('smss exp',$ex->getMessage());
                }
                return response()->json(['data' => 'Otp send successfully'], 200);

            }
        } catch (\Exception $ex) {
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Resend Otp function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }

    }
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',

            'phone' => 'required|numeric|unique:users|digits:10',

        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => mt_rand(1000000, 9999999),
                'device_token' => $request->device_token,

            ]);
            $email = $request->email;
            $phone = $request->phone;
            $user->assignRole('Customer');
            $token = Auth::guard('api')->login($user);
            $user->token = $token;
            $user->save();

            return response()->json(['data' => [
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'token' => $token,

            ]], 200);
        } catch (\Exception $ex) {
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'User Registration function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }
    }
    public function updateProfile(Request $request)
    {

        $user = User::where([
            'email' => $request->old_email,
            'phone' => $request->old_phone,

        ])->first();
        $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // //'password' => 'nullable|min:6',
            // 'phone' => 'required|numeric|digits:10|unique:users,phone,' . $user->id,
            'old_email' => 'required|string|email',
            'old_phone' => 'required',
            'address' => 'nullable',
        ]);

        try {
            $user->name = $request->name;
            // $user->email = $request->email;
            // $user->phone = $request->phone;
            $user->address = $request->address;
            $user->save();
            return response()->json(['data' => [
                'user' => $user,
                // 'user' => $user,
                // 'token' => $token,

            ]], 200);
        } catch (\Exception $ex) {
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Update Profile function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }
    }
    public function updateDeviceToken(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            //'password' => 'nullable|min:6',
            'phone' => 'required|numeric|digits:10|unique:users,phone,' . $user->id,
            'device_token' => 'required|string|',

        ]);
        $user = User::where([
            'email' => $request->email,
            'phone' => $request->phone,

        ])->first();

        $user->name = $request->name;
        $user->device_token = $request->device_token;

        $user->save();
        return response()->json(['message' => 'Done'], 200);
    }
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
        ]);
    }
    public function update_address(Request $r)
    {
        // Log::info("shipping u {id}",['id'=>json_encode($r->all())]);
        $address = $r->address;
        $email = $r->email;
        $phone = $r->phone;
        try {
            \App\Models\User::where(['email' => $email, 'phone' => $phone])->update([
                'address' => $address, 'pincode' => $r->pin,'lat'=>$r->lat,'lang'=>$r->lang

            ]);
            return response()->json(['message' => 'Updated Succefully'], 200);
        } catch (\Exception $ex) {
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Update Address function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }
    }

    public function verify_otp(Request $r)
    {
        $otp = $r->otp;
        $email = $r->has('email') && !empty($r->email) ? $r->email : null;
        $phone = $r->has('phone') && !empty($r->phone) ? $r->phone : null;
        try {
            if (\DB::table('user_otps')->when(!is_null($phone), function ($q) use ($phone) {
                return $q->wherePhone($phone);
            })->whereOtp($otp)->exists()) {
                \DB::table('users')->when(!is_null($phone), function ($q) use ($phone) {
                    return $q->wherePhone($phone);
                })->update(['phone_verified' => 'Yes']);
                $user = \App\Models\User::when(!is_null($email), function ($q) use ($email) {
                    return $q->whereEmail($email);
                })->when(!is_null($phone), function ($q) use ($phone) {
                    return $q->wherePhone($phone);
                })->first();
                $token = Auth::guard('api')->login($user);
                $user->token = $token;
                $user->device_token = $r->device_token;
                $user->save();
                Log::info("token  {id}", ['id' => $token]);
                return response()->json(['message' => 'Otp verified successfully',
                    'user' => ['email' => $user->email, 'phone' => $user->phone, 'address' => $user->address, 'name' => $user->name], 'token' => $token], 200);
            } else {
                return response()->json(['message' => 'Incorrect Otp entered'], 404);
            }
        } catch (\Exception $ex) {
            \DB::table('system_errors')->insert([
                'error' => $ex->getMessage() . '==Line==' . $ex->getLine() . '==File==' . $ex->getFile(),
                'created_at' => Carbon::now(),
                'which_function' => 'Otp verify function',
            ]);
            return response()->json([
                'message' => 'Some Error occurred,Please try later ',

            ], 400);
        }

    }
    public function identify_cg(Request $r)
    {

        $r->validate([
            'email' => 'sometimes|string|email',
            'phone' => 'sometimes|numeric|digits:between:10,12',


        ]);
        $email = $r->has('email') ? $r->email : null;
        $phone = $r->has('phone') ? $r->phone : null;
        $user = \App\Models\User::when(!is_null($email), function ($q) use ($email) {
            return $q->whereEmail($email);
        })->when(!is_null($phone), function ($q) use ($phone) {
            return $q->wherePhone($phone);
        })->first();
        if (!is_null($user)) {
            dd(getCustomerGroups($user->id));
            return response()->json(['data' => [
                'message' => 'User valid',

            ]], 200);
        } else {
            return response()->json(['data' => [
                'message' => 'User not found',

            ]], 404);
        }

    }
}
