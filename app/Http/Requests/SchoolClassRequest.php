<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolClassRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'stream_id' => 'required',
            'teacher_id' => 'nullable|numeric',
            'strength' => 'required|numeric',
        ];
    }
}
