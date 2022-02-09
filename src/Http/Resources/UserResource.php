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

use OpenApi\Annotations as OA;
use Seat\Api\Http\Resources\Json\JsonResource;

/**
 * Class UserResource.
 *
 * @package Seat\Api\Http\Resources
 *
 * @OA\Schema(
 *     description="User",
 *     schema="User",
 *     title="User",
 *     type="object"
 * )
 *
 * @OA\Property(
 *     property="id",
 *     type="integer",
 *     format="int64",
 *     minimum=1,
 *     description="ID"
 * )
 *
 * @OA\Property(
 *     property="name",
 *     type="string",
 *     description="Name",
 *     maxLength=255
 * )
 *
 * @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *     description="E-Mail address"
 * )
 *
 * @OA\Property(
 *     property="active",
 *     type="boolean",
 *     description="Account status"
 * )
 *
 * @OA\Property(
 *     property="last_login",
 *     type="string",
 *     format="date-time",
 *     description="Last login to SeAT time"
 * )
 *
 * @OA\Property(
 *     property="last_login_source",
 *     type="string",
 *     description="Last IP address used to sign in to SeAT"
 * )
 *
 * @OA\Property(
 *     property="associated_character_ids",
 *     type="array",
 *     description="Array of attached character ID",
 *     @OA\Items(type="integer", format="int64", minimum=90000000)
 * )
 *
 * @OA\Property(
 *     property="main_character_id",
 *     type="integer",
 *     format="int64",
 *     minimum=90000000,
 *     description="The main character ID of this group"
 * )
 */
class UserResource extends JsonResource
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

            'id'                       => $this->id,
            'name'                     => $this->name,
            'email'                    => $this->email,
            'active'                   => $this->active,
            'last_login'               => $this->last_login,
            'last_login_source'        => $this->last_login_source,
            'associated_character_ids' => $this->associatedCharacterIds(),
            'main_character_id'        => $this->main_character_id,
        ];
    }
}
