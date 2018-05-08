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
     *      path="/users/get",
     *      tags={"Users"},
     *      summary="Get a list of users, associated character id's and group ids",
     *      description="Returns list of users",
     *      security={"ApiKeyAuth"},
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @SWG\Get(
     *      path="/users/get/{user_id}",
     *      tags={"Users"},
     *      summary="Get group id's and assosciated character_id's for a user",
     *      description="Returns a user",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="User id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
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
     *      security={"ApiKeyAuth"},
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @SWG\Get(
     *      path="/users/get/{group_id}",
     *      tags={"Users"},
     *      summary="Get a group with its associated character_id's",
     *      description="Returns a group",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="group_id",
     *          description="Group id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
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
}
