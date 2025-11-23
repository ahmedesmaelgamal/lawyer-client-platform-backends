<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LawyerSpecialityRequest extends FormRequest
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
            'special_id' => 'required|exists:specials,id',
        ];
    }

    protected function update(): array
    {
        return [
            'lawyer_id' => 'required|exists:lawyers,id',
            'special_id' => 'required|exists:specials,id',
        ];
    }
}
