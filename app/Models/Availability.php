<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    public $timestamps = true;
    protected $table = "availabilities";
    protected $fillable = [
        'start_time',
        'end_time',
        'interval',
        'status',
        'recurrence_type',
        'recurrence_days',
        'recurrence_end_date',
        'notes'
    ];
}