<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $lang = app()->getLocale();

        return [
            'slug'=>$this->slug,
            'product_id'   => $this->id,
            'name' => $this->name[$lang] ?? 'N/A',
            'category'     => $this->category?->name[$lang] ?? 'N/A',
            'stock_quantity'      => (int) $this->quantity,

            'inStock' => (bool) $this->inStock,
            'tags' => $this->tags?->name[$lang] ?? null,
            'price' => rtrim(rtrim(number_format($this->price, 2, '.', ''), '0'), '.'),
            'oldPrice' => $this->oldPrice !== null 
                ? rtrim(rtrim(number_format($this->oldPrice, 2, '.', ''), '0'), '.') 
                : null,

            'discountPercentage' => $this->discountPercentage !== null
                ? $this->discountPercentage . '%'
                : null,
            'longDes'      => $this->longDes[$lang] ?? null,
            'ageRange'     => $this->ageRange,
            'images' => collect($this->main_image)->map(function ($img) {
                return asset('storage/' . $img);
            }),
        ];
    }
}
