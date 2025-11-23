<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogFileRequest extends FormRequest
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
            'blog_id' => 'required|exists:blogs,id',
            'file' => 'required|string',
            'type' => 'required',
        ];
    }

    protected function update(): array
    {
        return [
            'blog_id' => 'required|exists:blogs,id',
            'file' => 'required|string',
            'type' => 'required',
        ];
    }
}
