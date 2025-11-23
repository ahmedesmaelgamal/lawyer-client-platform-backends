<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'body' => 'required|string',
            'count_like' => 'nullable|integer|min:0',
            'count_dislike' => 'nullable|integer|min:0',
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');
        return [
            'user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'body' => 'required|string',
            'count_like' => 'nullable|integer|min:0',
            'count_dislike' => 'nullable|integer|min:0',
        ];
    }
}
