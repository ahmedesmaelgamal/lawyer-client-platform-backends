<?php

namespace App\Http\Requests;

use App\Enums\ExpireEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class LawyerPackageRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'number_of_bumps' => 'required|integer',
            'status'=>'required|in:'.implode(StatusEnum::values()),
            'is_expired' => 'required|in:ACTIVE,EXPIRED'.implode(ExpireEnum::values()),
        ];
    }

    protected function update(): array
    {
        return [
            'lawyer_id' => 'required|exists:lawyers,id',
            'package_id' => 'required|exists:offer_packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'number_of_bumps' => 'required|integer',
            'status'=>'required|in:'.implode(StatusEnum::values()),
            'is_expired' => 'required|in:ACTIVE,EXPIRED'.implode(ExpireEnum::values()),
        ];
    }
}
