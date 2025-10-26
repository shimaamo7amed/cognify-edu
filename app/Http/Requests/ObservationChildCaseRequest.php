<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObservationChildCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'child_id'    => 'required|exists:cognify_children,id',
            'session_id'  => 'required|exists:observation_sessions,id',
            'date'   => 'required|date|after_or_equal:today',
            'time'   => 'required|string',
        ];
    }
}
