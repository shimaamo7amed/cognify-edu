<?php

namespace App\Models;

use App\Models\Product;
use App\Models\CognifyParent;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    public $timestamps = true;
    protected $table = 'wish_lists';
    protected $fillable = [
        'user_id', 'session_id'
    ];

    public function user()
    {
        return $this->belongsTo(CognifyParent::class, 'user_id');
    }

    public function products()
{
    return $this->belongsToMany(Product::class, 'product_wishlists', 'wishlist_id', 'product_id')
        ->withTimestamps();
}

}
