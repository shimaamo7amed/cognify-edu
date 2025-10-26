<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public $timestamps = true;
    protected $table = 'coupons';
    protected $fillable = [
        'code', 'type', 'value', 'usage_limit', 'used', 'is_active', 'expires_at', 'parent_id'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
