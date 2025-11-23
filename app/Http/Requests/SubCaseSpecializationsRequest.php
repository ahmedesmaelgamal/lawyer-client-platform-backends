<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCaseSpecializationsRequest extends FormRequest
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
            'Case_Specializations_id' => 'required|exists:case_specializations,id',
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
        ];
    }

    protected function update(): array
    {
        return [
            'Case_Specializations_id' => 'nullable|exists:case_specializations,id',
            'name.ar' => 'nullable|string|max:255',
            'name.en' => 'nullable|string|max:255',
        ];
    }
}
