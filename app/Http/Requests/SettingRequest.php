<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'company_name' => 'required',
    'delivery_charge' => 'numeric',
    'delivery_instructions' => 'nullable',
    'delivery_slots' => 'nullable',
    'return_instructions' => 'nullable',
    'logo' => 'image|nullable',
    'website_url' => 'nullable',
    'delivery_slots__json__name[]\'' => 'nullable'
];
    }
}