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
