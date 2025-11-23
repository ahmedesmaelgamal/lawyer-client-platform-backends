<?php

namespace App\Http\Requests;

use App\Enums\LawyerStatusEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class LawyerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255',
            'email' => 'required|email|unique:lawyers,email',
            'status' => 'required|in:' . implode(',', $allowedLawyerStatuses),
            'image' => 'nullable|image|max:1024',
            'national_id' => 'required|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'government_id' => 'nullable|exists:governments,id',
            'phone' => 'required|string|max:15',
            'lawyer_id' => 'required|string|max:255',
            'type' => 'required|in:'.implode(',', LawyerStatusEnum::values()),
            'level_id' => 'required|exists:levels,id',
        ];
    }

    protected function update(): array
    {
        $allowedLawyerStatuses = array_column(LawyerStatusEnum::cases(), 'value');

        return [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255',
            'email' => 'required|email|unique:lawyers,email',
            'status' => 'required|in:' . implode(',', $allowedLawyerStatuses),
            'image' => 'nullable|string|max:255',
            'national_id' => 'required|string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'government_id' => 'nullable|exists:governments,id',
            'phone' => 'required|string|max:15',
            'lawyer_id' => 'required|string|max:255',
            'type' => 'required|in:'.implode(',', LawyerStatusEnum::values()),
            'level_id' => 'required|exists:levels,id',
        ];
    }
}
