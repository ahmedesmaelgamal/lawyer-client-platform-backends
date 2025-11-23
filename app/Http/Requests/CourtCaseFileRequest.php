<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourtCaseFileRequest extends FormRequest
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
            'case_id' => 'required|exists:cases,id',
            'type' => 'required',
        ];
    }

    protected function update(): array
    {
        return [
            'case_id' => 'required|exists:cases,id',
            'type' => 'required',
        ];
    }
}
