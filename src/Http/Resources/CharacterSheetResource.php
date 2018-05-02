<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CharacterSheetResource extends Resource
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
            'name'            => $this->name,
            'description'     => $this->description,
            'corporation_id'  => $this->corporation_id,
            'alliance_id'     => $this->alliance_id,
            'birthday'        => $this->birthday,
            'gender'          => $this->gender,
            'race_id'         => $this->race_id,
            'bloodline_id'    => $this->bloodline_id,
            'ancenstry_id'    => $this->ancenstry_id,
            'security_status' => $this->security_status,
            'faction_id'      => $this->faction_id,
            'balance'         => $this->balance->balance,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
