<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionDirectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'details' => 'required',
    'school_class_id' => 'required|numeric',
    'stream_id' => 'required|numeric',
    'language_medium_id' => 'required|numeric'
];
    }
}