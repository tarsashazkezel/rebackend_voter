<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'meeting_date' => $this->meeting_date,
            'location'     => $this->location,

            'created_by' => new UserResource($this->whenLoaded('creator')),

            'agenda_items' => AgendaItemResource::collection(
                $this->whenLoaded('agendaItems')
            ),

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
