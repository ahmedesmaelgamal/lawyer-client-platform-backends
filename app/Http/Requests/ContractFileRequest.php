<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractFileRequest extends FormRequest
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
            'file_path' => 'required|file|max:10000',
            'file_name.ar' => 'required|string',
            'file_name.en' => 'required|string',
            'contract_category_id' => 'required|exists:contract_categories,id',
        ];
    }

    protected function update(): array
    {
        return [
//            'file_path' => 'required|file|max:10000',
            'file_name.ar' => 'required|string',
            'file_name.en' => 'required|string',
//            'contract_category_id' => 'required|exists:contract_categories,id',
        ];
    }
}
