<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderBannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
    'image' => 'image'
];
    }
}