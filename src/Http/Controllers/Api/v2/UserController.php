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

use OpenApi\Annotations as OA;
use Seat\Api\Http\Resources\UserResource;
use Seat\Api\Http\Validation\NewUser;
use Seat\Web\Models\User;

/**
 * Class UserController.
 *
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Seat\Api\Http\Resources\UserResource
     */
    public function getUsers()
    {
        return UserResource::collection(User::paginate());
    }

    /**
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
     * @param  int  $user_id
     * @return \Seat\Api\Http\Resources\UserResource
     */
    public function show(int $user_id)
    {
        return UserResource::make(User::findOrFail($user_id));
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
     * @return \Illuminate\Http\JsonResponse
     *
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
     *                  required={"name", "main_character_id"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Eve Online (main) Character Name"
     *                  ),
     *                  @OA\Property(
     *                      property="active",
     *                      type="boolean",
     *                      description="Set the SeAT account state. Default is true"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      format="email",
     *                      description="A contact email address for the created user"
     *                  ),
     *                  @OA\Property(
     *                      property="main_character_id",
     *                      type="integer",
     *                      format="int64",
     *                      minimum=90000000,
     *                      description="Eve Online main Character ID"
     *                  )
     *              )
     *          )
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
     *     )
     *
     * @param  \Seat\Api\Http\Validation\NewUser  $request
     * @return \Illuminate\Http\JsonResponse|\Seat\Api\Http\Resources\UserResource
     */
    public function postNewUser(NewUser $request)
    {
        if ($request->get('name') == 'admin')
            return response()->json('You cannot create this user.', 403);

        $user = new User();
        $user->name = $request->get('name');
        $user->main_character_id = $request->get('main_character_id');
        $user->active = $request->get('active') ?? true;

        $user->save();

        if ($request->has('email'))
            setting(['email', $request->get('email'), $user->id], false);

        // Log the new account creation
        event('security.log', [
            'Created a new account for ' . $request->get('name') . ' via an API call.',
            'authentication',
        ]);

        return UserResource::make($user);
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
     *      @OA\Response(response=403, description="Unauthorized"),
     *     )
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(int $user_id)
    {

        $user = User::findOrFail($user_id);

        if ($user->name == 'admin')
            return response()->json('You cannot delete this user.', 403);

        $user->delete();

        return response()->json();
    }
}
