<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunityCategoryRequest extends FormRequest
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
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function update(): array
    {
        return [
            'title.ar' => 'required|max:255',
            'title.en' => 'required|max:255',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'title.ar.required' => trns('title in english'),
            'title.en.required' => trns('title in english'),
        ];
    }
}
