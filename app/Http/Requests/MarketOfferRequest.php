<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class MarketOfferRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
            'market_product_id' => 'required|exists:market_products,id',
            'from' => 'required|date|after_or_equal:today',
            'to' => 'required|date|after_or_equal:from',
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
        ];
    }

    protected function update(): array
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'market_product_id' => 'required|exists:market_products,id',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
        ];
    }

    public function messages()
    {
        return [
            'image.required' => trns('image is required'),
            'image.image' => trns('The image must be a file of type: jpeg, png, jpg, gif, svg.'),
            'market_product_id.required' => trns('market product is required'),
            'from.required' => trns('from date is required'),
            'from.date' => trns('from date must be a valid date'),
            'to.required' => trns('to date is required'),
            'to.date' => trns('to date must be a valid date'),
            'to.after_or_equal' => trns('to date must be after or equal from date'),
            'status.required' => trns('status is required'),
        ];
    }
}
