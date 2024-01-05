<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'full_name' => 'required',
    'email' => 'required|email',
    'phone_no_1' => 'required|numeric',
    'phone_no_2' => 'numeric|nullable',
    'highest_qualification' => 'required',
    'aadhar_image' => 'sometimes|image'
];
    }
}