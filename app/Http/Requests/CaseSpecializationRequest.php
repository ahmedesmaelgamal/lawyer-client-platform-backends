<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaseSpecializationRequest extends FormRequest
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
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
        ];
    }

    protected function update(): array
    {
        return [
            'title.ar' => 'nullable|string|max:255',
            'title.en' => 'nullable|string|max:255',
        ];
    }
}
