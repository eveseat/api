<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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
use Seat\Eveapi\Models\Market\CharacterOrder;
use Seat\Eveapi\Models\Market\CorporationOrder;

/**
 * Class MarketOrderResource.
 * @package Seat\Api\Http\Resources
 */
class MarketOrderResource extends Resource
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

        $definition = [
            'order_id'          => $this->order_id,
            'type_id'           => $this->type_id,
            'region_id'         => $this->region_id,
            'location_id'       => $this->location_id,
            'range'             => $this->range,
            'is_buy_order'      => $this->is_buy_order,
            'price'             => $this->price,
            'volume_total'      => $this->volume_total,
            'volume_remain'     => $this->volume_remain,
            'issued'            => $this->issued,
            'min_volume'        => $this->min_volume,
            'duration'          => $this->duration,
            'escrow'            => $this->escrow,
        ];

        if ($this->resource instanceof CorporationOrder)
            $definition['wallet_division'] = $this->wallet_division;

        if ($this->resource instanceof CharacterOrder)
            $definition['is_corporation'] = $this->is_corporation;

        $definition = array_add($definition, 'created_at', $this->created_at);
        $definition = array_add($definition, 'updated_at', $this->updated_at);

        return $definition;
    }
}
