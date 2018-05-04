<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class CorporationSheetResource.
 * @package Seat\Api\Http\Resources
 */
class CorporationSheetResource extends Resource
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
            'ticker'          => $this->ticker,
            'member_count'    => $this->member_count,
            'ceo_id'          => $this->ceo_id,
            'alliance_id'     => $this->alliance_id,
            'description'     => $this->description,
            'tax_rate'        => $this->tax_rate,
            'date_founded'    => $this->date_founded,
            'creator_id'      => $this->creator_id,
            'url'             => $this->url,
            'faction_id'      => $this->faction_id,
            'home_station_id' => $this->home_station_id,
            'shares'          => $this->shares,
        ];
    }
}
