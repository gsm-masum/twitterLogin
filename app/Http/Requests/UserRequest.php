<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_name' => 'required|string|max:20',
            'full_name' => 'sometimes|string|max:100',
            'email' => 'required|email|unique:users',
            'phone' => 'sometimes | string|unique:users',
            'password' => 'required|min:6',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,svg|max:2048',
        ];
    }
}
