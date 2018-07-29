<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

use Illuminate\Routing\Controller;
use Seat\Web\Models\User;

/**
 * Class RoleLookupController.
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class RoleLookupController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/roles/query/permissions",
     *      tags={"Roles"},
     *      summary="Get the available SeAT permissions",
     *      description="Returns a list of permissions",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              description="Permissions list",
     *              @SWG\Property(
     *                  type="array",
     *                  property="scope",
     *                  description="Permissions for the given scope where field name is scope",
     *                  @SWG\Items(
     *                      type="string",
     *                      description="Permission"
     *                  )
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissions()
    {

        return response()->json(config('web.permissions'));
    }

    /**
     * @SWG\Get(
     *      path="/roles/query/role-check/{character_id}/{role_name}",
     *      tags={"Roles"},
     *      summary="Check if a user has a role",
     *      description="Returns a boolean",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="role_name",
     *          description="SeAT Role name",
     *          required=true,
     *          type="string",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int    $character_id
     * @param string $role_name
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoleCheck(int $character_id, string $role_name)
    {

        $user = User::findOrFail($character_id);

        return response()->json($user->hasRole($role_name));
    }

    /**
     * @SWG\Get(
     *      path="/roles/query/permission-check/{character_id}/{permission_name}",
     *      tags={"Roles"},
     *      summary="Check if a user has a role",
     *      description="Returns a boolean",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="permission_name",
     *          description="SeAT Permission name",
     *          required=true,
     *          type="string",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int    $character_id
     * @param string $permission_name
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionCheck(int $character_id, string $permission_name)
    {

        $user = User::findOrFail($character_id);

        return response()->json($user->has($permission_name, false));
    }
}
