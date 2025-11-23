<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreeConsultationRequestRequest extends FormRequest
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
            'status' => 'required|in:pending,new,replied',
            'client_id' => 'required|exists:clients,id',
            'body' => 'required|string',
        ];
    }

    protected function update(): array
    {
        return [
            'status' => 'required|in:pending,new,replied',
            'client_id' => 'required|exists:clients,id',
            'body' => 'required|string',
        ];
    }
}
