<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefuseReasonRequest extends FormRequest
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
            'name.ar' => 'required',
            'name.en' => 'required',
            'type' => 'required'
        ];
    }

    protected function update(): array
    {
        return [
            'name.ar' => 'required',
            'name.en' => 'required',
            'type' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'name.ar.required' => trns('name in arabic'),
            'name.en.required' => trns('name in english'),
            'type.required' => trns('type is required'),
        ];
    }
}
