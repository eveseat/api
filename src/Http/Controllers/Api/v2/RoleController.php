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

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Seat\Api\Http\Resources\Json\AnonymousResourceCollection;
use Seat\Api\Http\Resources\Json\JsonResource;
use Seat\Api\Http\Resources\RoleResource;
use Seat\Api\Http\Validation\EditRole;
use Seat\Api\Http\Validation\NewRole;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\Acl\Role;

/**
 * Class RoleController.
 *
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class RoleController extends ApiController
{
    use AccessManager;
    use ValidatesRequests;

    #[OA\Get(
        path: '/api/v2/roles',
        description: 'Returns a list of roles',
        summary: 'Get the roles configured within SeAT',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Roles'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', description: 'Array of defined roles', type: 'array', items: new OA\Items(ref: '#/components/schemas/Role')),
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
    public function getIndex(): AnonymousResourceCollection
    {

        return JsonResource::collection(Role::paginate());
    }

    #[OA\Get(
        path: '/api/v2/roles/{role_id}',
        description: 'Returns a roles details',
        summary: 'Get detailed information about a role',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role_id', description: 'Role ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', description: 'Array of defined roles', type: 'array', items: new OA\Items(ref: '#/components/schemas/RoleResource')),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getDetail(int $role_id)
    {
        $role = Role::with('permissions', 'users', 'squads')
            ->findOrFail($role_id);

        return RoleResource::make($role);
    }

    #[OA\Post(
        path: '/api/v2/roles',
        description: 'Creates a role',
        summary: 'Create a new SeAT role',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['title'],
                    properties: [
                        new OA\Property(property: 'title', description: 'The new role name', type: 'string'),
                        new OA\Property(property: 'description', description: 'Base64 encoded new role logo', type: 'string'),
                        new OA\Property(property: 'permissions', description: 'A list of the permissions which have to be attached to the role.', type: 'array', items: new OA\Items(description: 'A permission name', type: 'string')),
                    ]
                )
            )
        ),
        tags: ['Roles'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/CreateRole', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(
                response: 422,
                description: 'Unprocessable Entity',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', description: 'The readable error message', type: 'string'),
                        new OA\Property(
                            property: 'errors',
                            description: 'Detailed information related to the encountered error',
                            properties: [
                                new OA\Property(
                                    property: 'title',
                                    description: 'The field for which the error has been encountered',
                                    type: 'array',
                                    items: new OA\Items(description: 'A list of the encountered error for this field', type: 'string')
                                ),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function postNew(NewRole $request): RoleResource
    {
        $role = new Role([
            'title' => $request->input('title'),
        ]);

        if ($request->has('description'))
            $role->description = $request->input('description');

        if ($request->has('logo'))
            $role->logo = $request->input('logo');

        $role->save();

        if ($request->has('permissions'))
            $this->giveRolePermissions($role->id, $request->input('permissions'), false);

        $role = Role::find($role->id);

        return RoleResource::make($role);
    }

    #[OA\Patch(
        path: '/api/v2/roles/{role_id}',
        description: 'Edit a role',
        summary: 'Edit an existing SeAT role',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'title', description: 'The new role name', type: 'string'),
                        new OA\Property(property: 'description', description: 'The new role description', type: 'string'),
                        new OA\Property(property: 'logo', description: 'Base64 encoded new role logo', type: 'string', format: 'byte'),
                    ]
                )
            )
        ),
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role_id', description: 'Role ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', ref: '#/components/schemas/RoleResource', type: 'object')])),
            new OA\Response(response: 304, description: 'Your request did not apply any change'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(
                response: 422,
                description: 'Unprocessable Entity',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', description: 'The readable error message', type: 'string'),
                        new OA\Property(
                            property: 'errors',
                            description: 'Detailed information related to the encountered error',
                            properties: [
                                new OA\Property(
                                    property: 'title',
                                    description: 'The field for which the error has been encountered',
                                    type: 'array',
                                    items: new OA\Items(description: 'A list of the encountered error for this field', type: 'string')
                                ),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function patch(EditRole $request, int $role_id): JsonResponse|RoleResource
    {
        $role = $this->getRole($role_id);

        if ($request->has('title'))
            $role->title = $request->input('title');

        if ($request->has('description'))
            $role->description = $request->input('description');

        if ($request->has('logo'))
            $role->logo = $request->input('logo');

        if ($role->isDirty()) {
            $role->save();

            return RoleResource::make($role);
        }

        return response()->json('', 304);
    }

    #[OA\Delete(
        path: '/api/v2/roles/{role_id}',
        description: 'Deletes a role',
        summary: 'Delete a SeAT role',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role_id', description: 'Role ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function deleteRole($role_id): JsonResponse
    {
        Role::findOrFail($role_id);

        $this->removeRole($role_id);

        return response()->json(true);
    }

    #[OA\Post(
        path: '/api/v2/roles/members',
        description: 'Grantes a role',
        summary: 'Grant a user a SeAT role',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['user_id', 'role_id'],
                    properties: [
                        new OA\Property(property: 'user_id', description: 'The user identifier', type: 'integer', minimum: 1),
                        new OA\Property(property: 'role_id', description: 'The role identifier', type: 'integer', minimum: 1),
                    ]
                )
            )
        ),
        tags: ['Roles'],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function postGrantUserRole(Request $request): JsonResponse
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id|numeric',
            'role_id' => 'required|exists:roles,id|numeric',
        ]);

        $this->giveUserRole($request->input('user_id'), $request->input('role_id'));

        return response()->json(true);
    }

    #[OA\Delete(
        path: '/api/v2/roles/members/{user_id}/{role_id}',
        description: 'Revokes a role',
        summary: 'Revoke a SeAT role from an user',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'role_id', description: 'Role ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function deleteRevokeUserRole($user_id, $role_id): JsonResponse
    {

        $this->removeUserFromRole($user_id, $role_id);

        return response()->json(true);
    }
}
