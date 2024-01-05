<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentLeaveApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'student_id' => 'required|numeric',
    'reason' => 'required|string',
    'from_date' => 'required',
    'to_date' => 'nullable',
    'approved_by_id' => 'numeric|nullable',
    'status' => 'sometimes'
];
    }
}