<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'session_id' => 'nullable|string',
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'products.required' => 'يجب اختيار المنتجات.',
    //         'products.*.product_id.required' => 'المنتج مطلوب.',
    //         'products.*.product_id.exists' => 'المنتج غير موجود.',
    //         'products.*.quantity.required' => 'الكمية مطلوبة.',
    //         'products.*.quantity.integer' => 'الكمية يجب أن تكون رقمًا صحيحًا.',
    //         'products.*.quantity.min' => 'الكمية يجب أن تكون على الأقل 1.',
    //     ];
    // }
}
