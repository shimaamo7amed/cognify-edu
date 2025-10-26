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
            'otp' => 'required|string|min:4|max:6',
        ];
    }
}
