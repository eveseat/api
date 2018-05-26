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

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Seat\Api\Http\Resources\GroupResource;
use Seat\Api\Http\Resources\UserResource;
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
    *      security={"ApiKeyAuth"},
    *      @SWG\Response(response=200, description="Successful operation"),
    *      @SWG\Response(response=400, description="Bad request"),
    *      @SWG\Response(response=401, description="Unauthorized"),
    *     )
    *
    * @SWG\Get(
    *      path="/users/{user_id}",
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
    *      path="/users/groups/{group_id}",
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

    /**
    * @SWG\Post(
    *      path="/users/new",
    *      tags={"Users"},
    *      summary="Create or update a user",
    *      description="Creates or Updates Users and refreshTokens",
    *      security={"ApiKeyAuth"},
    *      @SWG\Parameter(
    *          name="user_id",
    *          description="Eve Online Character ID",
    *          required=true,
    *          in="body",
    *          @SWG\Schema(type="integer")
    *      ),
    *     @SWG\Parameter(
    *          name="group_id",
    *          description="Group id. If ommited a new group is created",
    *          required=false,
    *          in="body",
    *          @SWG\Schema(type="integer")
    *      ),
    *     @SWG\Parameter(
    *          name="name",
    *          description="Eve Online Character Name",
    *          required=true,
    *          in="body",
    *          @SWG\Schema(type="string")
    *      ),
    *     @SWG\Parameter(
    *          name="active",
    *          description="Seat Account Active. Default True",
    *          required=false,
    *          in="body",
    *          @SWG\Schema(type="boolean")
    *      ),
    *     @SWG\Parameter(
    *          name="hash",
    *          description="Character Owner Hash",
    *          required=true,
    *          in="body",
    *          @SWG\Schema(type="string")
    *      ),
    *     @SWG\Parameter(
    *          name="refresh_token",
    *          description="A valid Refresh Token",
    *          required=true,
    *          in="body",
    *          @SWG\Schema(type="string")
    *      ),
    *     @SWG\Parameter(
    *          name="scopes",
    *          description="ESI Scopes for refreshToken. Defaults to Seat Scopes",
    *          required=false,
    *          in="body",
    *          @SWG\Schema(type="json")
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
    public function postNew(Request $request)
    {

        $id = $request->input('user_id');
        $group_id = $request->input('group_id');
        $name = $request->input('name');
        $active = $request->input('active');
        $hash = $request->input('hash');
        $new = false;

        if (is_null($user = User::find($id))){
            $user = User::forceCreate([  // id is not fillable
                'id'                   => $id,
                'group_id'             => is_null($group_id) ? Group::create()->id : $group_id,
                'name'                 => $name,
                'active'               => is_null($active) ? true : $active,
                'character_owner_hash' => $hash,
            ]);
            $new = true;
        }
        else{
            $user->group_id = is_null($group_id) ? Group::create()->id : $group_id;
            $user->name = $name;
            $user->active = true;
            $user->character_owner_hash = $hash;
            $user->save();
        }

        $character_id = $request->input('id');
        $refresh_token = $request->input('refresh_token');
        $scopes = $request->input('scopes');
        $expires_on = '1980-01-01 00:00:00';
        $token = '-';

        $userRefreshToken = RefreshToken::firstorCreate(
            ['character_id' => $character_id],
            ['refresh_token'=> $refresh_token,
                'scopes'=> is_null($scopes) ? setting('sso_scopes', true) : $scopes,
                'expires_on'=>$expires_on,
                'token'=>$token, ]
        );

        return response()->json(['OK'=>true, 'new'=>$new, 'group_id'=>$user->group_id, 'scopes'=>$userRefreshToken->scopes]);
    }

    /**
    * @SWG\Post(
    *      path="/users/enable/{user_id}",
    *      tags={"Users"},
    *      summary="Enable a user",
    *      description="Enables a User Account",
    *      security={"ApiKeyAuth"},
    *      @SWG\Parameter(
    *          name="user_id",
    *          description="Eve Online Character ID",
    *          required=true,
    *          in="path",
    *          type="integer"
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

    public function postEnable($user_id=null){
        
        $user = User::find($user_id);
        if (is_null($user)) return response()->json(['OK'=>false]);
        $user->active = true;
        $user->save();
        return response()->json(['OK'=>true]);

    }

    /**
    * @SWG\Post(
    *      path="/users/disable/{user_id}",
    *      tags={"Users"},
    *      summary="Disable User",
    *      description="Disables a User Account",
    *      security={"ApiKeyAuth"},
    *      @SWG\Parameter(
    *          name="user_id",
    *          description="Eve Online Character ID",
    *          required=true,
    *          in="path",
    *          type="integer"
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

    public function postDisable($user_id=null){

        $user = User::find($user_id);
        if (is_null($user)) return response()->json(['OK'=>false]);
        $user->active = false;
        $user->save();
        return response()->json(['OK'=>true]);

    }

}
