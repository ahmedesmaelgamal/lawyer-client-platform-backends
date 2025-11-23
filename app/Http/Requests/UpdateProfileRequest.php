<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [
            'name' => 'nullable',
            'old_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, auth()->user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
//            'user_name' => 'required|unique:admins,user_name',
            'password' => 'required|min:6|confirmed',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'nullable',
            'old_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, auth()->user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
//            'user_name' => 'required|unique:admins,user_name',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'password.required_without' => 'يجب ادخال كلمة مرور',
            'password.min' => 'الحد الادني لكلمة المرور : 6 أحرف',
        ];
    }
}
