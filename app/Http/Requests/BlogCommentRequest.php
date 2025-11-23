<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class BlogCommentRequest extends FormRequest
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
            'blog_id' => 'required|exists:blogs,id',
            'user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'comment' => 'nullable|string',
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');

        return [
            'blog_id' => 'required|exists:blogs,id',
            'comment_user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'comment' => 'nullable|string',
        ];
    }
}
