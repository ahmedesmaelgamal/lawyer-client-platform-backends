<?php

namespace App\Http\Requests;

use App\Enums\ReactionEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class BlogReactionRequest extends FormRequest
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
            'user_id'=> 'required',
            'user_type'=> 'required|in:'.implode(',', array_column(UserTypeEnum::cases(), 'value')),
            'blog_id'=> 'required|exists:blogs,id',
            'reaction'=> 'required|in:'.implode(',', array_column(ReactionEnum::cases(), 'value')),
        ];
    }

    protected function update(): array
    {
        return [
            'user_id'=> 'required',
            'user_type'=> 'required|in:'.implode(',', array_column(UserTypeEnum::cases(), 'value')),
            'blog_id'=> 'required|exists:blogs,id',
            'reaction'=> 'required|in:'.implode(',', array_column(ReactionEnum::cases(), 'value')),
        ];
    }
}
