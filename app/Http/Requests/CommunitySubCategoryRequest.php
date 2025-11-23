<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunitySubCategoryRequest extends FormRequest
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
            'community_category_id' => 'required|exists:community_categories,id',
            'title' => 'required|max:255',
            'status' => 'required|in:active,inactive',

        ];
    }

    protected function update(): array
    {
        return [
            'community_category_id' => 'required|exists:community_categories,id',
            'title' => 'required|max:255',
            'status' => 'required|in:active,inactive',
        ];
    }
}
