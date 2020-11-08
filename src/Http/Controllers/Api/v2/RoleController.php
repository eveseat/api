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

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Seat\Api\Http\Resources\RoleResource;
use Seat\Api\Http\Validation\EditRole;
use Seat\Api\Http\Validation\NewRole;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\Acl\Role;

/**
 * Class RoleController.
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class RoleController extends ApiController
{
    use AccessManager;
    use ValidatesRequests;

    /**
     * @OA\Get(
     *      path="/v2/roles",
     *      tags={"Roles"},
     *      summary="Get the roles configured within SeAT",
     *      description="Returns a list of roles",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  description="Array of defined roles",
     *                  @OA\Items(ref="#/components/schemas/Role")
     *              ),
     *              @OA\Property(
     *                  property="links",
     *                  ref="#/components/schemas/ResourcePaginatedLinks"
     *              ),
     *              @OA\Property(
     *                  property="meta",
     *                  ref="#/components/schemas/ResourcePaginatedMetadata"
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getIndex()
    {

        return Resource::collection(Role::paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/roles/{role_id}",
     *      tags={"Roles"},
     *      summary="Get detailed information about a role",
     *      description="Returns a roles details",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="role_id",
     *          description="Role id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#/components/schemas/RoleResource"
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $role_id
     * @return \Seat\Api\Http\Resources\RoleResource
     */
    public function getDetail(int $role_id)
    {
        $role = Role::with('permissions', 'users', 'squads')
            ->findOrFail($role_id);

        return RoleResource::make($role);
    }

    /**
     * @OA\Post(
     *      path="/v2/roles",
     *      tags={"Roles"},
     *      summary="Create a new SeAT role",
     *      description="Creates a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"title"},
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="The new role name"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="The new role description"
     *                  ),
     *                  @OA\Property(
     *                      property="logo",
     *                      type="string",
     *                      format="byte",
     *                      description="Base64 encoded new role logo"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#/components/schemas/CreateRole"
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=422, description="Unprocessable Entity",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  type="string",
     *                  property="message",
     *                  description="The readable error message"
     *              ),
     *              @OA\Property(
     *                  type="object",
     *                  property="errors",
     *                  description="Detailed information related to the encountered error",
     *                  @OA\Property(
     *                      type="array",
     *                      property="title",
     *                      description="The field for which the error has been encountered",
     *                      @OA\Items(
     *                          type="string",
     *                          description="A list of the encountered error for this field"
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *     )
     *
     * @param \Seat\Api\Http\Validation\NewRole $request
     * @return \Seat\Api\Http\Resources\RoleResource
     */
    public function postNew(NewRole $request)
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

    /**
     * @OA\Patch(
     *      path="/v2/roles/{role_id}",
     *      tags={"Roles"},
     *      summary="Edit an existing SeAT role",
     *      description="Edit a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="role_id",
     *          description="Role ID",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="The new role name"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="The new role description"
     *                  ),
     *                  @OA\Property(
     *                      property="logo",
     *                      type="string",
     *                      format="byte",
     *                      description="Base64 encoded new role logo"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#/components/schemas/RoleResource"
     *              )
     *          )
     *      ),
     *      @OA\Response(response=304, description="Your request didn't apply any change"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=422, description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="string",
     *                  property="message",
     *                  description="The readable error message"
     *              ),
     *              @OA\Property(
     *                  type="object",
     *                  property="errors",
     *                  description="Detailed information related to the encountered error",
     *                  @OA\Property(
     *                      type="array",
     *                      property="title",
     *                      description="The field for which the error has been encountered",
     *                      @OA\Items(
     *                          type="string",
     *                          description="A list of the encountered error for this field"
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *     )
     *
     * @param \Seat\Api\Http\Validation\EditRole $request
     * @param int $role_id
     * @return \Illuminate\Http\JsonResponse|\Seat\Api\Http\Resources\RoleResource
     */
    public function patch(EditRole $request, int $role_id)
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

    /**
     * @OA\Delete(
     *      path="/v2/roles/{role_id}",
     *      tags={"Roles"},
     *      summary="Delete a SeAT role",
     *      description="Deletes a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="role_id",
     *          description="Role id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param $role_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRole($role_id)
    {
        Role::findOrFail($role_id);

        $this->removeRole($role_id);

        return response()->json(true);
    }

    /**
     * @OA\Post(
     *      path="/v2/roles/members",
     *      tags={"Roles"},
     *      summary="Grant a user a SeAT role",
     *      description="Grants a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"user_id", "role_id"},
     *                  @OA\Property(
     *                      property="user_id",
     *                      type="integer",
     *                      minimum=1,
     *                      description="The user identifier"
     *                  ),
     *                  @OA\Property(
     *                      property="role_id",
     *                      type="integer",
     *                      minimum=1,
     *                      description="The role identifier"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postGrantUserRole(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id|numeric',
            'role_id' => 'required|exists:roles,id|numeric',
        ]);

        $this->giveUserRole($request->input('user_id'), $request->input('role_id'));

        return response()->json(true);
    }

    /**
     * @OA\Delete(
     *      path="/v2/roles/members/{user_id}/{role_id}",
     *      tags={"Roles"},
     *      summary="Revoke a SeAT role from an user",
     *      description="Revokes a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User identifier",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Parameter(
     *          name="role_id",
     *          description="Role id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param $user_id
     * @param $role_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRevokeUserRole($user_id, $role_id)
    {

        $this->removeUserFromRole($user_id, $role_id);

        return response()->json(true);
    }
}
