<?php

namespace App\Models;

use App\Models\Order;
use App\Models\CartProduct;
use App\Models\CognifyParent;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $timestamps = true;
    protected $table = 'carts';
    protected $fillable = [
        'user_id', 'session_id', 'status', 'coupon_code',
        'discount_amount', 'shipping_fee', 'total_amount'
    ];


    public function user()
    {
        return $this->belongsTo(CognifyParent::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(CartProduct::class);
    }


    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
