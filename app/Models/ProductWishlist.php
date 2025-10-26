<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWishlist extends Model
{
    protected $table = 'product_wishlists';
    
    protected $fillable = [
        'wishlist_id',
        'product_id',
    ];

    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class, 'wishlist_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
