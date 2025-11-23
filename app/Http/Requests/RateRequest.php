<?php

namespace App\Http\Requests;

use App\Enums\RateEnum;
use App\Enums\ReasonEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class RateRequest extends FormRequest
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
            'court_case_id' => 'required:exists:court_cases,id',
            'from_user_id' => 'required',
            'to_user_id' => 'required',
            'from_user_type' => 'required|in:'.implode(',', $allowedUserTypes),
            'to_user_type' => 'required|in:'.implode(',', $allowedUserTypes),
            'rate' => 'required|number|min:1|max:5',
            'reason'=>'nullable|string',
            'comment' => 'required|string|max:255',
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');

        return [
            'court_case_id' => 'required:exists:court_cases,id',
            'from_user_id' => 'required',
            'to_user_id' => 'required',
            'from_user_type' => 'required|in:'.implode(',', $allowedUserTypes),
            'to_user_type' => 'required|in:'.implode(',', $allowedUserTypes),
            'rate' => 'required|number|min:1|max:5',
            'reason'=>'nullable|string',
            'comment' => 'required|string|max:255',
        ];
    }
}
