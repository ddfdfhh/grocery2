<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FrontendController extends Controller
{
    public function index()
    {

        return view('welcome');
    }
    public function clear_cache()
    {

        \Artisan::call('optimize:clear');
        return 'cleared';
    }
    public function cache()
    {

        // \Artisan::call('storage:link');

        \Artisan::call('optimize');
        return 'cached';
    }
    /*****Redirect user id logged in on accessing login route  */
    public function redirect()
    {

        if (auth()->user()->hasRole('Admin')) {
            return redirect(route('admin.dashboard'));
        } else {
            return redirect(route('user.dashboard'));
        }

    }

    public function sendMail(Request $r)
    {
        $data = $r->all();
        //dd($data);
        $menus = $data['menus'];
        $addons = $data['addons'];
        unset($data['addons']);
        unset($data['menus']);
        $q = \App\Models\Menu::whereIn('id', $menus)->get();
        foreach ($q as $r) {
            $data['menus'][] = $r->title;
        }
        $q = \App\Models\Addon::whereIn('id', $addons)->get();
        $addon_price = 0;
        foreach ($q as $r) {
            $data['addons'][] = ['name' => $r->title, 'price' => $r->price];
            $addon_price += floatVal($r->price);
        }
        $email = $data['email'];
        $name = $data['name'];
        $data['event_model'] = \App\Models\Event::findOrFail($data['event_id']);
        $data['terms'] = $data['event_model']->terms;
        $data['package_model'] = \App\Models\PackageType::findOrFail($data['package_types']);
        $data['total_amount'] = $this->cal($data['members'], $data['package_model']->minimum, $data['package_model']->price) + $addon_price;
        $data['setting'] = \App\Models\Setting::first();
        $pdf = \PDF::loadView('invoice', ['detail' => $data]);

        \Mail::send('invoice', ['detail' => $data], function ($m) use ($email, $name, $pdf) {
            $m->from('info@primapluse.com', 'BusinessPluse');
            $m->to(trim($email), $name)->subject('Event Quotation ')
                ->attachData($pdf->output(), "event_quotation.pdf");;
        });
        return createResponse(true, 'Quotation mail send to your mail id');

    }
    public function getHtml(Request $r)
    {
        $cat = $r->category;
        $labels = \App\Models\Label::with('label_values')->whereCategoryId($cat)->get();
        $t = view('partial', compact('labels'))->render();
        return $t;
    }
    public function getOrderDetail(Request $r)
    {
        $data['label_values'] = array_values(json_decode($r->val_ar, true));
        $data['user_info'] = json_decode($r->user, true);
        $data['pincode'] = $r->pincode;
        $data['chosen_date'] = $r->chosen_date;
        $amount = 0;
        foreach ($data['label_values'] as $t) {
            $amount += floatVal($t['price']);
        }
        $data['amount'] = $amount;
        $t = view('order_detail', $data)->render();
        return $t;
    }
    public function submitForm(Request $r)
    {
        $data['label_values'] = array_values(json_decode($r->val_ar, true));
        $amount = 0;
        foreach ($data['label_values'] as $t) {
            $amount += floatVal($t['price']);
        }
        $data['user_info'] = json_decode($r->user, true);
        $data['pincode'] = $r->pincode;
        $data['chosen_date'] = $r->chosen_date;
        $info = $data['user_info'];
        $password = Hash::make('12345678');
        $user = \App\Models\User::create(['name' => $info['name'], 'password' => $password, 'email' => $info['email'], 'phone' => $info['email'],
            'address' => $info['address']]);
        Auth::login($user);
        $r->session()->regenerate();
        \App\Models\OrderDetail::create(['user_id' => auth()->id(), 'label_details' => json_encode($data['label_values']), 'amount' => $amount,
            'pincode' => $data['pincode'], 'chosen_date' => $data['chosen_date']]);

    }
    public function orders()
    {
        $list = \DB::table('order_details')->whereUserId(auth()->id())->get();
        // dd($list->toArray());
        return view('orders', compact('list'));
    }
    public function deleteRequest(Request $r)
    {
        if(count($r->all())>0){
        $post=$r->validate([
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:10',
        ]);

       
        \DB::table('info_delete_requests')->upsert([
            'email' => $post['email'],
            'phone' => $post['phone'],
        ], 'phone');
        \Session::flash('success','Account and related data Delete requests placed successfully,We will soon delete all you data ');
       } 
       return view('data_delete_form');

    }
}
