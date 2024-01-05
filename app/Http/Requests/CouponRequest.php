<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'coupon_code' => 'nullable',
            'customer_group_id' => 'nullable',
            'customer_usage_limit' => 'nullable|numeric',
            'details' => 'nullable',
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable',
            'end_date' => 'required',
            'free_shipping' => 'nullable',
            'minimum_order_amount' => 'nullable|numeric',
            'name' => 'required|string',
            'start_date' => 'required',
            'status' => 'string',
            'total_usage_limit' => 'nullable|numeric',
            'discount_method' => 'required',
            'type' => 'required',  'include_or_exclude' => 'required',

        ];
    }
}
