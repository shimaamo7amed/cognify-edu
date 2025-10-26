<?php

namespace App\Models;

use App\Models\CognifyParent;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public $timestamps = true;
    protected $table = 'reviews';
    protected $fillable = [
        'user_id',
        'is_reviewed',
        'review',
        'rating',
    ];
    public function user()
    {
        return $this->belongsTo(CognifyParent::class, 'user_id');
    }
}
