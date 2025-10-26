<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public $timestamps = true;
    protected $table = 'order_product';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
