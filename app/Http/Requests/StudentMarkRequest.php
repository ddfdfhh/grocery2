<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentMarkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'exam_type_id' => 'required|numeric',
    'school_class_id' => 'required|numeric',
    'student_id' => 'required|numeric',
    'marks_details__json__subject[]\'' => 'nullable',
    'marks_details' => 'nullable',
    'marks_details__json__total[]\'' => 'nullable',
    'marks_details__json__obtained[]\'' => 'nullable'
];
    }
}