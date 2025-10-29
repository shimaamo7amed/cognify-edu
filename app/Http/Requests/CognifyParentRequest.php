<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CognifyParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "name" => "required|string|min:3|max:25",
            "email" => "required|email|unique:cognify_parents",
            "phone" => "required|min_digits:11|max_digits:15|unique:cognify_parents,phone",
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
            "address" => "required|string|min:3|max:255",
            "governorate" => "required|string|min:3|max:255",
            "role" => "required|in:parent,public-user",
        ];
    }
}
