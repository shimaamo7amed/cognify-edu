<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
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
            "organizationName"=>"required|string",
            "contactPersonName"=>"required|string",
            "email"=>"required|string|email|unique:partners",
            "phoneNumber"=>"required|string",
            "location"=>"required|string",
            'service_id' => 'required|exists:services,id',
            'service_items' => 'required|array',
            'service_items.*' => 'exists:service_items,id'
        ];
    }
}
