<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductDiscountRuleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'product_id' => 'required',
    'quantity_rule' => 'nullable',
    'quantity_rule__json__Min Quantity[]\'' => 'nullable',
    'quantity_rule__json__Max Quantity[]\'' => 'nullable',
    'quantity_rule__json__discount_type[]\'' => 'nullable',
    'quantity_rule__json__discount[]\'' => 'nullable',
    'quantity_rule__json__is_range[]\'' => 'nullable'
];
    }
}