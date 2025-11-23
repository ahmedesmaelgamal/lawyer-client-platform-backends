<?php

namespace App\Http\Requests;


use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class CourtCaseDueRequest extends FormRequest
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
            'title' => 'required|boolean',
            'from_user_id' => 'required',
            'to_user_id' => 'required',
            'from_user_type' => 'required|in:'. implode(',', $allowedUserTypes),
            'to_user_type' => 'required|in:'. implode(',', $allowedUserTypes),
            'cort_case_id' => 'required|exists:court_cases,id',
            'court_case_event_id' => 'required|exists:court_case_events,id',
            'date' => 'required|date',
            'paid'=>'required|boolean',
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');

        return [
            'title' => 'required|boolean',
            'from_user_id' => 'required',
            'to_user_id' => 'required',
            'from_user_type' => 'required|in:'. implode(',', $allowedUserTypes),
            'to_user_type' => 'required|in:'. implode(',', $allowedUserTypes),
            'cort_case_id' => 'required|exists:court_cases,id',
            'court_case_event_id' => 'required|exists:court_case_events,id',
            'date' => 'required|date',
            'paid'=>'required|boolean',
        ];
    }
}
