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

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Arr;

/**
 * Class MailResource.
 *
 * @package Seat\Api\Http\Resources
 *
 * @OA\Schema(
 *     schema="MailResource",
 *     description="Mail Resource",
 *     type="object",
 *     @OA\Property(property="mail_id", type="integer", format="int64", description="The mail identifier"),
 *     @OA\Property(property="subject", type="string", description="The mail topic"),
 *     @OA\Property(property="timestamp", type="string", format="date-time", description="The date-time when the mail has been sent"),
 *     @OA\Property(property="sender", ref="#/components/schemas/UniverseName", description="The mail sender"),
 *     @OA\Property(property="body", type="string", description="The mail content"),
 *     @OA\Property(property="recipients", type="array", description="A list of recipients", @OA\Items(ref="#/components/schemas/UniverseName"))
 * )
 */
class MailResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $definition = parent::toArray($request);

        Arr::forget($definition, 'character_id');
        Arr::forget($definition, 'created_at');
        Arr::forget($definition, 'updated_at');
        Arr::forget($definition, 'from');

        Arr::set($definition, 'body', Arr::get($definition, 'body.body'));
        Arr::set($definition, 'recipients', array_map(function ($recipient) {
            return [
                'entity_id' => $recipient['entity']['entity_id'],
                'name' => $recipient['entity']['name'],
                'category' => $recipient['entity']['category'],
            ];
        }, Arr::get($definition, 'recipients')));

        return $definition;
    }
}
