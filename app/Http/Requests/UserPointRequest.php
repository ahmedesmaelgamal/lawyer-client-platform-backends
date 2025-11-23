<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPointRequest extends FormRequest
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
            'point' => 'required|integer',
        ];
    }

    protected function update(): array
    {
        return [
            'user_id' => 'required',
            'point' => 'required|integer',
        ];
    }
}
