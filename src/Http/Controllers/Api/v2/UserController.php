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
use Seat\Api\Http\Resources\GroupResource;
use Seat\Api\Http\Resources\UserResource;
use Seat\Api\Http\Validation\NewUser;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\Group;
use Seat\Web\Models\User;

/**
 * Class UserController.
 * @package Seat\Api\Http\Controllers\Api\v2
 */
class UserController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/users",
     *      tags={"Users"},
     *      summary="Get a list of users, associated character id's and group ids",
     *      description="Returns list of users",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  type="array",
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/User")
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @SWG\Get(
     *      path="/users/{user_id}",
     *      tags={"Users"},
     *      summary="Get group id's and assosciated character_id's for a user",
     *      description="Returns a user",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="User id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param null $user_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getUsers($user_id = null)
    {

        if (! is_null($user_id))
            return new UserResource(User::findOrFail($user_id));

        return UserResource::collection(User::all());
    }

    /**
     * @SWG\Get(
     *      path="/users/groups",
     *      tags={"Users"},
     *      summary="Get a list of groups with their associated character_id's",
     *      description="Returns list of groups",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  type="array",
     *                  property="data",
     *                  @SWG\Items(ref="#definitions/Group")
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @SWG\Get(
     *      path="/users/groups/{group_id}",
     *      tags={"Users"},
     *      summary="Get a group with its associated character_id's",
     *      description="Returns a group",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="group_id",
     *          description="Group id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#definitions/Group"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param null $group_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getGroups($group_id = null)
    {

        if (! is_null($group_id))
            return new GroupResource(Group::findOrFail($group_id));

        return GroupResource::collection(Group::all());
    }

    /**
     * @SWG\Get(
     *      path="/users/configured-scopes",
     *      tags={"Users"},
     *      summary="Get a list of the scopes configured for this instance",
     *      description="Returns list of scopes",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              description="The scope list requested by the SeAT instance",
     *              @SWG\Items(type="string")
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getConfiguredScopes()
    {

        return response()
            ->json(setting('sso_scopes', true));
    }

    /**
     * @SWG\Post(
     *      path="/users/",
     *      tags={"Users"},
     *      summary="Create a new SeAT user",
     *      description="Creates a new SeAT user",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          type="object",
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              required={"user_id", "name", "character_owner_hash", "refresh_token", "scopes"},
     *              @SWG\Property(
     *                  type="integer",
     *                  format="int64",
     *                  minimum=90000000,
     *                  property="user_id",
     *                  description="Eve Online Character ID"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="group_id",
     *                  description="The SeAT group id. If ommited, a new group will be created"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="name",
     *                  description="Eve Online Character Name"
     *              ),
     *              @SWG\Property(
     *                  type="boolean",
     *                  property="active",
     *                  description="Set the SeAT account state. Default is true"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="character_owner_hash",
     *                  description="Eve Online account character hash"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  property="refresh_token",
     *                  description="A refresh token for the account"
     *              ),
     *              @SWG\Property(
     *                  type="array",
     *                  @SWG\Items(type="string"),
     *                  property="scopes",
     *                  description="ESI scopes as array that are valid for the refresh token"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param \Seat\Api\Http\Validation\NewUser $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function postNewUser(NewUser $request)
    {

        $user = User::forceCreate([  // Only because I don't want to set id as fillable
            'id'                   => $request->get('user_id'),
            'group_id'             => $request->get('group_id') ?? Group::create()->id,
            'name'                 => $request->get('name'),
            'active'               => $request->get('active') ?? true,
            'character_owner_hash' => $request->get('character_owner_hash'),
        ])->refresh_token()->save(new RefreshToken([
            'refresh_token' => $request->get('refresh_token'),
            'scopes'        => $request->get('scopes'),
            'token'         => '-',
            'expires_on'    => carbon('now'),
        ]));

        // Log the new account creation
        event('security.log', [
            'Created a new account for ' . $request->get('name') . ' via an API call.',
            'authentication',
        ]);

        return response()->json($user);
    }

    /**
     * @SWG\Delete(
     *      path="/users/{user_id}",
     *      tags={"Users"},
     *      summary="Delete a SeAT user",
     *      description="Deletes a user",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="A SeAT user id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $user_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(int $user_id)
    {

        User::findOrFail($user_id)->delete();

        return response()->json();
    }
}
