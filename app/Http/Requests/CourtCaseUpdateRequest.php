<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourtCaseUpdateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'case_id' => 'required|exists:cases,id',
            'details' => 'required|string',
            'date' => 'required|date',
        ];
    }

    protected function update(): array
    {
        return [
            'title' => 'required|string|max:255',
            'case_id' => 'required|exists:cases,id',
            'details' => 'required|string',
            'date' => 'required|date',
        ];
    }
}
