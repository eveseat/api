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
use Seat\Api\Http\Resources\UserResource;
use Seat\Api\Http\Validation\NewUser;
use Seat\Web\Models\User;

class UserController extends ApiController
{
    #[OA\Get(
        path: '/api/v2/users',
        description: 'Returns list of users',
        summary: 'Get a list of users, associated character id\'s and group ids',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/User')),
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
    public function getUsers(): AnonymousResourceCollection
    {
        return UserResource::collection(User::paginate()->appends(request()->except('page')));
    }

    #[OA\Get(
        path: '/api/v2/users/{user_id}',
        description: 'Returns a user',
        summary: 'Get group id\'s and associated character_id\'s for a user',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Users'],
        parameters: [
            new OA\Parameter(name: 'user_id', description: 'User ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/User', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function show(int $user_id): UserResource
    {
        return UserResource::make(User::findOrFail($user_id));
    }

    #[OA\Get(
        path: '/api/v2/users/configured-scopes',
        description: 'Returns list of scopes',
        summary: 'Get a list of the scopes configured for this instance',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    description: 'The scope list requested by the SeAT instance',
                    type: 'array',
                    items: new OA\Items(type: 'string')
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getConfiguredScopes(): JsonResponse
    {

        return response()
            ->json(setting('sso_scopes', true));
    }

    #[OA\Post(
        path: '/api/v2/users',
        description: 'Creates a new SeAT User',
        summary: 'Creates a new SeAT User',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['name', 'main_character_id'],
                    properties: [
                        new OA\Property(property: 'name', description: 'Eve Online (main) Character Name', type: 'string'),
                        new OA\Property(property: 'active', description: 'Set the SeAT account state. Default is true.', type: 'boolean'),
                        new OA\Property(property: 'email', description: 'A contact e-mail address for the created user', type: 'string', format: 'email'),
                        new OA\Property(property: 'main_character_id', description: 'Eve Online main Character ID', type: 'integer', format: 'int64', minimum: 90000000),
                    ]
                )
            )
        ),
        tags: [
            'Users',
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/User', type: 'object'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function postNewUser(NewUser $request): JsonResponse|UserResource
    {
        if ($request->get('name') == 'admin')
            return response()->json('You cannot create this user.', 403);

        $user = new User();
        $user->name = $request->get('name');
        $user->main_character_id = $request->get('main_character_id');
        $user->active = $request->get('active') ?? true;

        $user->save();

        if ($request->has('email'))
            setting(['email', $request->get('email'), $user->id], false);

        // Log the new account creation
        event('security.log', [
            'Created a new account for ' . $request->get('name') . ' via an API call.',
            'authentication',
        ]);

        return UserResource::make($user);
    }

    #[OA\Delete(
        path: '/api/v2/users/{user_id}',
        description: 'Deletes a user',
        summary: 'Deletes a SeAT user',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: [
            'Squads',
        ],
        parameters: [
            new OA\Parameter(name: 'user_id', description: 'A SeAT User ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function deleteUser(int $user_id): JsonResponse
    {

        $user = User::findOrFail($user_id);

        if ($user->name == 'admin')
            return response()->json('You cannot delete this user.', 403);

        $user->delete();

        return response()->json();
    }
}
