<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'full_name' => 'required',
 
    'email' => 'nullable|email',
    'phone_no' => 'nullable',
    'caste' => 'required',
    'gender' => 'required',
    'aadhar_number' => 'required|numeric',
    'class_id' => 'required|numeric',
    'section_id' => 'numeric|nullable',
    'date_of_birth' => 'required',
    'has_disability' => 'nullable',
    'disability_description' => 'nullable',
    'bus' => 'nullable',
    'hostel' => 'nullable',
    'religion' => 'required',
    'father_aadhar_number' => 'numeric|nullable',
    'mdium' => 'required',
    'mother_aadhar_number' => 'nullable',
    'father_image' => 'image|nullable',
    'mother_full_name' => 'required',
    'father_full_name' => 'required',
    'father_occupation' => 'required',
    'father_job_location' => 'nullable',
    'father_job_sector' => 'nullable',
    'mother_job_location' => 'nullable',
    'mother_occupation' => 'nullable',
    'has_sibling' => 'nullable',
    'siblings_info' => 'nullable',
    'previous_class' => 'nullable',
    'previous_marksheet' => 'nullable',
    'previous_percentage' => 'nullable',
    'tc_image' => 'image|nullable',
    'character_certificate_image' => 'image|nullable',
    'is_old_student' => 'nullable',
    'reason_to_leave' => 'nullable',
    'weight' => 'numeric|nullable',
    'height' => 'numeric|nullable',
    'year_of_leaving' => 'numeric|nullable',
    'siblings_info__json__class[]\'' => 'nullable'
];
    }
}