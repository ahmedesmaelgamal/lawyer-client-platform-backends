<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreeConsultationRepliesRequest extends FormRequest
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
            'free_consultation_request_id' => 'required|exists:free_consultation_requests,id',
            'body' => 'required|string',
            'lawyer_id' => 'required|exists:lawyers,id',
        ];
    }

    protected function update(): array
    {
        return [
            'free_consultation_request_id' => 'required|exists:free_consultation_requests,id',
            'body' => 'required|string',
            'lawyer_id' => 'required|exists:lawyers,id',
        ];
    }
}
