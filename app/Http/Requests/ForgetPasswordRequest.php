<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            "otp" => rand(100000, 999999),
        ]);
    }
    public function rules(): array
    {
        return [
            'email' => 'required|exists:cognify_parents,email',
            "otp" => "required|numeric",

        ];
    }
}
