<?php

declare (strict_types = 1);

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
            'latitude' => 'required|min:-90|max:90',
            'longitude' => 'required|min:-180|max:180',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required!',
            'title.min' => 'Minimun length of title is 2!',
            'title.max' => 'Maximum length of title is 255!',
            'date.required' => 'Date required!',
            'time.required' => 'Time required!',
            'country.required' => 'Country required!',
            'latitude.required' => 'Latitud required!',
            'longitude.required' => 'Longitude requred!',
            'latitude.min' => 'Минимальное значение широты -90',
            'latitude.max' => 'Максимальное значение широты 90',
            'longitude.min' => 'Минимальное значение долготы -180',
            'longitude.max' => 'Максимальное значение долготы 180',
        ];
    }
}
