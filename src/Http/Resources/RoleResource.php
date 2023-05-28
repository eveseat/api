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

/**
 * Class RoleResource.
 *
 * @package Seat\Api\Http\Resources
 */

#[OA\Schema(
    schema: "RoleResource",
    title: "RoleResource",
    description: "Role Resource",
    properties: [
        new OA\Property(property: 'title', description: 'Role name', type: 'string'),
        new OA\Property(property: 'description', description: 'Role description', type: 'string'),
        new OA\Property(property: 'logo', description: 'Role logo', type: 'byte'),
        new OA\Property(property: 'permissions', description: 'Role permissions list', type: 'array', items: new OA\Items(ref: '#/components/schemas/PermissionResource')),
        new OA\Property(property: 'members', description: 'Role members list', type: 'array', items: new OA\Items(type: 'integer')),
        new OA\Property(property: 'squads', description: 'Role squads List', type: 'array', items: new OA\Items(type: 'integer')),
    ]
)]
#[OA\Schema(
    schema: 'CreateRole',
    type: 'object',
    allOf: [
        new OA\Schema(properties: [
            new OA\Property(property: 'id', description: 'The created role ID', type: 'integer'),
        ]),
        new OA\Schema(ref: '#/components/schemas/RoleResource')
    ]
)]
class RoleResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->when($request->method() == 'POST', $this->id),
            'title' => $this->title,
            'description' => $this->description,
            'logo' => $this->logo,
            'permissions' => PermissionResource::collection($this->permissions),
            'members' => $this->users->pluck('id'),
            'squads' => $this->squads->pluck('id'),
        ];
    }
}
