<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017  Leon Jacobs
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

use Illuminate\Routing\Controller;
use Seat\Web\Models\User;

/**
 * Class RoleLookupController.
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class RoleLookupController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissions()
    {

        return response()->json(config('web.permissions'));
    }

    /**
     * @param $user_identifier
     * @param $role_identifier
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoleCheck($user_identifier, $role_identifier)
    {

        $user = User::where(
            is_numeric($user_identifier) ? 'id' : 'name', $user_identifier)
            ->first();

        if (! $user)
            return response()->json([
                'msg' => sprintf('Unable to retrieve the user with either id or name "%s"', $user_identifier),
            ], 404);

        $access = $user->hasRole($role_identifier);

        return response()->json($access);
    }

    /**
     * @param $user_identifier
     * @param $permission_identifier
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionCheck($user_identifier, $permission_identifier)
    {

        $user = User::where(
            is_numeric($user_identifier) ? 'id' : 'name', $user_identifier)
            ->first();

        if (! $user)
            return response()->json([
                'msg' => sprintf('Unable to retrieve the user with either id or name "%s"', $user_identifier),
            ], 404);

        $access = $user->has($permission_identifier, false);

        return response()->json($access);

    }
}
