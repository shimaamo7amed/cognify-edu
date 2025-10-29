<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SMSRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string',
            'email_otp' => 'required|digits_between:4,6',
            'sms_otp' => 'required|digits_between:4,6',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'رقم الهاتف مطلوب.',
            'email_otp.required' => 'رمز التحقق المرسل إلى الإيميل مطلوب.',
            'sms_otp.required' => 'رمز التحقق المرسل إلى رقم الهاتف مطلوب.',
        ];
    }
}
