<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language', 'en');

        return [
            'id' => $this->id,
            'title' => $this->title[$locale] ?? $this->title['en'],
            'description' => $this->description[$locale] ?? $this->description['en'],
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
                    'children' => ServiceCoreResource::collection($this->whenLoaded('children')),

        ];
    }
}
