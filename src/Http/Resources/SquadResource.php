<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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
 * Class SquadResource.
 *
 * @package Seat\Api\Http\Resources
 *
 * @OA\Schema(
 *     type="object",
 *     title="SquadResource",
 *     schema="SquadResource",
 *     description="Squad Resource",
 *     @OA\Property(property="id", type="integer", description="The unique identifier"),
 *     @OA\Property(property="type", type="string", enum={"manual", "auto", "hidden"}, description="The Squad management type"),
 *     @OA\Property(property="name", type="string", description="The Squad name"),
 *     @OA\Property(property="logo", type="string", format="byte", description="The Squad Logo"),
 *     @OA\Property(property="description", type="string", description="The squad description")
 * )
 *
 * @OA\Schema(
 *     type="object",
 *     title="Squad",
 *     schema="Squad",
 *     description="Detailed Squad",
 *     allOf={
 *       @OA\Schema(ref="#/components/schemas/SquadResource"),
 *       @OA\Schema(
 *          @OA\Property(property="roles", type="array", @OA\Items(type="integer"), description="List of roles attached to that Squad"),
 *          @OA\Property(property="moderators", type="array", @OA\Items(type="integer"), description="List of moderators attached to that Squad"),
 *          @OA\Property(property="members", type="array", @OA\Items(type="integer"), description="List of members attached to that Squad"),
 *          @OA\Property(property="applications", type="array", @OA\Items(type="integer"), description="List of candidates attached to that Squad")
 *       )
 *     }
 * )
 */
class SquadResource extends Resource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'logo' => $this->logo,
            'description' => $this->description,
            'roles' => $this->whenLoaded('roles', function () { return $this->roles->pluck('id'); }),
            'moderators' => $this->whenLoaded('moderators', function () { return $this->moderators->pluck('id'); }),
            'members' => $this->whenLoaded('members', function () { return $this->members->pluck('id'); }),
            'applications' => $this->whenLoaded('applications', function () { return $this->applications->pluck('user_id'); }),
        ];
    }
}
