<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'password' =>'required|string|confirmed',
            'role' => 'required|string',
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
