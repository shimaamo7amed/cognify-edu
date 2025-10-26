<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CognifyParentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|min:3|max:25",
            "email" => "required|email|unique:cognify_parents",
            "phone" => "required|min_digits:11|max_digits:15",
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
