<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'user_name' => 'sometimes|string|max:20',
            'full_name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:users',
            'phone' => 'sometimes | string|unique:users',
            'password' => 'sometimes|min:6',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,svg|max:2048',
        ];
    }
}
