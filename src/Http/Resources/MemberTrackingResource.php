<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class MemberTrackingResource.
 * @package Seat\Api\Http\Resources
 */
class MemberTrackingResource extends Resource
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
            'character_id' => $this->character_id,
            'start_date'   => $this->start_date,
            'base_id'      => $this->base_id,
            'logon_date'   => $this->logon_date,
            'logoff_date'  => $this->logoff_date,
            'location_id'  => $this->location_id,
            'ship_type_id' => $this->ship_type_id,
            'ship_type'    => $this->type,
        ];
    }
}
