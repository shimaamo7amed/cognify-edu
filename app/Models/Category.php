<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = true;
    protected $table = 'categories';
    protected $fillable = ['name'];
    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
    ];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = [
                'en' => Str::slug($category->name['en']),
                'ar' => $category->name['ar']
            ];
        });

        static::updating(function ($category) {
            $category->slug = [
                'en' => Str::slug($category->name['en']),
                'ar' => $category->name['ar']
            ];
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
