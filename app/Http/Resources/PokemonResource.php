<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PokemonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shape' => $this->shape,
            'order' => $this->order,
            'abilities' => AbilityResource::collection($this->abilities),
            'location' => new LocationResource($this->location),
            'region' => $this->region->name,
            'image' => $this->image_url,
        ];
    }
}
