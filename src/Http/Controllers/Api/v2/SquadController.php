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

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Seat\Api\Http\Resources\Json\AnonymousResourceCollection;
use Seat\Api\Http\Resources\SquadResource;
use Seat\Api\Http\Validation\NewSquad;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;

class SquadController extends ApiController
{
    #[OA\Get(
        path: '/api/v2/squads',
        description: 'Returns list of squads',
        summary: 'Get a list of squads',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Squads'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/SquadResource')),
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
    public function index(): AnonymousResourceCollection
    {
        return SquadResource::collection(Squad::paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/squads/{squad_id}',
        description: 'Return detailed information from a Squad',
        summary: 'Get details about a Squad',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Squads'],
        parameters: [
            new OA\Parameter(name: 'squad_id', description: 'Squad ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Squad')),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function show(int $squad_id): SquadResource
    {
        return SquadResource::make(Squad::with('roles', 'moderators', 'members', 'applications')->findOrFail($squad_id));
    }

    #[OA\Post(
        path: '/api/v2/squads',
        description: 'Creates a new SeAT Squad',
        summary: 'Creates a new SeAT Squad',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(required: ['name', 'type', 'description'], properties: [
                new OA\Property(property: 'name', description: 'Squad name', type: 'string'),
                new OA\Property(property: 'type', description: 'Squad type', type: 'string', enum: ['hidden', 'manual', 'auto']),
                new OA\Property(property: 'description', description: 'Squad description', type: 'string'),
                new OA\Property(property: 'logo', description: 'Squad logo', type: 'string', format: 'byte'),
            ]))
        ),
        tags: [
            'Squads',
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Squad', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function store(NewSquad $request): SquadResource
    {
        $squad = new Squad();
        $squad->name = $request->get('name');
        $squad->type = $request->get('type');
        $squad->description = $request->description;

        if ($request->has('logo'))
            $squad->logo = $request->get('logo');

        $squad->save();

        return SquadResource::make($squad->load('roles', 'moderators', 'members', 'applications'));
    }

    #[OA\Delete(
        path: '/api/v2/squads/{squad_id}',
        description: 'Deletes a Squad',
        summary: 'Delete a SeAT Squad',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: [
            'Squads',
        ],
        parameters: [
            new OA\Parameter(name: 'squad_id', description: 'A SeAT Squad ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function destroy(int $squad_id): JsonResponse
    {
        Squad::findOrFail($squad_id)->delete();

        return response()->json();
    }

    #[OA\Post(
        path: '/api/v2/squads/{squad_id}/add/{user_id}',
        description: 'Adds a user to a squad',
        summary: 'Adds a user to a squad. If the user is not eligible according to the squad\'s filters, the user cannot be added.',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: [
            'Squads',
        ],
        parameters: [
            new OA\Parameter(name: 'squad_id', description: 'A SeAT Squad ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'user_id', description: 'A SeAT User ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function addUser($squad_id, $user_id)
    {
        $squad = Squad::findOrFail($squad_id);
        $user = User::findOrFail($user_id);

        if(! $squad->isUserEligible($user)){
            return response()->json('The squad filter doesn\'t allow this user in this squad.', 400);
        }

        $squad->members()->attach($user->id);

        return response()->json();
    }

    #[OA\Post(
        path: '/api/v2/squads/{squad_id}/remove/{user_id}',
        description: 'Removes a user from a squad',
        summary: 'Removes a user from a squad.',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: [
            'Squads',
        ],
        parameters: [
            new OA\Parameter(name: 'squad_id', description: 'A SeAT Squad ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'user_id', description: 'A SeAT User ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function removeUser($squad_id, $user_id)
    {
        $squad = Squad::findOrFail($squad_id);
        $user = User::findOrFail($user_id);

        $squad->members()->detach($user->id);

        return response()->json();
    }
}
