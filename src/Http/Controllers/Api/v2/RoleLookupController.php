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

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Seat\Web\Models\User;

class RoleLookupController extends ApiController
{
    #[OA\Get(
        path: '/api/v2/roles/query/permissions',
        description: 'Returns a list of permissions',
        summary: 'Get the available SeAT permissions',
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
                    description: 'Permissions list',
                    properties: [
                        new OA\Property(
                            property: 'scope',
                            description: 'Permissions for the given scope where field name is scope',
                            type: 'array',
                            items: new OA\Items(description: 'Permission', type: 'string')
                        ),
                    ], type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getPermissions(): JsonResponse
    {
        $permissions = collect(config('seat.permissions'))->map(function ($item, $scope) {
            return collect($item)->map(function ($sub_item, $name) {
                return $name;
            })->values();
        });

        return response()->json($permissions);
    }

    #[OA\Get(
        path: '/api/v2/roles/query/role-check/{user_id}/{role_name}',
        description: 'Returns a boolean',
        summary: 'Check if a user has a role',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'user_id', description: 'User ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'role_name', description: 'SeAT Role name', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getRoleCheck(int $user_id, string $role_name): JsonResponse
    {

        $user = User::findOrFail($user_id);

        return response()->json($user->roles->where('title', $role_name)->isNotEmpty());
    }

    #[OA\Get(
        path: '/api/v2/roles/query/permission-check/{user_id}/{permission_name}',
        description: 'Returns a boolean',
        summary: 'Check if a user has a permission',
        security: [
            [
                'ApiKeyAuth' => [],
            ],
        ],
        tags: ['Roles'],
        parameters: [
            new OA\Parameter(name: 'user_id', description: 'User ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'permission_name', description: 'SeAT Permission name', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getPermissionCheck(int $user_id, string $permission_name): JsonResponse
    {

        $user = User::findOrFail($user_id);

        return response()->json($user->can($permission_name));
    }
}
