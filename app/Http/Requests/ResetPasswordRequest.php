<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email_or_phone' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'confirmed'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email_or_phone.required' => __('messages.email_or_phone_required'),
            'password.required' => __('messages.password_required'),
            'password.confirmed' => __('messages.password_confirmed'),
            'password.min' => __('messages.password_min'),
            'password.regex' => __('messages.password_complexity'),
        ];
    }
}
