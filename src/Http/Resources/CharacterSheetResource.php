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
use Illuminate\Support\Arr;

/**
 * Class CharacterSheetResource.
 *
 * @package Seat\Api\Http\Resources
 *
 * @OA\Schema(
 *     type="object",
 *     title="CharacterSheetResource",
 *     schema="CharacterSheetResource",
 *     description="Character Sheet Resource",
 *     @OA\Property(property="name", type="string", description="Character name"),
 *     @OA\Property(property="description", type="string", description="Character biography"),
 *     @OA\Property(property="corporation", ref="#/components/schemas/UniverseName", description="Character corporation"),
 *     @OA\Property(property="alliance", ref="#/components/schemas/UniverseName", description="Character alliance (if anny)"),
 *     @OA\Property(property="faction", ref="#/components/schemas/UniverseName", description="Character faction (if any)"),
 *     @OA\Property(property="birthday", type="string", format="date-time", description="Character birthday"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female"}, description="Character gender"),
 *     @OA\Property(property="race_id", type="integer", description="Character race identifier"),
 *     @OA\Property(property="bloodline_id", type="integer", description="Character bloodline identifier"),
 *     @OA\Property(property="security_status", type="number", description="Character security status"),
 *     @OA\Property(property="balance", type="number", description="Character wallet balance"),
 *     @OA\Property(property="skillpoints", type="object",
 *       @OA\Property(property="total_sp", type="number", description="The total skill points owned by the character"),
 *       @OA\Property(property="unallocated_sp", type="number", description="The total skill points not allocated for this character")
 *     ),
 *     @OA\Property(property="user_id", type="integer", description="Seat user identifier")
 * )
 */
class CharacterSheetResource extends Resource
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

        $definition = parent::toArray($request);

        Arr::forget($definition, 'character_id');
        Arr::forget($definition, 'skillpoints.character_id');
        Arr::forget($definition, 'skillpoints.created_at');
        Arr::forget($definition, 'skillpoints.updated_at');
        Arr::set($definition, 'balance', Arr::get($definition, 'balance.balance'));

        return  $definition;
    }
}
