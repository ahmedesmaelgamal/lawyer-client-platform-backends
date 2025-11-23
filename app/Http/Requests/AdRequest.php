<?php

namespace App\Http\Requests;

use App\Enums\AdConfirmationEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
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
            'lawyer_id' => 'required|exists:lawyers,id',
            'package_id' => 'required|exists:offer_packages,id',
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'image' => 'required|image',
            'ad_confirmation'=>'required|in:'.implode(',', AdConfirmationEnum::values()),
        ];
    }

    protected function update(): array
    {
        return [
            'lawyer_id' => 'required|exists:lawyers,id',
            'package_id' => 'required|exists:offer_packages,id',
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'image' => 'required|image',
            'ad_confirmation'=>'required|in:'.implode(',', AdConfirmationEnum::values()),
        ];
    }
}
