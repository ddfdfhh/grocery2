<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'title' => 'required',
    'start_date' => 'required',
    'end_date' => 'required',
    'status' => 'sometimes'
];
    }
}