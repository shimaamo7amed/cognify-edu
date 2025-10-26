<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\CognifyChild;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    public $timestamps = true;
    protected $table = 'daily_reports';
    protected $fillable = [
        'child_id',
        'employee_id',
        'report_date',
        'attention_activity',
        'attention_level',
        'positive_behavior',
        'communication',
        'social_interaction',
        'meltdown',
        'general_behavior',
        'reinforcers',
        'academic',
        'independence',
        'overall_rating',
        'comments',
        'report_type',
        'employee_name',
        'status',
    ];
    public function child()
    {
        return $this->belongsTo(CognifyChild::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
