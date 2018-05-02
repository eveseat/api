<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class JumpcloneResource.
 * @package Seat\Api\Http\Resources
 */
class JumpcloneResource extends Resource
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
            'jump_clone_id' => $this->jump_clone_id,
            'name'          => $this->name,
            'location_id'   => $this->location_id,
            'location_type' => $this->location_type,
            'location'      => $this->location,
            'implants'      => $this->implants,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
