<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class WalletTransactionRequest extends FormRequest
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
            'user_id' => 'required|integer',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'credit' => 'required|numeric|min:0',
            'debit' => 'required|numeric|min:0',
            'court_case_id' => 'required|integer|exists:court_cases,id',
        ];
    }

    protected function update(): array
    {
        $allowedUserTypes = array_column(UserTypeEnum::cases(), 'value');
        return [
            'user_id' => 'required|integer',
            'user_type' => 'required|in:' . implode(',', $allowedUserTypes),
            'credit' => 'required|numeric|min:0',
            'debit' => 'required|numeric|min:0',
            'case_id' => 'required|integer|exists:cases,id',
        ];
    }
}
