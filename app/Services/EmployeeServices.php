<?php
namespace App\Services;
use App\Models\Partner;
use App\Models\Employee;
use Filament\Facades\Filament;
use App\Mail\EmployeeRequestMail;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class EmployeeServices
{

    static public function employeeRegister(array $array)
    {
        $employeeData = [
            'name' => $array['first_name'],
            'middle_name' => $array['middle_name'],
            'last_name' => $array['last_name'],
            'date_of_birth' => $array['date_of_birth'],
            'gender' => $array['gender'],
            'nationality' => $array['nationality'],
            'phone' => $array['phone'],
            'email' => $array['email'],
            'street_address' => $array['street_address'],
            'highest_degree' => $array['highest_degree'],
            'institution' => $array['institution'],
            'graduation_year' => $array['graduation_year'],
            'other_institution' => $array['other_institution'],
            'other_degree_title' => $array['other_degree_title'],
            'other_degree_institution' => $array['other_degree_institution'],
            'other_degree_year' => $array['other_degree_year'],
            'current_position_title' => $array['current_position_title'],
            'current_institution' => $array['current_institution'],
            'current_from' => $array['current_from'],
            'current_to' => $array['current_to'],
            'current_responsibilities' => $array['current_responsibilities'],
            'previous_position_title' => $array['previous_position_title'],
            'previous_institution' => $array['previous_institution'],
            'previous_from' => $array['previous_from'],
            'previous_to' => $array['previous_to'],
            'previous_responsibilities' => $array['previous_responsibilities'],
            'total_teaching_experience' => $array['total_teaching_experience'],
            'special_needs_experience' => $array['special_needs_experience'],
            'shadow_teacher_experience' => $array['shadow_teacher_experience'],
            'arabic_proficiency' => $array['arabic_proficiency'],
            'english_proficiency' => $array['english_proficiency'],
            'french_proficiency' => $array['french_proficiency'],
            'italian_proficiency' => $array['italian_proficiency'],
            'computer_skills' => $array['computer_skills'],
            'training_title' => $array['training_title'],
            'training_date' => $array['training_date'],
            'professional_memberships' => $array['professional_memberships'],
            'motivation' => $array['motivation'],
            'suitability' => $array['suitability'],
        ];

        $partner = Employee::create($employeeData);

        Mail::to('shimaa0mohamed19@gmail.com')->send(new EmployeeRequestMail($partner));
    }






}