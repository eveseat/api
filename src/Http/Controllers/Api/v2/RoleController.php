<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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
use Seat\Api\Http\Validation\RenameRole;
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
     *              type="object",
     *              description="Role",
     *              @OA\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="id",
     *                  description="The unique identifier of the role"
     *              ),
     *              @OA\Property(
     *                  type="string",
     *                  property="title",
     *                  description="The name of the role"
     *              ),
     *              @OA\Property(
     *                  type="array",
     *                  property="groups",
     *                  description="Attached user relationships",
     *                  @OA\Items(
     *                      type="object",
     *                      description="User relationship",
     *                      @OA\Property(
     *                          type="integer",
     *                          minimum=1,
     *                          property="id",
     *                          description="The unique identifier of the relationship"
     *                      ),
     *                      @OA\Property(
     *                          type="string",
     *                          format="date-time",
     *                          property="created_at",
     *                          description="The creation date-time of the relationship"
     *                      ),
     *                      @OA\Property(
     *                          type="string",
     *                          format="date-time",
     *                          property="updated_at",
     *                          description="The last update date-time of the relationship"
     *                      ),
     *                      @OA\Property(
     *                          type="object",
     *                          property="pivot",
     *                          description="The association keys between user relationship and role",
     *                          @OA\Property(
     *                              type="integer",
     *                              minimum=1,
     *                              property="role_id",
     *                              description="The role identifier"
     *                          ),
     *                          @OA\Property(
     *                              type="integer",
     *                              minimum=1,
     *                              property="group_id",
     *                              description="The user relationship identifier"
     *                          )
     *                      )
     *                  )
     *              ),
     *              @OA\Property(
     *                  type="array",
     *                  property="permissions",
     *                  description="A list of permissions object",
     *                  @OA\Items(
     *                      type="object",
     *                      description="Permission",
     *                      @OA\Property(
     *                          type="integer",
     *                          minimum=1,
     *                          property="id",
     *                          description="The unique identifier of permission"
     *                      ),
     *                      @OA\Property(
     *                          type="string",
     *                          property="title",
     *                          description="The permission name"
     *                      ),
     *                      @OA\Property(
     *                          type="object",
     *                          property="pivot",
     *                          description="The association keys between the role and permission",
     *                          @OA\Property(
     *                              type="integer",
     *                              minimum=1,
     *                              property="role_id",
     *                              description="The role identifier"
     *                          ),
     *                          @OA\Property(
     *                              type="integer",
     *                              minimum=1,
     *                              property="permission_id",
     *                              description="The permission identifier"
     *                          ),
     *                          @OA\Property(
     *                              type="boolean",
     *                              property="not",
     *                              description="True if the permission is negated - meaning role does not have it"
     *                          )
     *                      )
     *                  )
     *              ),
     *              @OA\Property(
     *                  type="array",
     *                  property="affiliations",
     *                  description="A list of affiliated entities (character or corporation)",
     *                  @OA\Items(
     *                      type="object",
     *                      description="Affiliation",
     *                      @OA\Property(
     *                          type="integer",
     *                          minimum=1,
     *                          property="id",
     *                          description="The affiliation identifier"
     *                      ),
     *                      @OA\Property(
     *                          type="integer",
     *                          format="int64",
     *                          minimum=0,
     *                          property="affiliation",
     *                          description="The entity ID to which affiliation is related (0 if all)"
     *                      ),
     *                      @OA\Property(
     *                          type="string",
     *                          enum={"corp", "char"},
     *                          property="type",
     *                          description="Determine the type of entity - char for Character - corp for Corporation"
     *                      ),
     *                      @OA\Property(
     *                          type="object",
     *                          property="pivot",
     *                          description="The association keys between the role and an entity",
     *                          @OA\Property(
     *                              type="integer",
     *                              property="role_id",
     *                              description="The role identifier"
     *                          ),
     *                          @OA\Property(
     *                              type="integer",
     *                              format="int64",
     *                              property="affiliation_id",
     *                              description="The affiliated entity identifier"
     *                          ),
     *                          @OA\Property(
     *                              type="boolean",
     *                              property="not",
     *                              description="Determine if the affiliation is negated (meaning excluded)"
     *                          )
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param $role_id
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetail($role_id)
    {

        $role = Role::with('groups', 'permissions', 'affiliations')
            ->where(is_numeric($role_id) ? 'id' : 'title', $role_id)
            ->first();

        return response()->json($role);
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
     *                      description="The new group name"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="role_id",
     *                  description="The newly created role identifier"
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postNew(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|string|unique:roles,title',
        ]);

        $name = $request->input('title');

        $role = $this->addRole($name);

        return response()->json(['role_id' => $role->id]);
    }

    /**
     * @OA\Put(
     *      path="/v2/roles/{role_id}",
     *      tags={"Roles"},
     *      summary="Rename an existing SeAT role",
     *      description="Rename a role",
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
     *                  required={"title"},
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="The new group name"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
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
     * @param RenameRole $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function putRename(RenameRole $request, $role_id)
    {
        $role = $this->getRole($role_id);

        $role->title = $request->input('title');
        $role->save();

        return response()->json(true);
    }

    /**
     * @OA\Post(
     *      path="/v2/roles/affiliation/character",
     *      tags={"Roles"},
     *      summary="Add a character affiliation to a SeAT role",
     *      description="Adds a character affiliation",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"role_id", "character_id"},
     *                  @OA\Property(
     *                      property="role_id",
     *                      type="integer",
     *                      minimum=1,
     *                      description="Role id"
     *                  ),
     *                  @OA\Property(
     *                      property="character_id",
     *                      type="integer",
     *                      minimum=90000000,
     *                      description="Character id"
     *                  ),
     *                  @OA\Property(
     *                      property="inverse",
     *                      type="boolean",
     *                      description="Inverse flag"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddCharacterAffiliation(Request $request)
    {

        $this->validate($request, [
            'role_id'      => 'required|exists:roles,id|numeric|min:2',
            'character_id' => 'required|exists:character_infos,character_id|numeric',
            'inverse'      => 'sometimes|required|boolean',
        ]);

        $this->giveRoleCharacterAffiliation(
            $request->input('role_id'),
            $request->input('character_id'),
            $request->has('inverse') ? $request->input('inverse') : false
        );

        return response()->json(true);
    }

    /**
     * @OA\Post(
     *      path="/v2/roles/affiliation/corporation",
     *      tags={"Roles"},
     *      summary="Add a corporation affiliation to a SeAT role",
     *      description="Adds a corporation affiliation",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"role_id", "corporation_id"},
     *                  @OA\Property(
     *                      property="role_id",
     *                      type="integer",
     *                      minimum=1,
     *                      description="Role id"
     *                  ),
     *                  @OA\Property(
     *                      property="corporation_id",
     *                      type="integer",
     *                      minimum=98000000,
     *                      description="Corporation id"
     *                  ),
     *                  @OA\Property(
     *                      property="inverse",
     *                      type="boolean",
     *                      description="Inverse flag"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddCorporationAffiliation(Request $request)
    {

        $this->validate($request, [
            'role_id'        => 'required|exists:roles,id|numeric|min:2',
            'corporation_id' => 'required|exists:corporation_infos,corporation_id|numeric',
            'inverse'        => 'sometimes|required|boolean',
        ]);

        $this->giveRoleCorporationAffiliation(
            $request->input('role_id'),
            $request->input('corporation_id'),
            $request->has('inverse') ? $request->input('inverse') : false
        );

        return response()->json(true);
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

        $this->removeRole($role_id);

        return response()->json(true);
    }

    /**
     * @OA\Post(
     *      path="/v2/roles/groups",
     *      tags={"Roles"},
     *      summary="Grant a user relationship a SeAT role",
     *      description="Grants a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"group_id", "role_id"},
     *                  @OA\Property(
     *                      property="group_id",
     *                      type="integer",
     *                      minimum=1,
     *                      description="The user relationship identifier"
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
            'group_id' => 'required|exists:groups,id|numeric',
            'role_id' => 'required|exists:roles,id|numeric',
        ]);

        $this->giveGroupRole($request->input('group_id'), $request->input('role_id'));

        return response()->json(true);

    }

    /**
     * @OA\Delete(
     *      path="/v2/roles/groups/{group_id}/{role_id}",
     *      tags={"Roles"},
     *      summary="Revoke a SeAT role from a user relationship",
     *      description="Revokes a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="group_id",
     *          description="User relationship identifier",
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
    public function deleteRevokeGroupRole($group_id, $role_id)
    {

        $this->removeGroupFromRole($group_id, $role_id);

        return response()->json(true);
    }
}
