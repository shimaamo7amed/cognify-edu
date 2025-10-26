<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait Translatable
{
public function getTranslatedAttribute($value)
{
$lang = request()->header('Accept-Language', 'en');

return $value[$lang] ?? $value['en'];
}
}