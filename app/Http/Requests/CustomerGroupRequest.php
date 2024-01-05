<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerGroupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'purchase_amount_rule__json__minimum_amount[]\'' => 'nullable',
    'purchase_amount_rule' => 'nullable',
    'purchase_amount_rule__json__maximum_amount[]\'' => 'nullable',
    'purchase_amount_rule__json__within_days[]\'' => 'nullable',
    'order_count_rules__json__minimum[]\'' => 'nullable',
    'order_count_rules' => 'nullable',
    'order_count_rules__json__maximum[]\'' => 'nullable',
    'subscription_rule__json__is_subscribed[]\'' => 'nullable',
    'subscription_rule' => 'nullable'
];
    }
}