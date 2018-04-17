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

namespace Seat\Api\Http\Controllers\Api\v1;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Seat\Eveapi\Models\Character\CharacterSheet;
use Seat\Eveapi\Models\Corporation\CorporationSheet;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {

        $roles = Role::all();

        return response()->json($roles);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetail($id)
    {

        $role = Role::with('users', 'permissions', 'affiliations')
            ->where(is_numeric($id) ? 'id' : 'title', $id)
            ->first();

        return response()->json($role);
    }

    /**
     * Create a new Role.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postNew(Request $request)
    {

        $name = $request->input('name');

        $this->addRole($name);

        return response()->json(true);
    }

    /**
     * Append a character affiliation to an existing role.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddCharacterAffiliation(Request $request)
    {

        // role_id and character_id are required, but disallow superuser role edition
        $this->validate($request, [
            'role_id'      => 'required|numeric|min:2',
            'character_id' => 'required|numeric',
            'inverse'      => 'sometimes|required|boolean',
        ]);

        $role_id = $request->input('role_id');
        $character_id = $request->input('character_id');

        // make inverse optional and set false as default value
        $inverse = $request->has('inverse') ? $request->input('inverse') : false;

        try {

            Role::findOrFail($role_id);
            CharacterSheet::findOrFail($character_id);

        } catch (ModelNotFoundException $e) {

            return response()->json(['msg' => $e->getMessage()], 404);
        }

        $this->giveRoleCharacterAffiliation($role_id, $character_id, $inverse);

        return response()->json(true);
    }

    /**
     * Append a corporation affiliation to an existing role.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddCorporationAffiliation(Request $request)
    {

        // role_id and character_id are required, but disallow superuser role edition
        $this->validate($request, [
            'role_id'        => 'required|numeric|min:2',
            'corporation_id' => 'required|numeric',
            'inverse'        => 'sometimes|required|boolean',
        ]);

        $role_id = $request->input('role_id');
        $corporation_id = $request->input('corporation_id');

        // make inverse optional and set false as default value
        $inverse = $request->has('inverse') ? $request->input('inverse') : false;

        Role::findOrFail($role_id);
        CorporationSheet::findOrFail($corporation_id);

        $this->giveRoleCorporationAffiliation($role_id, $corporation_id, $inverse);

        return response()->json(true);
    }

    /**
     * Delete a role.
     *
     * @param $role_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRemove($role_id)
    {

        $this->removeRole($role_id);

        return response()->json(true);
    }

    /**
     * Give a user a role.
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
     * Remove a user from a role.
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
