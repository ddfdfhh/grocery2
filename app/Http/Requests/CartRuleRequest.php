<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRuleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'title' => 'required',
   
    'discount' => 'numeric',
    'from_value' => 'numeric|nullable',
    'to_value' => 'numeric|nullable',
    'start_date' => 'nullable',
    'end_date' => 'nullable'
];
    }
}