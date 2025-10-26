<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    public $timestamps = true;
    protected $table = 'cart_products';

    protected $fillable = [
        'cart_id', 'product_id', 'quantity', 'unit_price', 'total_price'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
