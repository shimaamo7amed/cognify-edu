<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CognifyChildRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'childPhoto'=>"nullable|image|mimes:png,jpg,GIF,webp|max:2048",
            'fullName' => 'required|string|min:3|max:25',
            'age' => 'required|integer|min:1|max:18',
            'schoolName' =>'required|string|max:25',
            'schoolAddress' =>'nullable|string',
            'homeAddress' =>'string',
            'gender' => 'required|string',
            'textDescription' => 'nullable|string|max:10000',
            'voiceRecording' => 'nullable|mimes:mp3,wav,m4a,webm|max:10240',
            'foodAllergies' => 'nullable|array',
            'foodAllergies.*' => 'string',
            'environmentalAllergies' => 'nullable|array',
            'environmentalAllergies.*' => 'string',
            'severityLevels' => 'nullable|array',
            'severityLevels.*' => 'string',
            'medicationAllergies' => 'nullable|array',
            'medicationAllergies.*' => 'string',
            'medicalConditions' => 'nullable|array',
            'medicalConditions.*' => 'string',
            



        ];
    }
}
