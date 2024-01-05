<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->user,
            'phone' => 'required|numeric|unique:users,phone,' . $this->user,
            'password' => 'sometimes',
            'state_id' => 'nullable|numeric',
            'city_id' => 'nullable|numeric',
            'pincode' => 'required|numeric',
            'address' => 'required',
            'image' => 'image|nullable',
            'status' => 'nullable', 'role' => 'required',
        ];
    }
}
