<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class BlogActionRequest extends FormRequest
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
            'action_user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'action' => 'required|in:like,dislike',
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');
        return [
            'action_user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'action' => 'required|in:like,dislikep',
        ];
    }

}
