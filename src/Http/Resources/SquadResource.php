<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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
 * Class SquadResource.
 *
 * @package Seat\Api\Http\Resources
 */
#[OA\Schema(
    schema: 'SquadResource',
    title: 'SquadResource',
    description: 'Squad Resource',
    properties: [
        new OA\Property(property: 'id', description: 'The unique identifier', type: 'integer'),
        new OA\Property(property: 'type', description: 'The Squad management type', type: 'string', enum: ['manual', 'auto', 'hidden']),
        new OA\Property(property: 'name', description: 'The Squad name', type: 'string'),
        new OA\Property(property: 'logo', description: 'The Squad Logo', type: 'string'),
        new OA\Property(property: 'description', description: 'The squad description', type: 'string'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Squad',
    title: 'Squad',
    description: 'Detailed squad',
    type: 'object',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/SquadResource'),
        new OA\Schema(
            properties: [
                new OA\Property(property: 'roles', description: 'List of roles attached to that Squad', type: 'array', items: new OA\Items(type: 'integer')),
                new OA\Property(property: 'moderators', description: 'List of moderators attached to that Squad', type: 'array', items: new OA\Items(type: 'integer')),
                new OA\Property(property: 'members', description: 'List of members attached to that Squad', type: 'array', items: new OA\Items(type: 'integer')),
                new OA\Property(property: 'applications', description: 'List of candidates attached to that Squad', type: 'array', items: new OA\Items(type: 'integer')),
            ]
        ),
    ]
)]
class SquadResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
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
