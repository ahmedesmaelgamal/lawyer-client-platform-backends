<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'market_product_id' => 'required|exists:products,id',
            'lawyers_id' => 'required|exists:lawyers,id',
            'qty' => 'required|integer|min:1',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
        ];
    }

    protected function update(): array
    {
        return [
            'market_product_id' => 'required|exists:products,id',
            'lawyers_id' => 'required|exists:lawyers,id',
            'qty' => 'required|integer|min:1',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
        ];
    }
}
