<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObservationReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'childs_strengths',
        'areas_of_concern',
        'behavioral_observations',
        'academic_performance',
        'social_interactions',
        'cognitive_assessment',
        'emotional_assessment',
        'physical_assessment',
        'communication_skills',
        'classroom_environment',
        'teacher_interaction',
        'peer_interaction',
        'professional_recommendations',
        'short_term_goals',
        'long_term_goals',
        'intervention_strategies',
        'classroom_accommodations',
        'next_steps',
        'recommended_follow_up_date',
        'parent_meeting_required',
        'status',
        'progress',
        'child_id',
        'observation_child_case_id',
        'sent_by',
        'sent_at',
        'delivery_status'
    ];

    protected $casts = [
        'recommended_follow_up_date' => 'datetime',
        'progress' => 'integer',
    ];

    public function child()
    {
        return $this->belongsTo(\App\Models\CognifyChild::class, 'child_id');
    }

    public function observationCase()
    {
        return $this->belongsTo(\App\Models\ObservationChildCase::class, 'observation_child_case_id');
    }
    public function observer()
{
    return $this->hasOneThrough(
        Employee::class,
        ObservationChildCase::class,
        'id',
        'id',
        'observation_child_case_id', 
        'employee_id' 
    );
}

public function observationSession()
{
    return $this->hasOneThrough(
        ObservationSession::class,
        ObservationChildCase::class,
        'id', 
        'id',
        'observation_child_case_id',
        'observation_session_id'
    );
}

}
