<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarketProductCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [
            'title.ar' => 'required|max:255',
            'title.en' => 'required|max:255',
        ];
    }

    protected function update(): array
    {
        return [
            'title.ar' => 'required|max:255',
            'title.en' => 'required|max:255',
        ];
    }
    public function messages()
    {
        return [
            'title.ar.required' => trns('The Arabic title is required.'),
            'title.en.required' => trns('The English title is required.'),
        ];
    }
}
