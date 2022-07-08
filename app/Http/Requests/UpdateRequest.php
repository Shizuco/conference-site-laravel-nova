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
            'address_lat' => 'required|min:-180|max:180',
            'address_lon' => 'required|min:-180|max:180',
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
            'address_lat.min' => 'Минимальное значение широты -180',
            'address_lat.max' => 'Максимальное значение широты 180',
            'address_lon.min' => 'Минимальное значение долготы -180',
            'address_lon.max' => 'Максимальное значение долготы 180',
        ];
    }
}
