<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
            'title' => 'required|string',
            'body' => 'required|string',
            'user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');

        return [
            'title' => 'required|string',
            'body' => 'required|string',
            'user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
        ];
    }
}
