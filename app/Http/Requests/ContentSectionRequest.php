<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentSectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'section_number' => 'required',
    'section_title' => 'nullable',
    'header_image' => 'nullable|image|mimes:jpg,png,jpeg,webp,gif|max:900',
    'section_background_image' => 'nullable|image|mimes:jpg,png,jpeg,webp,gif|max:900',
    
];
    }
}