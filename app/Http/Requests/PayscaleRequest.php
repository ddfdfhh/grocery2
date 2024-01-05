<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayscaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'basic_salary' => 'required|numeric'
];
    }
}