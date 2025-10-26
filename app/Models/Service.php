<?php

namespace App\Models;

use App\Models\ServiceItem;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

use Translatable;

    public $timestamps = true;
    protected $table = "services";
    protected $fillable = [
        'title',
        'description',
    ];
    protected $casts = [
        'title' => 'array',
        'description' => 'array',
    ];

    public function items()
{
    return $this->hasMany(ServiceItem::class);
}

}