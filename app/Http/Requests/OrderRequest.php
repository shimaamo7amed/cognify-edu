<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            "shipping_fee" => "nullable|numeric|min:0",
        ];
        if (!auth()->check()) {
            $rules = array_merge($rules, [
                "guest_first_name" => "required|string|max:255",
                "guest_last_name" => "nullable|string|max:255",
                "guest_email" => "required|email|max:255",
                "guest_phone" => "required|string|max:20",
                "guest_address" => "required|string|max:255",
            ]);
        } else {
            $rules = array_merge($rules, [
                "guest_name" => "nullable|string|max:255",
                "guest_email" => "nullable|email|max:255",
                "guest_phone" => "nullable|string|max:20",
                "guest_address" => "nullable|string|max:255",
            ]);
        }

        return $rules;
    }
}
