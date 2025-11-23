<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
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
            'file' => 'required|string',
            'name' => 'required|string',
            'category_id' => 'required|exists:document_categories,id',
        ];
    }

    protected function update(): array
    {
        return [
            'file' => 'required|string',
            'name' => 'required|string',
            'category_id' => 'required|exists:document_categories,id',
        ];
    }
}
