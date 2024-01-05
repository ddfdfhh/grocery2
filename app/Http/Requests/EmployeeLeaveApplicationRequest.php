<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeLeaveApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'user_id' => 'required|numeric',
    'leave_type' => 'required|numeric',
    'from_date' => 'required',
    'to_date' => 'nullable',
    'details' => 'required',
    'status' => 'sometimes',
    'approved_by_id' => 'numeric|nullable'
];
    }
}