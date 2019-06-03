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
use Illuminate\Routing\Controller;
use Seat\Api\Http\Validation\RenameRole;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\Acl\Role;

/**
 * Class RoleController.
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class RoleController extends Controller
{
    use AccessManager;
    use ValidatesRequests;

    /**
     * @SWG\Get(
     *      path="/roles",
     *      tags={"Roles"},
     *      summary="Get the roles configured within SeAT",
     *      description="Returns a list of roles",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              description="Array of defined roles",
     *              @SWG\Items(
     *                  type="object",
     *                  description="Role",
     *                  @SWG\Property(
     *                      type="integer",
     *                      minimum=1,
     *                      property="id",
     *                      description="The unique identifier of the role"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      property="title",
     *                      description="The name of the role"
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
    public function getIndex()
    {

        $roles = Role::all();

        return response()->json($roles);
    }

    /**
     * @SWG\Get(
     *      path="/roles/{role_id}",
     *      tags={"Roles"},
     *      summary="Get detailed information about a role",
     *      description="Returns a roles details",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="role_id",
     *          description="Role id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              description="Role",
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="id",
     *                  description="The unique identifier of the role"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="title",
     *                  description="The name of the role"
     *              ),
     *              @SWG\Property(
     *                  type="array",
     *                  property="groups",
     *                  description="Attached user relationships",
     *                  @SWG\Items(
     *                      type="object",
     *                      description="User relationship",
     *                      @SWG\Property(
     *                          type="integer",
     *                          minimum=1,
     *                          property="id",
     *                          description="The unique identifier of the relationship"
     *                      ),
     *                      @SWG\Property(
     *                          type="string",
     *                          format="date-time",
     *                          property="created_at",
     *                          description="The creation date-time of the relationship"
     *                      ),
     *                      @SWG\Property(
     *                          type="string",
     *                          format="date-time",
     *                          property="updated_at",
     *                          description="The last update date-time of the relationship"
     *                      ),
     *                      @SWG\Property(
     *                          type="object",
     *                          property="pivot",
     *                          description="The association keys between user relationship and role",
     *                          @SWG\Property(
     *                              type="integer",
     *                              minimum=1,
     *                              property="role_id",
     *                              description="The role identifier"
     *                          ),
     *                          @SWG\Property(
     *                              type="integer",
     *                              minimum=1,
     *                              property="group_id",
     *                              description="The user relationship identifier"
     *                          )
     *                      )
     *                  )
     *              ),
     *              @SWG\Property(
     *                  type="array",
     *                  property="permissions",
     *                  description="A list of permissions object",
     *                  @SWG\Items(
     *                      type="object",
     *                      description="Permission",
     *                      @SWG\Property(
     *                          type="integer",
     *                          minimum=1,
     *                          property="id",
     *                          description="The unique identifier of permission"
     *                      ),
     *                      @SWG\Property(
     *                          type="string",
     *                          property="title",
     *                          description="The permission name"
     *                      ),
     *                      @SWG\Property(
     *                          type="object",
     *                          property="pivot",
     *                          description="The association keys between the role and permission",
     *                          @SWG\Property(
     *                              type="integer",
     *                              minimum=1,
     *                              property="role_id",
     *                              description="The role identifier"
     *                          ),
     *                          @SWG\Property(
     *                              type="integer",
     *                              minimum=1,
     *                              property="permission_id",
     *                              description="The permission identifier"
     *                          ),
     *                          @SWG\Property(
     *                              type="boolean",
     *                              property="not",
     *                              description="True if the permission is negated - meaning role does not have it"
     *                          )
     *                      )
     *                  )
     *              ),
     *              @SWG\Property(
     *                  type="array",
     *                  property="affiliations",
     *                  description="A list of affiliated entities (character or corporation)",
     *                  @SWG\Items(
     *                      type="object",
     *                      description="Affiliation",
     *                      @SWG\Property(
     *                          type="integer",
     *                          minimum=1,
     *                          property="id",
     *                          description="The affiliation identifier"
     *                      ),
     *                      @SWG\Property(
     *                          type="integer",
     *                          format="int64",
     *                          minimum=0,
     *                          property="affiliation",
     *                          description="The entity ID to which affiliation is related (0 if all)"
     *                      ),
     *                      @SWG\Property(
     *                          type="string",
     *                          enum={"corp", "char"},
     *                          property="type",
     *                          description="Determine the type of entity - char for Character - corp for Corporation"
     *                      ),
     *                      @SWG\Property(
     *                          type="object",
     *                          property="pivot",
     *                          description="The association keys between the role and an entity",
     *                          @SWG\Property(
     *                              type="integer",
     *                              property="role_id",
     *                              description="The role identifier"
     *                          ),
     *                          @SWG\Property(
     *                              type="integer",
     *                              format="int64",
     *                              property="affiliation_id",
     *                              description="The affiliated entity identifier"
     *                          ),
     *                          @SWG\Property(
     *                              type="boolean",
     *                              property="not",
     *                              description="Determine if the affiliation is negated (meaning excluded)"
     *                          )
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
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
     * @SWG\Post(
     *      path="/roles",
     *      tags={"Roles"},
     *      summary="Create a new SeAT role",
     *      description="Creates a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              required={"title"},
     *              type="object",
     *              @SWG\Property(
     *                  type="string",
     *                  property="title",
     *                  description="The new group name"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="role_id",
     *                  description="The newly created role identifier"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=422, description="Unprocessable Entity",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  type="string",
     *                  property="message",
     *                  description="The readable error message"
     *              ),
     *              @SWG\Property(
     *                  type="object",
     *                  property="errors",
     *                  description="Detailed information related to the encountered error",
     *                  @SWG\Property(
     *                      type="array",
     *                      property="title",
     *                      description="The field for which the error has been encountered",
     *                      @SWG\Items(
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
     * @SWG\Put(
     *      path="/roles/{role_id}",
     *      tags={"Roles"},
     *      summary="Rename an existing SeAT role",
     *      description="Rename a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="role_id",
     *          description="Role ID",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              required={"title"},
     *              type="object",
     *              @SWG\Property(
     *                  type="string",
     *                  property="title",
     *                  description="The new group name"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=422, description="Unprocessable Entity",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  type="string",
     *                  property="message",
     *                  description="The readable error message"
     *              ),
     *              @SWG\Property(
     *                  type="object",
     *                  property="errors",
     *                  description="Detailed information related to the encountered error",
     *                  @SWG\Property(
     *                      type="array",
     *                      property="title",
     *                      description="The field for which the error has been encountered",
     *                      @SWG\Items(
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
     * @SWG\Post(
     *      path="/roles/affiliation/character",
     *      tags={"Roles"},
     *      summary="Add a character affiliation to a SeAT role",
     *      description="Adds a character affiliation",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              required={"role_id", "character_id"},
     *              type="object",
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="role_id",
     *                  description="Role id"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=0,
     *                  property="character_id",
     *                  description="Character id"
     *              ),
     *              @SWG\Property(
     *                  type="boolean",
     *                  property="inverse",
     *                  description="Inverse flag"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
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
     * @SWG\Post(
     *      path="/roles/affiliation/corporation",
     *      tags={"Roles"},
     *      summary="Add a corporation affiliation to a SeAT role",
     *      description="Adds a corporation affiliation",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              required={"role_id", "corporation_id"},
     *              type="object",
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="role_id",
     *                  description="Role id"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=0,
     *                  property="corporation_id",
     *                  description="Corporation id"
     *              ),
     *              @SWG\Property(
     *                  type="boolean",
     *                  property="inverse",
     *                  description="Inverse flag"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
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
     * @SWG\Delete(
     *      path="/roles/{role_id}",
     *      tags={"Roles"},
     *      summary="Delete a SeAT role",
     *      description="Deletes a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="role_id",
     *          description="Role id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
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
     * @SWG\Post(
     *      path="/roles/groups",
     *      tags={"Roles"},
     *      summary="Grant a user relationship a SeAT role",
     *      description="Grants a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              required={"group_id", "role_id"},
     *              type="object",
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="group_id",
     *                  description="The user relationship identifier"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="role_id",
     *                  description="The role identifier"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
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
     * @SWG\Delete(
     *      path="/roles/groups/{group_id}/{role_id}",
     *      tags={"Roles"},
     *      summary="Revoke a SeAT role from a user relationship",
     *      description="Revokes a role",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="group_id",
     *          description="User relationship identifier",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="role_id",
     *          description="Role id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
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
