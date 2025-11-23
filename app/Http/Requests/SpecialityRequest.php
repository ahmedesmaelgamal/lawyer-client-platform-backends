<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class SpecialityRequest extends FormRequest
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
            'title' => 'required',
            'level_id' => 'required|exists:levels,id',
            'status' => 'required|in:'.implode(',', StatusEnum::values()),
        ];
    }

    protected function update(): array
    {
        return [
            'title' => 'required',
            'level_id' => 'required|exists:levels,id',
            'status' => 'required|in:'.implode(',', StatusEnum::values()),
        ];
    }
}
