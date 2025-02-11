<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_name' => $this->user_name,
            'ticket_quantity'=> $this->ticket_quantity,
            'status' => $this->status,
            'event' =>new EventResource($this->whenLoaded('event')),
        ];
    }
}
