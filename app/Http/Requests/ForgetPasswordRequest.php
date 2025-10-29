<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email_or_phone' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email_or_phone.required' => __('messages.email_or_phone_required'),
        ];
    }
}
