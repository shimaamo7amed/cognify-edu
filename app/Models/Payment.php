<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $timestamps = true;
    protected $table = "payments";

    protected $fillable = [
        'parent_id', 'child_id', 'session_id',
        'transaction_id', 'status', 'amount', 'metadata', 'reference_id',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

}
