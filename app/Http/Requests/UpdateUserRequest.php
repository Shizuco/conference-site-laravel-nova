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
            'name' => 'string',
            'email' => [
                Rule::unique('users')->ignore(Auth::user()->id),
            ],
            'password' => 'string|min:8|nullable',
            'surname' => 'string',
            'birthday' => 'string',
            'country' => 'string',
            'phone' => 'string'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Account with this email is already exists'
        ];
    }
}
