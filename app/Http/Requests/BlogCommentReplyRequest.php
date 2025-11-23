<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class BlogCommentReplyRequest extends FormRequest
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
            'rate_user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'comment_id' => 'required|exists:comments,id',
            'reply_id' => 'nullable|exists:replies,id',
            'reply' => 'required|string',
            'reply_type' => 'required|in:comment,reply',
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');

        return [
            'rate_user_id' => 'required',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'comment_id' => 'required|exists:comments,id',
            'reply_id' => 'nullable|exists:replies,id',
            'reply' => 'required|string',
            'reply_type' => 'required|in:comment,reply',
        ];
    }
}
