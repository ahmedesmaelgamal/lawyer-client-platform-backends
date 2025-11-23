<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LawyerAboutRequest extends FormRequest
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
            'lawyer_id' => 'required|exists:lawyers,id',
            'about' => 'required|string',
            'constancy_fee' => 'required|numeric|min:0',
            'attorney_fee' => 'required|numeric|min:0',
            'office_address' => 'required|string',
        ];
    }

    protected function update(): array
    {
        return [
            'lawyer_id' => 'required|exists:lawyers,id',
            'about' => 'required|string',
            'constancy_fee' => 'required|numeric|min:0',
            'attorney_fee' => 'required|numeric|min:0',
            'office_address' => 'required|string',
        ];
    }
}
