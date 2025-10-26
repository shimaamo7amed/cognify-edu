<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\CognifyParent;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public $timestamps = true;
    protected $table = 'orders';
    protected $fillable = [
        'user_id', 'cart_id', 'session_id', 'order_number', 'coupon_id','status',
        'discount_amount', 'shipping_fee', 'subtotal', 'total_amount',
        'payment_status', 'payment_method','payment_id',
        'guest_name', 'guest_email', 'guest_phone', 'guest_address'
    ];

    public function user()
    {
        return $this->belongsTo(CognifyParent::class, 'user_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity', 'price', 'total')
                    ->withTimestamps();
    }




}
