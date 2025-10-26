<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    public $timestamps = true;
    protected $table = "contact_us";
    protected $fillable = [
        'name',
        'email',
        'message',
        'phoneNumber',
        'date'

    ];
}