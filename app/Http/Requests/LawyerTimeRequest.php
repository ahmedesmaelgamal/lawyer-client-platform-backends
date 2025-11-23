<?php

namespace App\Http\Requests;

use App\Enums\WeekDaysEnum;
use Illuminate\Foundation\Http\FormRequest;

class LawyerTimeRequest extends FormRequest
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
        $allowedWeekDays = array_column(WeekDaysEnum::cases(), 'value');

        return [
            'day' => 'required|in:'.implode(',', $allowedWeekDays),
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after_or_equal:from',
            'status' => 'required',
        ];
    }

    protected function update(): array
    {
        $allowedWeekDays = array_column(WeekDaysEnum::cases(), 'value');

        return [
            'day' => 'required|in:'.implode(',', $allowedWeekDays),
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after_or_equal:from',
            'status' => 'required',
        ];
    }
}
