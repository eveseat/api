<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

use OpenApi\Attributes as OA;
use Seat\Api\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    #[OA\Schema(schema: 'PermissionResource', title: 'PermissionResource', description: 'Permission Resource', properties: [
        new OA\Property(property: 'title', description: 'Permission technical name', type: 'string'),
        new OA\Property(property: 'filters', description: 'Entities to which the permission is limited', type: 'object'),
    ])]
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'filters' => json_decode($this->pivot->filters),
        ];
    }
}
