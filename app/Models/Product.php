<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Category;
use App\Models\WishList;
use App\Models\CartProduct;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = true;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'shortDes',
        'longDes',
        'price',
        'oldPrice',
        'sale',
        'discountPercentage',
        'quantity',
        'main_image',
        'inStock',
        'ageRange',
        'category_id',
        'tag_id'
    ];
    protected $casts = [
        'name' => 'array',
        'shortDes' => 'array',
        'longDes' => 'array',
        'main_image' => 'array',
        // 'slug' => 'array',
    ];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // $product->slug = [
            //     'en' => Str::slug($product->name['en']),
            //     'ar' => $product->name['ar']
            // ];
            $product->slug = Str::slug($product->name['en']);

        });

        static::updating(function ($product) {
        $product->slug = Str::slug($product->name['en']);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function tags()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class, 'product_wishlists', 'product_id', 'wishlist_id')
            ->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
                    ->withPivot('quantity', 'price', 'total')
                    ->withTimestamps();
    }



}
