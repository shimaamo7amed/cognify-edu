<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'slug' =>  $this->slug,
            'name' => $this->name[$locale] ?? $this->name['en'],
            // 'shortDes' => $this->shortDes[$locale] ?? null,
            'longDes' => $this->longDes[$locale] ?? null,
            'price' => rtrim(rtrim(number_format($this->price, 2, '.', ''), '0'), '.'),
            'oldPrice' => $this->oldPrice !== null 
                ? rtrim(rtrim(number_format($this->oldPrice, 2, '.', ''), '0'), '.') 
                : null,

            'discountPercentage' => $this->discountPercentage !== null ? $this->discountPercentage . '%' : null,
            'sale' => (bool) $this->sale,
            'images' => collect($this->main_image)->map(function ($img) {
                return asset('storage/' . $img);
            }),
            'ageRange' => $this->ageRange,
            'inStock' => (bool) $this->inStock,
            'category' => $this->category?->name[$locale] ?? null,
            'tags' => $this->tags?->name[$locale] ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
