<?php

namespace App\Http\Requests;

use App\Enums\CourtCaseStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class CourtCaseRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'speciality_id' => 'required|exists:specialities,id',
            'client_id' => 'required|exists:clients,id',
            'case_estimated_price' => 'required|string',
            'details' => 'required|string',
            'court_case_level_id'=>'required|exists:court_case_levels,id',
            'status' => 'required|string|in:' . implode(',', $allowedCourtCaseStatuses),
            'case_final_price' => 'nullable|string',
        ];
    }

    protected function update(): array
    {
        $allowedCourtCaseStatuses = array_column(CourtCaseStatusEnum::cases(), 'value');

        return [
            'title' => 'required|string|max:255',
            'speciality_id' => 'required|exists:specialities,id',
            'client_id' => 'required|exists:clients,id',
            'case_estimated_price' => 'required|string',
            'details' => 'required|string',
            'status' => 'required|string|in:' . implode(',', $allowedCourtCaseStatuses),
            'case_final_price' => 'nullable|string',
        ];
    }
}
