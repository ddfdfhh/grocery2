<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Hash;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */

    // use SendsPasswordResetEmails;

    public function ForgetPassword()
    {
        $data['title']='Forget Password';
        return view('auth.forget_password',with($data));
    }

    public function ForgetPasswordStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:tbl_users',
        ]);

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

       
         $str=view('emails.forget_password_mail',['token' => $token])->render();
         $resp=$this->mail($request->email,'Forget Password ',$str);
        return createResponse(true, 'We have emailed your password reset link!');
    }

    public function ResetPassword($token)
    {
        
        return view('auth.reset-password', ['token' => $token,'title'=>'Reset Password']);
    }

    public function ResetPasswordStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:tbl_users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $update = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->first();

        if (!$update) {
            return createResponse(false, 'Invalid token!');
        }

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password),'zasper_api_id'=>$request->password]);

        // Delete password_resets record
        \DB::table('password_resets')->where(['email' => $request->email])->delete();

        return createResponse(true, 'Your password has been successfully changed!', route('login'));
    }
}
