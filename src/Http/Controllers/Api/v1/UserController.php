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

use App\Http\Requests;
use Illuminate\Routing\Controller;
use Seat\Api\Validation\NewUser;
use Seat\Api\Validation\UpdateUser;
use Seat\Web\Models\User;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::all();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Seat\Api\Validation\NewUser $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(NewUser $request)
    {

        User::create([
            'name'     => $request->input('username'),
            'email'    => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'active'   => true
        ]);

        return response()->json(['ok']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // Allow for both an id, or a name as an identifier
        $user = User::with('roles', 'keys', 'affiliations')
            ->where(is_numeric($id) ? 'id' : 'name', $id)
            ->first();

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Seat\Api\Validation\UpdateUser $request
     * @param  int                            $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, $id)
    {

        // Extract the update data. We want to hash a password
        // before it is stored, so check for that and perform
        // the hashing
        $update_data = $request->all();
        if (array_key_exists('password', $update_data))
            $update_data['password'] = bcrypt($request->password);

        // Allow for both an id, or a name as an identifier
        User::where(is_numeric($id) ? 'id' : 'name', $id)
            ->update($update_data);

        return response()->json(['ok']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // Allow for both an id, or a name as an identifier
        User::where(is_numeric($id) ? 'id' : 'name', $id)
            ->delete();

        return response()->json(['ok']);
    }
}
