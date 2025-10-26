<?php

namespace App\Models;

use App\Models\Service;
use App\Models\ServiceItem;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public $timestamps = true;
    protected $table = "partners";
   protected $fillable = [
    'organizationName',
    'contactPersonName',
    'email',
    'phoneNumber',
    'location',
    'service_id',
    'is_approved'
];

protected $attributes = [
    'is_approved' => 'pending'
];

        public function service()
{
    return $this->belongsTo(Service::class);
}

public function serviceItems()
{
    return $this->belongsToMany(ServiceItem::class, 'partner_service_item');
}



}