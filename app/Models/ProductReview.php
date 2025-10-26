<?php

namespace App\Models;

use App\Models\Product;
use App\Models\CognifyParent;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    public $timestamps = true;
    protected $table = 'product_reviews';
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'rate',
    ];

    public function user()
    {
        return $this->belongsTo(CognifyParent::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
