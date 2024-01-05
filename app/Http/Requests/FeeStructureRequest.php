<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeeStructureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'structure__json__getList(\'FeeHead\')[]\'' => 'nullable',
    'structure' => 'nullable'
];
    }
}