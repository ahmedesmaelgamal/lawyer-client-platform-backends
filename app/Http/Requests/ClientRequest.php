<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email',
            'password' => 'required|string|min:8',
            'national_id' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'points' => 'nullable|integer|min:0',
            'city_id' => 'required|exists:cities,id',
            'country_id' => 'required|exists:countries,id',
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
            'otp' => 'nullable|string|size:6',
            'otp_expire' => 'nullable|date',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email',
            'password' => 'required|string|min:8',
            'national_id' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'points' => 'nullable|integer|min:0',
            'city_id' => 'required|exists:cities,id',
            'country_id' => 'required|exists:countries,id',
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
            'otp' => 'nullable|string|size:6',
            'otp_expire' => 'nullable|date',
        ];
    }
}
