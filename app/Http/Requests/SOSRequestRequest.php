<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SOSRequestRequest extends FormRequest
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
            'user_id' => 'required',
            'problem' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'lat' => 'required|string',
            'long' => 'required|string',
            'status' => 'required|in:now,accepted,completed',
            'lawyer_id' => 'nullable|exists:lawyers,id',
            'client_id'=> 'required|exists:clients,id'
        ];
    }

    protected function update(): array
    {
        return [
            'problem' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'lat' => 'required|string',
            'long' => 'required|string',
            'status' => 'required|in:now,accepted,completed',
            'user_id' => 'required',
            'lawyer_id' => 'nullable|exists:lawyers,id',
            'client_id'=> 'required|exists:clients,id'

        ];
    }
}
