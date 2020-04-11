<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

use Seat\Web\Models\User;

/**
 * Class RoleLookupController.
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class RoleLookupController extends ApiController
{
    /**
     * @OA\Get(
     *      path="/v2/roles/query/permissions",
     *      tags={"Roles"},
     *      summary="Get the available SeAT permissions",
     *      description="Returns a list of permissions",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              description="Permissions list",
     *              @OA\Property(
     *                  type="array",
     *                  property="scope",
     *                  description="Permissions for the given scope where field name is scope",
     *                  @OA\Items(
     *                      type="string",
     *                      description="Permission"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissions()
    {
        $permissions = collect(config('seat.permissions'))->map(function ($item, $scope) {
            return collect($item)->map(function ($sub_item, $name) {
                return $name;
            })->values();
        });

        return response()->json($permissions);
    }

    /**
     * @OA\Get(
     *      path="/v2/roles/query/role-check/{user_id}/{role_name}",
     *      tags={"Roles"},
     *      summary="Check if a user has a role",
     *      description="Returns a boolean",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Parameter(
     *          name="role_name",
     *          description="SeAT Role name",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int    $user_id
     * @param string $role_name
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoleCheck(int $user_id, string $role_name)
    {

        $user = User::findOrFail($user_id);

        return response()->json($user->hasRole($role_name));
    }

    /**
     * @OA\Get(
     *      path="/v2/roles/query/permission-check/{user_id}/{permission_name}",
     *      tags={"Roles"},
     *      summary="Check if a user has a role",
     *      description="Returns a boolean",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Parameter(
     *          name="permission_name",
     *          description="SeAT Permission name",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int    $user_id
     * @param string $permission_name
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionCheck(int $user_id, string $permission_name)
    {

        $user = User::findOrFail($user_id);

        return response()->json($user->has($permission_name, false));
    }
}
