<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Api\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Seat\Web\Acl\Pillow;
use Seat\Web\Models\Acl\Role;

/**
 * Class RoleController
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class RoleController extends Controller
{

    use Pillow;

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
     * Create a new Role
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
     * Delete a role
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
     * Give a user a role
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
     * Remove a user from a role
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
