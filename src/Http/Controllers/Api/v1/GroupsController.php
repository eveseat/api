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

use Illuminate\Routing\Controller;
use Seat\Web\Models\Person;

/**
 * Class GroupsController.
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class GroupsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroups()
    {

        $people = Person::all();

        return response()->json($people);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupDetail(int $group_id)
    {

        $detail = Person::with('members', 'members.characters')
            ->findOrFail($group_id);

        return response()->json($detail);
    }
}
