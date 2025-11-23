<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductDiscountRequest extends FormRequest
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
            'discount' => 'nullable|numeric|min:0',
            'product_id' => 'required|exists:products,id',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ];
    }

    protected function update(): array
    {
        return [
            'discount' => 'nullable|numeric|min:0',
            'product_id' => 'required|exists:products,id',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ];
    }
}
