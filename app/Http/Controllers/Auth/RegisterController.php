<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Display register page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $r)
    {

        $data['title'] = 'Registration';
        return view('auth.register', with($data));
    }

    /**
     * Handle account registration request
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\Response
     */

    public function register(RegisterRequest $request)
    {

        $post = $request->validated();

        $newData = ['name' => $post['name'],

            'email' => $post['email'],
            'password' => $post['password'],
        ];

        \DB::beginTransaction();

        try {
            $user = User::create($newData);
            /*
            $newuserid = $user->id;

            \DB::table('password_resets')->where(['email' => $user->email])->delete();

            $token = \Str::random(64);
            \DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now(),
            ]);
             */
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return createResponse(false, $e->getMessage());

        }

        // try {
        //     $token = \Crypt::encrypt($token);
        //     $str = view('emails.registration_email', ['user' => $user, 'token' => $token])->render();
        //     $resp = $this->mail($request->email, 'Registration Email', $str);
        //     dd($resp);
        // } catch (\Exception $ex) {
        //     dd('mail error', $ex->getMessage());
        // }

        return createResponse(true, "Account created successfully,Check your email to activate your account", route('login'));

    }

    public function verify_email($_vX00, $_tX00)
    {
        $email = $_vX00;
        $token = $_tX00;
        if (!$token && !$email) {
            abort(404);
        }
        $token = \Crypt::decrypt($token);
        $update = \DB::table('password_resets')->where(['email' => $email, 'token' => $token])->first();

        if (!$update) {
            //\Session::flash('error', 'Changes Saved.' );
            return redirect()->route('login')->with('error', 'Account verification failed');
        }

        $user = User::where('email', $email)->update(['email_verified_at' => \Carbon\Carbon::now()]);

        // Delete password_resets record
        \DB::table('password_resets')->where(['email' => $email])->delete();

        return redirect(route('login'))->withSuccess('Email verified successfully');
    }

}
