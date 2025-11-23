<?php

namespace App\Http\Requests;

use App\Enums\CurrencyEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'currency' => 'required|in:' . implode(',', $this->getAllowedCurrencies()),
            'status' => 'required|in:' . implode(',', $this->getAllowedStatuses()),
        ];

        return array_merge($rules, $this->getTitleValidationRules());
    }

    public function messages()
    {
        return [
            'currency.required' => trns('The currency field is required.'),
            'currency.in' => trns('Invalid currency selected.'),
            'status.required' => trns('The status field is required.'),
            'status.in' => trns('Invalid status selected.'),
            'title.ar.required' => trns('The Arabic title is required.'),
            'title.ar.max' => trns('The Arabic title may not be greater than 255 characters.'),
            'title.en.required' => trns('The English title is required.'),
            'title.en.max' => trns('The English title may not be greater than 255 characters.'),
        ];
    }


    protected function getAllowedCurrencies(): array
    {
        return method_exists(CurrencyEnum::class, 'values')
            ? CurrencyEnum::values()
            : array_column(CurrencyEnum::cases(), 'value');
    }

    protected function getAllowedStatuses(): array
    {
        return method_exists(StatusEnum::class, 'values')
            ? StatusEnum::values()
            : array_column(StatusEnum::cases(), 'value');
    }

    protected function getTitleValidationRules(): array
    {
        return [
            'title.ar' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
        ];
    }
}
