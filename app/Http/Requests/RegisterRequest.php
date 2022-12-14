<?php

declare (strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'role' => 'required|string',
            'password' => 'required|string|min:8',
            'surname' => 'required|string',
            'birthday' => 'required|string',
            'country' => 'required|string',
            'phone' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Account with this email is already exists'
        ];
    }
}
