<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ObservationSessionResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        $user = Auth::user();
        $child = $user?->children()?->first();
        $serviceFee = floatval($this->service_fee);
        $serviceTax = floatval($this->service_tax);
        $locale = $request->header('Accept-Language', 'en');
        $locale = in_array($locale, ['ar', 'en']) ? $locale : 'en';

        return [
            'child_id' => $child?->id,
            'session_id' => $this->id,
            'name' => $this->name[$locale] ?? $this->session?->name[$locale] ?? null,
            'desc' => $this->desc[$locale] ?? $this->session?->desc[$locale] ?? null,
            'duration' => $this->duration,
            'service_fee' => $this->service_fee,
            'service_tax' => $this->service_tax,
            'total_amount' => $serviceFee + $serviceTax,

        ];
    }
}
