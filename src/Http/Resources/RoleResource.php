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

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RoleResource.
 *
 * @package Seat\Api\Http\Resources
 *
 * @OA\Schema(
 *     title="RoleResource",
 *     schema="RoleResource",
 *     description="Role Resource",
 *     @OA\Property(property="title", type="string", description="Role name"),
 *     @OA\Property(property="description", type="string", description="Role description"),
 *     @OA\Property(property="logo", type="string", format="byte", description="Role logo"),
 *     @OA\Property(property="permissions", type="array", description="Role permissions list", @OA\Items(ref="#/components/schemas/PermissionResource")),
 *     @OA\Property(property="members", type="array", description="Role members list", @OA\Items(type="integer")),
 *     @OA\Property(property="squads", type="array", description="Role squads list", @OA\Items(type="integer"))
 * )
 *
 * @OA\Schema(
 *     schema="CreateRole",
 *     type="object",
 *     allOf={
 *       @OA\Schema(
 *          @OA\Property(property="id", type="integer", description="The created role ID")
 *       ),
 *       @OA\Schema(ref="#/components/schemas/RoleResource")
 *     }
 * )
 */
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
