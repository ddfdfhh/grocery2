<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'question' => 'required',
    'stream_id' => 'required|numeric',
    'school_class_id' => 'required|numeric',
    'direction_id' => 'numeric|nullable',
    'answer' => 'required',
    'subject' => 'required',
    'language_medium_id' => 'required|numeric',
    'marks' => 'required|numeric',
    'option_a' => 'nullable',
    'option_b' => 'nullable',
    'option_c' => 'nullable|nullable',
    'option_d' => 'nullable',
    'option_e' => 'nullable',
    'question_type' => 'required',
    'or_part_of' => 'nullable'
];
    }
}