<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractualPayscaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'payment_amount' => 'required|numeric',
    'payment_terms' => 'required',
    'work_period' => 'required|numeric',
    'duration_type' => 'required',
];
    }
}