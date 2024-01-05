<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductAddonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'addons__json__name[]\'' => 'nullable',
    'addons' => 'nullable',
    'addons__json__price[]\'' => 'nullable'
];
    }
}