<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    public $timestamps = true;
    protected $table = "admins";
    protected $guard_name = 'admin';
    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'password',
        'remember_token',
    ];
    protected $casts = [
        "password" => "hashed"
    ];
    public function canAccessPanel(\Filament\Panel $panel): bool
{
    return true;
}

}
