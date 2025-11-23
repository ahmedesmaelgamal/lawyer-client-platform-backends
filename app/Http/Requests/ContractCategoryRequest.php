<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractCategoryRequest extends FormRequest
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
            'title'=>'required|unique:contract_categories'
        ];
    }

    protected function update(): array
    {
        return [
            'title'=>'required|unique:contract_categories'

        ];
    }
}
