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

namespace Seat\Api\Http\Controllers\Api\v2;

use OpenApi\Attributes as OA;
use Seat\Api\Http\Resources\Json\AnonymousResourceCollection;
use Seat\Api\Http\Resources\Json\JsonResource;
use Seat\Api\Http\Resources\KillmailDetailResource;
use Seat\Eveapi\Models\Killmails\Killmail;
use Seat\Eveapi\Models\Killmails\KillmailDetail;

class KillmailsController extends ApiController
{
    #[OA\Get(
        path: '/v2/character/killmails/{character_id}',
        description: 'Returns list of killmails',
        summary: 'Get a paginated list of killmails for a character',
        security: [
            [
                'ApiKeyAuth' => []
            ]
        ],
        tags: ['Killmails'],
        parameters: [
            new OA\Parameter(name: 'character_id', description: 'Character ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Killmail')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized')
        ]
    )]
    public function getCharacterKillmails(int $character_id): AnonymousResourceCollection
    {
        return JsonResource::collection(
            Killmail::with('detail', 'victim', 'attackers')
                ->whereHas('victim', function ($query) use ($character_id) {
                    $query->where('character_id', $character_id);
                })->orWhereHas('attackers', function ($query) use ($character_id) {
                    $query->where('character_id', $character_id);
                })->paginate()->appends(request()->except('page'))
            );
    }

    #[OA\Get(
        path: '/v2/corporation/killmails/{corporation_id}',
        description: 'Returns list of killmails',
        summary: 'Get a paginated list of killmails for a corporation',
        security: [
            [
                'ApiKeyAuth' => []
            ]
        ],
        tags: ['Killmails'],
        parameters: [
            new OA\Parameter(name: 'corporation_id', description: 'Corporation ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Killmail')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized')
        ]
    )]
    public function getCorporationKillmails(int $corporation_id): AnonymousResourceCollection
    {
        return JsonResource::collection(
            Killmail::whereHas('victim', function ($query) use ($corporation_id) {
                $query->where('corporation_id', $corporation_id);
            })->orWhereHas('attackers', function ($query) use ($corporation_id) {
                $query->where('corporation_id', $corporation_id);
            })->paginate()->appends(request()->except('page'))
        );
    }

    #[OA\Get(
        path: '/v2/killmails/{killmail_id}',
        description: 'Returns a detailed killmail',
        summary: 'Get full details about a killmail',
        security: [
            [
                'ApiKeyAuth' => []
            ]
        ],
        tags: ['Killmails'],
        parameters: [
            new OA\Parameter(name: 'killmail_id', description: 'Killmail ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'killmail_time', description: 'The date/time when kill append', type: 'string', format: 'date-time'),
                            new OA\Property(property: 'solar_system_id', description: 'The solar system identifier in which the kill occurs', type: 'integer'),
                            new OA\Property(property: 'moon_id', description: 'The moon identifier near to which the kill occurs', type: 'integer'),
                            new OA\Property(property: 'war_id', description: 'The war identifier in which the kill involves', type: 'integer'),
                            new OA\Property(property: 'attackers', type: 'array', items: new OA\Items(ref: '#/components/schemas/KillmailAttacker')),
                            new OA\Property(property: 'victim', ref: '#/components/schemas/KillmailVictim')
                        ], type: 'object')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized')
        ]
    )]
    public function getDetail(int $killmail_id): KillmailDetailResource
    {

        return new KillmailDetailResource(KillmailDetail::with('attackers', 'victim')->findOrFail($killmail_id));
    }
}
