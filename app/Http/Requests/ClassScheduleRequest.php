<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'schedules' => 'nullable',
    'schedules__json__from[]\'' => 'nullable',
    'schedules__json__to[]\'' => 'nullable',
    'schedules__json__period_type[]\'' => 'nullable'
];
    }
}