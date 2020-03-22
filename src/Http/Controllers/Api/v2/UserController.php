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
class UserController extends ApiController
{
    /**
     * @OA\Get(
     *      path="/v2/users",
     *      tags={"Users"},
     *      summary="Get a list of users, associated character id's and group ids",
     *      description="Returns list of users",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/User")
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
     * )
     *
     * @OA\Get(
     *      path="/v2/users/{user_id}",
     *      tags={"Users"},
     *      summary="Get group id's and associated character_id's for a user",
     *      description="Returns a user",
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
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#/components/schemas/User"
     *              ),
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     * )
     *
     * @param null $user_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getUsers($user_id = null)
    {

        if (! is_null($user_id))
            return new UserResource(User::findOrFail($user_id));

        return UserResource::collection(User::with('group')->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/users/groups",
     *      tags={"Users"},
     *      summary="Get a list of groups with their associated character_id's",
     *      description="Returns list of groups",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#definitions/Group")
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @OA\Get(
     *      path="/v2/users/groups/{group_id}",
     *      tags={"Users"},
     *      summary="Get a group with its associated character_id's",
     *      description="Returns a group",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="group_id",
     *          description="Group id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#definitions/Group"
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
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
     * @OA\Get(
     *      path="/v2/users/configured-scopes",
     *      tags={"Users"},
     *      summary="Get a list of the scopes configured for this instance",
     *      description="Returns list of scopes",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              description="The scope list requested by the SeAT instance",
     *              @OA\Items(type="string")
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
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
     * @OA\Post(
     *      path="/v2/users/",
     *      tags={"Users"},
     *      summary="Create a new SeAT user",
     *      description="Creates a new SeAT user",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"user_id", "name", "character_owner_hash", "refresh_token", "scopes"},
     *                  @OA\Property(
     *                      property="user_id",
     *                      type="integer",
     *                      format="int64",
     *                      minimum=90000000,
     *                      description="Eve Online Character ID"
     *                  ),
     *                  @OA\Property(
     *                      property="group_id",
     *                      type="integer",
     *                      minimum=1,
     *                      description="The SeAT group id. If ommited, a new group will be created"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Eve Online Character Name"
     *                  ),
     *                  @OA\Property(
     *                      property="active",
     *                      type="boolean",
     *                      description="Set the SeAT account state. Default is true"
     *                  ),
     *                  @OA\Property(
     *                      property="character_owner_hash",
     *                      type="string",
     *                      description="Eve Online account character hash"
     *                  ),
     *                  @OA\Property(
     *                      property="refresh_token",
     *                      type="string",
     *                      description="A refresh token for the account"
     *                  ),
     *                  @OA\Property(
     *                      property="scopes",
     *                      type="array",
     *                      @OA\Items(type="string"),
     *                      description="ESI scopes as array that are valid for the refresh token"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
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
     * @OA\Delete(
     *      path="/v2/users/{user_id}",
     *      tags={"Users"},
     *      summary="Delete a SeAT user",
     *      description="Deletes a user",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="user_id",
     *          description="A SeAT user id",
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
