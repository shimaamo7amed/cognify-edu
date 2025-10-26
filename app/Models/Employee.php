<?php

namespace App\Models;

use App\Models\CognifyChild;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasRoles;

    protected $guard_name = 'employee';

    public $timestamps = true;
    protected $table = "employees";

    protected $fillable = [
        'name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality',
        'phone',
        'email',
        'password',
        'street_address',
        'highest_degree',
        'institution',
        'graduation_year',
        'other_institution',
        'other_degree_title',
        'other_degree_institution',
        'other_degree_year',
        'current_position_title',
        'current_institution',
        'current_from',
        'current_to',
        'current_responsibilities',
        'previous_position_title',
        'previous_institution',
        'previous_from',
        'previous_to',
        'previous_responsibilities',
        'total_teaching_experience',
        'special_needs_experience',
        'shadow_teacher_experience',
        'arabic_proficiency',
        'english_proficiency',
        'french_proficiency',
        'italian_proficiency',
        'computer_skills',
        'training_title',
        'training_date',
        'professional_memberships',
        'motivation',
        'suitability',
        'is_approved',
    ];

    protected $attributes = [
    'is_approved' => 'pending'
    ];
    // public function getAuthIdentifierName()
    // {
    //     return 'email';
    // }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }
    public function assignedChildren()
    {
        return $this->belongsToMany(CognifyChild::class, 'employee_children', 'employee_id', 'child_id');
    }
}