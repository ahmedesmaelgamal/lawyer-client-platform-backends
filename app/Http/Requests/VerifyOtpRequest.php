<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'otp' => 'required|max:4',
            'email' => 'required|email|exists:admins,email',
        ];
    }



    public function messages(): array
    {
        return [
            'otp.required' => trns('The otp field is required.'),
            'otp.max'    => trns('The otp must not exceed 4 characters.'),
        ];
    }
}
