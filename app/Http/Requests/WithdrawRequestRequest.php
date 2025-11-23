<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequestRequest extends FormRequest
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
            'status' => 'required|in:approved,rejected',
        ];
    }

    protected function update(): array
    {
        return [
            'status' => 'required|in:approved,rejected',
            'reject_reason' => 'required_if:status,rejected',
        ];
    }
}
