<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

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
