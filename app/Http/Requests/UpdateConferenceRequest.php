<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConferenceRequest extends FormRequest
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
            'title.min' => 'Минимальное длинна названия 2 символа, введите еще!',
            'title.max' => 'Максимальная длинна названия 255 символа, Вы ввели слишком много!',
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
