<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class MarketProductRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable',
            'location' => 'required|max:500',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'market_product_category_id' => 'required|exists:market_product_categories,id',
            'status' => 'required|in:'.implode(',', StatusEnum::values()),
        ];
    }

    protected function update(): array
    {
        return [
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable',
            'location' => 'required|max:500',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'market_product_category_id' => 'required|exists:market_product_categories,id',
            'status' => 'required|in:'.implode(',', StatusEnum::values()),
        ];
    }
}
