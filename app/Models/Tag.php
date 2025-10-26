<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = true;
    protected $table = 'tags';
    protected $fillable = ['name'];
    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->slug = [
                'en' => Str::slug($tag->name['en']),
                'ar' => $tag->name['ar']
            ];
        });

        static::updating(function ($tag) {
            $tag->slug = [
                'en' => Str::slug($tag->name['en']),
                'ar' => $tag->name['ar']
            ];
        });
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }


}
