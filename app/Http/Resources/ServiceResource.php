<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language', 'en');

        return [
            'service_id'    => $this->id,
            'service_name' => $this->title[$locale] ?? $this->title['en'],
            'options' => ServiceItemResource::collection($this->whenLoaded('items')),
        ];
    }
}


