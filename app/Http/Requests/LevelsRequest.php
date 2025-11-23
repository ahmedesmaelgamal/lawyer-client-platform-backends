<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LevelsRequest extends FormRequest
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
            'title' => 'required|max:255',
            'salary' => 'required|numeric|min:0',
        ];
    }

    protected function update(): array
    {
        return [
            'title' => 'required|max:255',
            'salary' => 'required|numeric|min:0',
        ];
    }
}
