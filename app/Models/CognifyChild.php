<?php

namespace App\Models;

use App\Models\Report;
use App\Models\Employee;
use App\Models\CognifyParent;
use App\Models\ObservationReport;
use App\Models\ObservationChildCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CognifyChild extends Model
{
    public $timestamps = true;
    protected $table = "cognify_children";
    protected $fillable = [
        'childPhoto',
        'fullName',
        'age',
        'gender',
        'schoolName',
        'schoolAddress',
        'homeAddress',
        'textDescription',
        'voiceRecording',
        'foodAllergies',
        'environmentalAllergies',
        'severityLevels',
        'medicationAllergies',
        'medicalConditions',
        'parent_id',
        'priority',
    ];
    protected $casts = [
        'foodAllergies' => 'array',
        'environmentalAllergies' => 'array',
        'severityLevels' => 'array',
        'medicationAllergies' => 'array',
        'medicalConditions' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(CognifyParent::class, 'parent_id');
    }
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'cognify_children_id');
    }
    public function observationReports()
    {
        return $this->hasOne(ObservationReport::class, 'child_id');
    }

    public function employees()
    {
        return $this->belongsToMany(
            Employee::class, 
            'employee_children', 
            'child_id', 
            'employee_id'
        )->withPivot('assigned_at', 'some_flag'); 
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class, 'child_id');
    }

    public function observationCase()
    {
        return $this->hasOne(ObservationChildCase::class, 'child_id');
    }
    public function sentReports()
    {
        return $this->hasMany(Report::class, 'cognify_children_id')
            ->whereHas('child.observationReports', function ($query) {
                $query->where('delivery_status', 'sent');
            });
    }
     
   






}
