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

namespace Seat\Api\Http\Controllers\Api\v2;

use OpenApi\Attributes as OA;
use Seat\Api\Http\Resources\ContactResource;
use Seat\Api\Http\Traits\Filterable;
use Seat\Eveapi\Models\Contacts\AllianceContact;

/**
 * Class AllianceController.
 *
 * @package Seat\Api\Http\Controllers\Api\v2
 */
class AllianceController extends ApiController
{
    use Filterable;

    #[OA\Get(
        path: '/api/v2/alliance/contacts/{alliance_id}',
        description: 'Returns a list of contacts',
        summary: 'Get a list of contacts for an alliance',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Contacts'],
        parameters: [
            new OA\Parameter(name: 'alliance_id', description: 'Alliance ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: '$filter', description: 'Query filter following OData format', in: 'query', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/AllianceContact')),
                        new OA\Property(property: 'links', ref: '#/components/schemas/ResourcePaginatedLinks'),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/ResourcePaginatedMetadata'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getContacts(int $alliance_id)
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = AllianceContact::with('labels')
            ->where('alliance_id', $alliance_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return ContactResource::collection($query->paginate()->appends(request()->except('page')));
    }
}
