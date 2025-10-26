<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
        use Translatable;

    public $timestamps = true;
    protected $table = "events";
    protected $fillable = [
        'title',
        'description',
        'startDate',
        'endDate',
        'location',

    ];
    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'location' => 'array',
    ];
        protected $appends = ['title_translated', 'description_translated', 'location_translated'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
        $event->code= Str::slug($event->title['en']);
        });
        static::updating(function ($event) {
            $event->code = Str::slug($event->title['en']);
        });
    }
    public function getTitleTranslatedAttribute()
    {
        return $this->getTranslatedAttribute($this->title);
    }

    public function getDescriptionTranslatedAttribute()
    {
        return $this->getTranslatedAttribute($this->description);
    }

    public function getLocationTranslatedAttribute()
    {
        return $this->getTranslatedAttribute($this->location);
    }

}