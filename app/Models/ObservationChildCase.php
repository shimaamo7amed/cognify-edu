<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class ObservationChildCase extends Model
{
    protected $table='observation_child_cases';
    public $timestamps=true;
    protected $fillable = [
        'child_id',
        'employee_id',
        'observation_session_id',
        'slot_date',
        'slot_time',
        'total_amount',
        'status',
        'duration',
        'status_updated_by',
        'pyment_method',
    ];
    protected $casts = [
    'slot_date' => 'date',
];

    
    public function child()
    {
        return $this->belongsTo(CognifyChild::class);
    }

    public function session()
    {
        return $this->belongsTo(ObservationSession::class, 'observation_session_id');
    }

    public function slot()
    {
        return $this->belongsTo(SessionSlot::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function notes()
    {
        return $this->hasMany(ObservationCaseNote::class);
    }


}
