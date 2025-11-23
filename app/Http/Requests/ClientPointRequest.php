<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientPointRequest extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'points' => 'required|integer',
        ];
    }

    protected function update(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'points' => 'required|integer',
        ];
    }
}
