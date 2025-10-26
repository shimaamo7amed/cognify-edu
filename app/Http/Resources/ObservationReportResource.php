<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObservationReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'status'          => $this->status,
            'progress'        => $this->progress,
            'delivery_status' => $this->delivery_status,
            'sent_at'         => $this->sent_at,

            // الأسماء اللي محتاجاها
            'observer_name'    => $this->observer?->name,
            'observation_name' => $this->observationSession?->name,
        ];
    }
}
