<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamTimetableRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'school_class_id' => 'required|numeric',
    'title' => 'required',
    'timetable' => 'nullable',
    'status' => 'sometimes',
    'timetable__json__subject[]\'' => 'nullable',
    'timetable__json__date[]\'' => 'nullable'
];
    }
}