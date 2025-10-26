<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    public $timestamps = true;
    protected $table = "service_items";
    protected $fillable = [
        'service_id',
        'title',
        'description',
    ];
    protected $casts = [
        'title' => 'array',
        'description' => 'array',
    ];
    public function service()
{
    return $this->belongsTo(Service::class);
}

}