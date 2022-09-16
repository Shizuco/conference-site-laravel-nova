<?php

declare (strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateConferenceRequest extends FormRequest
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
            'title' => 'required|max:255|min:2',
            'date' => 'required',
            'time' => 'required',
            'country' => 'required',
            'latitude' => 'required|min:-90|max:90',
            'longitude' => 'required|min:-180|max:180',
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
            'latitude.min' => 'Минимальное значение широты -90',
            'latitude.max' => 'Максимальное значение широты 90',
            'longitude.min' => 'Минимальное значение долготы -180',
            'longitude.max' => 'Максимальное значение долготы 180',
            'category_id.required' => 'Category is required'
        ];
    }
}
