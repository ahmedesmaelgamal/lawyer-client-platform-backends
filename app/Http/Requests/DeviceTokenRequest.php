<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class DeviceTokenRequest extends FormRequest
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
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');

        return [
            'user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'device_type' => 'required|string',
            'token' => 'required|string',
            'mac_address' => 'required|string',
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');

        return [
            'user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'device_type' => 'required|string',
            'token' => 'required|string',
            'mac_address' => 'required|string',
        ];
    }
}
