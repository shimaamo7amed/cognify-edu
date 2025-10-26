<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
{
    public function authorize(): bool
    {
      return true;
    }
    
    public function rules(): array
    {
        return [
        "name"=>"required|string|min:3|max:25",
        "email"=>"required|email",
        "phoneNumber"=>"required|string|min:11|max:15",
        "message"=>"required",
        "date"=>"nullable|string",
        ];
    }
}
