<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCore extends Model
{
    public $timestamps = true;
    protected $table = "service_cores";

    protected $fillable = [
        'title',
        'description',
        'image',
        'parent_id'
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
    ];

     public function parent()
    {
        return $this->belongsTo(ServiceCore::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ServiceCore::class, 'parent_id');
    }
}