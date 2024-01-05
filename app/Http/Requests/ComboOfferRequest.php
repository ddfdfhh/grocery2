<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComboOfferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'title' => 'required',
    'buy_products' => 'nullable',
    'get_products' => 'nullable',
    'buy_products__json__product_id[]\'' => 'nullable',
    'buy_products__json__qty[]\'' => 'nullable',
    'get_products__json__product_id[]\'' => 'nullable',
    'get_products__json__qty[]\'' => 'nullable',
    'get_products__json__discount_type[]\'' => 'nullable',
    'get_products__json__discount[]\'' => 'nullable'
];
    }
}