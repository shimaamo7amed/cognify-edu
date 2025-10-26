<?php

namespace App\Http\Resources;

use App\Http\Resources\WishlistProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'wishlist_id' => $this->id,
            'user_id'     => $this->user_id,
            'session_id'  => $this->session_id,
            'products'    => WishlistProductResource::collection($this->products),
        ];
    }
}
