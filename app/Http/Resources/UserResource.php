<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,

            // frontend szempontból hasznos
            'role'  => $this->role?->name,
            'is_admin' => $this->isAdmin(),
            'ownership_ratio' => $this->ownership_ratio,
        ];
    }
}
