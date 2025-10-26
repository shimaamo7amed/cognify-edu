<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObservationSession extends Model
{
    public $timestamps = true;
    protected $table = "observation_sessions";
    protected $fillable = [
        'name',
        'desc',
        'service_fee',
        'service_tax',
        'duration'
    ];
    protected $casts=
    [
        'name' => 'array',
        'desc' => 'array'
    ];
    public function slots()
    {
        return $this->hasMany(SessionSlot::class);
    }

}
