<?php

namespace App\Http\Requests;

use App\Enums\CourtCaseStatusEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class CourtCaseEventRequest extends FormRequest
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
        $allowedCourtCaseStatuses = StatusEnum::values();

        return [
            'lawyer_id' => 'required|exists:lawyers,id',
            'status' => 'required|in:' . implode(',', $allowedCourtCaseStatuses),
            'price' => 'required|numeric|min:0',
            'court_case_id' => 'required|exists:court_cases,id',
            'seen' => 'required|boolean',
            'rate' => 'nullable|integer|min:1|max:10',
        ];
    }

    protected function update(): array
    {
        $allowedCourtCaseStatuses = StatusEnum::values();

        return [
            'lawyer_id' => 'required|exists:lawyers,id',
            'status' => 'required|in:' . implode(',', $allowedCourtCaseStatuses),
            'price' => 'required|numeric|min:0',
            'court_case_id' => 'required|exists:court_cases,id',
            'seen' => 'required|boolean',
            'rate' => 'nullable|integer|min:1|max:10',
        ];
    }
}
