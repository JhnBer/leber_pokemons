<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Location;

class LocationResource extends JsonResource
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
            'region' => new RegionResource($this->region),
            'region_id' => $this->region_id,
            'parent_location' => $this->getParentLocationsRecursive($this) ?? null,
            'parent_id' => $this->parent_id,
            
        ];
    }

    /**
     * Get all parent locations recursively.
     *
     * @param  Location  $location
     * @return array | null
     */
    protected function getParentLocationsRecursive($location): array | null
    {
        $parent = null;

        if ($location->parent_id !== null) {
            $parentLocation = Location::find($location->parent_id);
            if ($parentLocation) {
                $parent = [
                    'id' => $parentLocation->id,
                    'name' => $parentLocation->name,
                    'parent_location' => ( $parentLocation->parent_id ? $this->getParentLocationsRecursive($parentLocation) : null ) 
                ];
            }
        }

        return $parent ?? null;
    }
}
