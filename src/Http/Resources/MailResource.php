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

use Illuminate\Support\Arr;
use OpenApi\Attributes as OA;
use Seat\Api\Http\Resources\Json\JsonResource;

/**
 * Class MailResource.
 *
 * @package Seat\Api\Http\Resources
 */
class MailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    #[OA\Schema(schema: 'MailResource', description: 'Mail Resource', properties: [
        new OA\Property(property: 'mail_id', description: 'The mail identifier', type: 'integer', format: 'int64'),
        new OA\Property(property: 'subject', description: 'The mail topic', type: 'string'),
        new OA\Property(property: 'timestamp', description: 'The date-time when the mail has been sent', type: 'string', format: 'date-time'),
        new OA\Property(property:'sender', ref: '#/components/schemas/UniverseName', description:'The mail sender'),
        new OA\Property(property: 'body', description: 'The mail content', type: 'string'),
        new OA\Property(property: 'recipients', description: 'A list of recipients', type: 'array', items: new OA\Items(ref: '#/components/schemas/UniverseName')),
    ], type: 'object')]
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
