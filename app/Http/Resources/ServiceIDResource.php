<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceIDResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language', 'en');

        return [
            'id'       => $this->id,
            'title'    => $this->title[$locale] ?? $this->title['en'],

            'services' => [
                $this->key => ServiceItemResource::collection($this->items),
            ],
        ];
    }
}
