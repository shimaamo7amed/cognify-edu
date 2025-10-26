<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ChildResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'address'   => $this->address,
            'governorate' => $this->governorate,

            'role'      => $this->role,
            'step'      => $this->step,
            // Review
            'review' => $this->review ? [
                'id'        => $this->review->id,
                'review'    => $this->review->review,
                'rating'    => $this->review->rating,
                'is_reviewed' => $this->review->is_reviewed,
            ] : null,
         'children' => ChildResource::collection($this->whenLoaded('children')),
        ];
    }
}
