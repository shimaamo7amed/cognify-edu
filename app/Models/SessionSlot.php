<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionSlot extends Model
{
    public $timestamps = true;
    protected $table = "session_slots";
    protected $fillable = [
        'observation_session_id',
        'start_time',
        'end_time',
        'interval',
        'is_booked',
        'recurrence_type',
        'recurrence_days',
        'recurrence_end_date',
    ];
    public function session()
    {
        return $this->belongsTo(ObservationSession::class, 'observation_session_id');
    }
}
