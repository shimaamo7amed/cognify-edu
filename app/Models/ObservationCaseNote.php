<?php

namespace App\Models;

use App\Models\ObservationChildCase;
use Illuminate\Database\Eloquent\Model;

class ObservationCaseNote extends Model
{
    protected $table = 'observation_case_notes';
    public $timestamps = true;
    protected $fillable = [
        'observation_child_case_id',
        'note',
        'author_id',
        'author_type',
    ];

    public function case()
    {
        return $this->belongsTo(ObservationChildCase::class, 'observation_child_case_id');
    }

    public function author()
    {
        return $this->morphTo();
    }
}
