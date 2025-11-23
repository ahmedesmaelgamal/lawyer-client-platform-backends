<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'country_id' => 'required|exists:countries,id',
        ];

        // Common title validation rules for both store and update
        $titleRules = $this->getTitleValidationRules();

        return array_merge($rules, $titleRules);
    }

    public function messages()
    {
        return [
            'country_id.required' => trns('The country field is required.'),
            'title.ar.required' => trns('The Arabic title is required.'),
            'title.ar.max' => trns('The Arabic title may not be greater than 255 characters.'),
            'title.en.required' => trns('The English title is required.'),
            'title.en.max' => trns('The English title may not be greater than 255 characters.'),
        ];
    }

    protected function getTitleValidationRules(): array
    {
        return [
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
        ];
    }
}
