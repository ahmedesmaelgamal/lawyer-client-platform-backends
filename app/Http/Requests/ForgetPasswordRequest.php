<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:admins,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => trns('The email field is required.'),
            'email.email'    => trns('You must enter a valid email address.'),
            'email.exists'   => trns('This email is not registered with us.'),
        ];
    }
}
