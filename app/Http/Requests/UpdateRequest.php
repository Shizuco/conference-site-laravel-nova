<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|min:2|max:255',
            'date' => 'required',
            'time' => 'required',
            'country' => 'required',
            'address_lat' => 'required',
            'address_lon' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название обезательно!',
            'title.min' => 'Название от 2 до 255!',
            'title.max' => 'Название от 2 до 255!',
            'date.required' => 'Дата обезательна!',
            'time.required' => 'Время обезательно!',
            'country.required' => 'Страна обезательна!',
            'address_lat.required' => 'Широта обезательна!',
            'address_lon.required' => 'Долгота обезательна!',
        ];
    }
}
