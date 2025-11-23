<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class OfficeRequestRequest extends FormRequest
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
        $allowedLawyerStatuses = array_column(StatusEnum::cases(), 'value');

        return [
            'office_id'=>'required|exists:lawyers,office_id',
            'lawyer_id'=>'required,exists:lawyers,id',
            'status' => 'required|in:' . implode(',', $allowedLawyerStatuses),
        ];
    }

    protected function update(): array
    {
        $allowedLawyerStatuses = array_column(StatusEnum::cases(), 'value');

        return [
            'office_id'=>'required|exists:lawyers,office_id',
            'lawyer_id'=>'required,exists:lawyers,id',
            'status' => 'required|in:' . implode(',', $allowedLawyerStatuses),
        ];
    }
}
