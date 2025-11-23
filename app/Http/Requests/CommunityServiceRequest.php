<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunityServiceRequest extends FormRequest
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
            'community_sub_category_id'=> 'required|exists:community_sub_categories,id',
            'body' => 'required',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function update(): array
    {
        return [
            'community_sub_category_id'=> 'required|exists:community_sub_categories,id',
            'body' => 'required',
            'status' => 'required|in:active,inactive',
        ];
    }
}
