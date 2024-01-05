<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {

        $data['title'] = 'Login';
        return view('auth.login', with($data));
    }

    /**
     * Handle account login request
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $cr = ['email' => $request->email, 'password' => $request->password];

        if (Auth::attempt($cr)) {
            $logo_row = \DB::table('settings')->first();
            if ($logo_row) {
                $logo = $logo_row->logo_image;
                if(!empty($logo))
                session(['logo' => $logo]);
            }

            if (auth()->user()->hasRole(['Admin'])) {

                return createResponse(true, 'Logged In Successfully', route('admin.dashboard'));
            } else {
                if (auth()->user()->status == 'Active') {
                    $store_id = null;

                    if (auth()->user()->hasRole(['Store Incharge'])) {
                        $store_row = \DB::table('stores')->whereOwnerId(auth()->id())->first();

                        if (!is_null($store_row)) {
                            $store_id = $store_row->id;
                            session(['store_name' => $store_row->name, 'store_id' => $store_row->id]);

                        }

                    }
                    return createResponse(true, 'Admin Logged In Successfully', route('user.dashboard'));
                } else {
                    return createResponse(false, 'Account is deactive');
                }

            }

        } else {
            // dd(auth()->id());
            return createResponse(false, 'Login credentials are invalid');

        }

    }

    /**
     * Handle response after user authenticated
     *
     * @param Request $request
     * @param Auth $user
     *
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended();
    }
    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
