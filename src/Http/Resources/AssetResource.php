<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class AssetResource.
 * @package Seat\Api\Http\Resources
 */
class AssetResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {

        return [
            'item_id'       => $this->item_id,
            'type_id'       => $this->type_id,
            'quantity'      => $this->quantity,
            'location_id'   => $this->location_id,
            'location_type' => $this->location_type,
            'location_flag' => $this->location_flag,
            'is_singleton'  => $this->is_singleton,
            'x'             => $this->x,
            'y'             => $this->y,
            'z'             => $this->z,
            'map_id'        => $this->map_id,
            'map_name'      => $this->map_name,
            'name'          => $this->name,
            'type'          => $this->type,
        ];
    }
}
