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

        $definition = parent::toArray($request);

        if ($this->resource instanceof CorporationOrder)
            array_forget($definition, 'corporation_id');

        if ($this->resource instanceof CharacterOrder)
            array_forget($definition, 'character_id');

        return $definition;
    }
}
