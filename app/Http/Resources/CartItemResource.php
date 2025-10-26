<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $lang = app()->getLocale();

        return [
            'id'          => $this->id,
            'slug'         =>$this->product->slug,
            'cart_id'     => $this->cart_id,
            'product_id'  => (int) $this->product_id,
            'quantity'    => (int) $this->quantity,
            'unit_price'  => round((float) $this->unit_price, 2),
            'total_price' => round((float) $this->total_price, 2),
            'created_at'  => $this->created_at?->format('d-m-Y'),
            'updated_at'  => $this->updated_at?->format('d-m-Y'),

            // 🔹 بيانات المنتج
            'name'       => $this->product?->name[$lang] ?? 'N/A',
            'category'   => $this->product?->category?->name[$lang] ?? 'N/A',
            'tags'        => $this->product?->tags?->name[$lang] ?? 'N/A',
            'stock_quantity'      => (int) $this->product?->quantity,
            'longDes'            => $this->product?->longDes[$lang] ?? null,
            'price' => rtrim(rtrim(number_format($this->product->price, 2, '.', ''), '0'), '.'),
            'oldPrice'           => $this->product->oldPrice ? round((float) $this->product->oldPrice, 2) : null,

            'discountPercentage' => $this->product->discountPercentage !== null
                ? (float) $this->product->discountPercentage
                : null,
            'images' => is_array($this->product->main_image)
                ? collect($this->product->main_image)->map(fn($img) => asset('storage/' . $img))
                : ($this->product->main_image ? [asset('storage/' . $this->product->main_image)] : []),
            'ageRange' => $this->product->ageRange,
        ];
    }
}

