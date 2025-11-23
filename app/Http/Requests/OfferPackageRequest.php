<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class OfferPackageRequest extends FormRequest
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
            'number_of_days' => 'required|integer|min:1',
            'number_of_ads' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|integer|min:0',
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
        ];
    }

    protected function update(): array
    {
        return [
            'title' => 'required|max:255',
            'number_of_days' => 'required|integer|min:1',
            'number_of_ads' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|integer|min:0',
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
        ];
    }
}
