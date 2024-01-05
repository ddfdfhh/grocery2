<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'name' => 'required',
    'category_id' => 'numeric|nullable',
    'image' => 'nullable|image|mimes:jpg,png,jpeg|max:648',
    'banner_image' => 'nullable|image|mimes:jpg,png,jpeg,webp,gif|max:1024',
];
    }
}