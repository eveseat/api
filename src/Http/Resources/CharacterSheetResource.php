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
 * Class CharacterSheetResource.
 *
 * @package Seat\Api\Http\Resources
 */

#[OA\Schema(
    schema: 'CharacterSheetResource',
    title: 'CharacterSheetResource',
    description: 'Character Sheet Resource',
    properties: [
        new OA\Property(property: 'name', description: 'Character name', type: 'string'),
        new OA\Property(property: 'description', description: 'Character biography', type: 'string'),
        new OA\Property(property: 'corporation', ref: '#/components/schemas/UniverseName', description: 'Character corporation'),
        new OA\Property(property: 'alliance', ref: '#/components/schemas/UniverseName', description: 'Character alliance (if any)'),
        new OA\Property(property: 'faction', ref: '#/components/schemas/UniverseName', description: 'Character faction (if any)'),
        new OA\Property(property: 'birthday', description: 'Character birthday', type: 'string', format: 'date-time'),
        new OA\Property(property: 'gender', description: 'Character gender', type: 'string', enum: ['male', 'female']),
        new OA\Property(property: 'race_id', description: 'Character race identifier', type: 'integer'),
        new OA\Property(property: 'bloodline_id', description: 'Character bloodline identifier', type: 'integer'),
        new OA\Property(property: 'security_status', description: 'Character security status', type: 'number'),
        new OA\Property(property: 'balance', description: 'Character wallet balance', type: 'number'),
        new OA\Property(property: 'skillpoints', properties: [
            new OA\Property(property: 'total_sp', description: 'The total skill points owned by the character', type: 'number'),
            new OA\Property(property: 'unallocated_sp', description: 'The total skill points not allocated for this character', type: 'number'),
        ], type: 'object'),
        new OA\Property(property: 'user_id', description: 'Seat user identifier', type: 'integer'),
    ],
    type: 'object'
)]
class CharacterSheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'corporation' => $this->affiliation->corporation,
            'alliance' => $this->affiliation->alliance,
            'faction' => $this->affiliation->faction,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'race_id' => $this->race_id,
            'bloodline_id' => $this->bloodline_id,
            'security_status' => $this->security_status,
            'balance' => $this->balance->balance,
            'skillpoints' => [
                'total_sp' => $this->skillpoints->total_sp,
                'unallocated_sp' => $this->skillpoints->unallocated_sp,
            ],
            'user_id' => $this->user->id,
        ];
    }
}
