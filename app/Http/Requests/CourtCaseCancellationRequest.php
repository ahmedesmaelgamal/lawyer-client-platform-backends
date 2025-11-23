<?php

namespace App\Http\Requests;

use App\Enums\CourtCaseStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class CourtCaseCancellationRequest extends FormRequest
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
        $allowedCourtCaseStatuses = array_column(CourtCaseStatusEnum::cases(), 'value');

        return [
            'court_case_id' => 'required|exists:court_cases,id',
            'accept_lawyer' => 'required|boolean',
            'accept_client' => 'required|boolean',
        ];
    }

    protected function update(): array
    {
        $allowedCourtCaseStatuses = array_column(CourtCaseStatusEnum::cases(), 'value');

        return [
            'court_case_id' => 'required|exists:court_cases,id',
            'accept_lawyer' => 'required|boolean',
            'accept_client' => 'required|boolean',
        ];
    }
}
