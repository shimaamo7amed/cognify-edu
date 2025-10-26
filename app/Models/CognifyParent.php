<?php
namespace App\Models;
use App\Models\Order;
use App\Models\Report;
use App\Models\Review;
use App\Models\WishList;
use App\Models\CognifyChild;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CognifyParent extends Authenticatable
{
    use HasApiTokens;

    public $timestamps = true;
    protected $table = "cognify_parents";
    protected $fillable =
    [
        'name',
        'email',
        'phone',
        'address',
        'governorate',
        'password',
        'active',
        'completed',
        'otp',
        'step',
        'role',
        'otp_expires_at'
    ];

    protected $hidden = [
        'id',
        'otp',
        'completed',
        'active',
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function children(): HasMany
    {
        return $this->hasMany(CognifyChild::class, 'parent_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlist()
    {
        return $this->hasMany(WishList::class);
    }
    public function review()
    {
        return $this->hasOne(Review::class, 'user_id');
    }

}
