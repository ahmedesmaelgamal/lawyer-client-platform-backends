<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
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
            'from_id' => 'required',
            'to_id' => 'required',
            'sender_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'receiver_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'message' => 'required|string',
            'message_type' => 'required|in:text,file',
            'file' => 'nullable|string',
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');

        return [
            'from_id' => 'required',
            'to_id' => 'required',
            'sender_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'receiver_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'message' => 'required|string',
            'message_type' => 'nullable|in:text,file',
            'file' => 'nullable|string',
        ];
    }
}
