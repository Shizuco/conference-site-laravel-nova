<?php

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
            'thema' => 'required|max:255|min:2',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required',
            'presentation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'thema.required' => 'The thema is required!',
            'thema.min' => 'Mininmum length of thema is 2, enter more!',
            'thema.max' => 'Maximum length of thema is 255, you have entered too mach!',
            'start_time.required' => 'Time of start of presentation is required!',
            'end_time.required' => 'Time of end of presentation is required!',
            'description.required' => 'Description is required!',
            'presentation.required' => 'Presentation is required',
        ];
    }
}
