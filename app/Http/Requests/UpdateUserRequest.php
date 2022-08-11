<?php

declare (strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class UpdateUserRequest extends FormRequest
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
            'email' => [
                'required',
                Rule::unique('users')->ignore(Auth::user()->id),
            ],
            'password' => 'required|string|min:8',
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
