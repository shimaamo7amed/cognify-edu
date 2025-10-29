<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email_or_phone' => 'required|string',
            'otp' => 'required|numeric|digits:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email_or_phone.required' => __('messages.email_or_phone_required'),
            'otp.required' => __('messages.otp_required'),
            'otp.numeric' => __('messages.otp_must_be_number'),
            'otp.digits' => __('messages.otp_must_be_6_digits'),
        ];
    }
}
