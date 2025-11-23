<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required',
            'New_Password' => 'required',
        ];
    }



    public function messages(): array
    {
        return [
            'New_Password.required' => trns('The otp field is required.'),
        ];
    }
}
