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

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
     *      security={"ApiKeyAuth"},
     *      @SWG\Response(response=200, description="Successful operation"),
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
     *      path="/roles/detail/{role_id}",
     *      tags={"Roles"},
     *      summary="Get detailed information about a role",
     *      description="Returns a roles details",
     *      security={"ApiKeyAuth"},
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
     * @return \Illuminate\Http\Response
     */
    public function getDetail($role_id)
    {

        $role = Role::with('users', 'permissions', 'affiliations')
            ->where(is_numeric($role_id) ? 'id' : 'title', $role_id)
            ->first();

        return response()->json($role);
    }

    /**
     * @SWG\Post(
     *      path="/roles/new",
     *      tags={"Roles"},
     *      summary="Create a new SeAT role",
     *      description="Creates a role",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="name",
     *          description="A role name",
     *          required=true,
     *          type="string",
     *          in="body",
     *          @SWG\Schema(type="string")
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postNew(Request $request)
    {

        $name = $request->input('name');

        $role = $this->addRole($name);

        return response()->json(['role_id' => $role->id]);
    }

    /**
     * @SWG\Post(
     *      path="/roles/affiliation/character",
     *      tags={"Roles"},
     *      summary="Add a character affiliation to a SeAT role",
     *      description="Adds a character affiliation",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="role_id",
     *          description="Role id",
     *          required=true,
     *          type="integer",
     *          in="body",
     *          @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="body",
     *          @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Parameter(
     *          name="inverse",
     *          description="Inverse flag",
     *          required=false,
     *          type="boolean",
     *          in="body",
     *          @SWG\Schema(type="boolean")
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
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="role_id",
     *          description="Role id",
     *          required=true,
     *          type="integer",
     *          in="body",
     *          @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="body",
     *          @SWG\Schema(type="integer")
     *      ),
     *      @SWG\Parameter(
     *          name="inverse",
     *          description="Inverse flag",
     *          required=false,
     *          type="boolean",
     *          in="body",
     *          @SWG\Schema(type="boolean")
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
     *      path="/roles/delete/{role_id}",
     *      tags={"Roles"},
     *      summary="Delete a SeAT role",
     *      description="Deletes a role",
     *      security={"ApiKeyAuth"},
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
     * @SWG\Get(
     *      path="/roles/grant-user-role/{user_id}/{role_id}",
     *      tags={"Roles"},
     *      summary="Grant a user a SeAT role",
     *      description="Grants a role",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="User id",
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
    public function getGrantUserRole($user_id, $role_id)
    {

        $this->giveUserRole($user_id, $role_id);

        return response()->json(true);

    }

    /**
     * @SWG\Get(
     *      path="/roles/revoke-user-role/{user_id}/{role_id}",
     *      tags={"Roles"},
     *      summary="Revoke a SeAT role from a user",
     *      description="Revokes a role",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="User id",
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
    public function getRevokeUserRole($user_id, $role_id)
    {

        $this->removeUserFromRole($user_id, $role_id);

        return response()->json(true);
    }
}
