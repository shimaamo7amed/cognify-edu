<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            "first_name"=>"required|string",
            "middle_name"=>"required|string",
            "last_name"=>"required|string",
            "date_of_birth"=>"required|string",
            "gender"=>"required|string",
            "nationality"=>"required|string",
            "phone"=>"required|string",
            'email' => [
                'required',
                'string',
                'email',
                'unique:employees,email,' . $id,  
                'regex:/^[A-Za-z0-9._%+-]+@cognifyedu\.com$/i',
            ],
            "street_address"=>"required|string",
            "highest_degree"=>"required|string",
            "institution"=>"required|string",
            "graduation_year"=>"required|string",
            "other_institution"=>"required|string",
            "other_degree_title"=>"required|string",
            "other_degree_institution"=>"required|string",
            "other_degree_year"=>"required|string",
            "current_position_title"=>"required|string",
            "current_institution"=>"required|string",
            "current_from"=>"required|string",
            "current_to"=>"required|string",
            "current_responsibilities"=>"required|string",
            "previous_position_title"=>"required|string",
            "previous_institution"=>"required|string",
            "previous_from"=>"required|string",
            "previous_to"=>"required|string",
            "previous_responsibilities"=>"required|string",
            "total_teaching_experience"=>"required|string",
            "special_needs_experience"=>"required|string",
            "shadow_teacher_experience"=>"required|string",
            "arabic_proficiency"=>"required|string",
            "english_proficiency"=>"required|string",
            "french_proficiency"=>"required|string",
            "italian_proficiency"=>"required|string",
            "computer_skills"=>"required|string",
            "training_title"=>"required|string",
            "training_date"=>"required|string",
            "professional_memberships"=>"required|string",
            "motivation"=>"required|string",
            "suitability"=>"required|string",
        ];
    }
}
