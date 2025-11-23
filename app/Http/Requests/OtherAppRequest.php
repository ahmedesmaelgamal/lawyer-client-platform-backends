<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtherAppRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'android_url' => 'required|url|max:255',
            'ios_url' => 'required|url|max:255',
            'icon' => 'required|image',
        ];
    }


    protected function update(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'android_url' => 'nullable|url|max:255',
            'ios_url' => 'nullable|url|max:255',
            'icon' => 'nullable|image',
            'status' => 'nullable|boolean',
        ];
    }
}
