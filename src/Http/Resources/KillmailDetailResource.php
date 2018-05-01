<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class KillmailDetailResource.
 * @package Seat\Api\Http\Resources
 */
class KillmailDetailResource extends Resource
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

            'killmail_time'   => $this->killmail_time,
            'solar_system_id' => $this->solar_system_id,
            'moon_id'         => $this->moon_id,
            'war_id'          => $this->war_id,
            'attackers'       => $this->attackers,
            'victims'         => $this->victims,
        ];
    }
}
