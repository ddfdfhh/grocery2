<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaperGeneralSettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'stream_id' => 'numeric|sometimes',
    'school_class_id' => 'required|numeric',
    'subject' => 'required',
    'language_medium_id' => 'required|numeric',
    'section' => 'nullable',
    'total_marks' => 'numeric',
    'paper_duration' => 'required|string',
    'passing_marks' => 'numeric',
    'section__json__title[]\'' => 'nullable',
    'section__json__subtitle[]\'' => 'nullable'
];
    }
}