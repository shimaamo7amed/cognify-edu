<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventsResource extends JsonResource
{
    

      public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language', 'en');

        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title[$locale] ?? $this->title['en'],
            'description' => $this->description[$locale] ?? $this->description['en'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'location' => $this->location[$locale] ?? $this->location['en'],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}