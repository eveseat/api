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
use Seat\Api\Validation\ApiKeyUpdate;
use Seat\Eveapi\Models\Eve\ApiKey;
use Seat\Web\Http\Validation\ApiKey as ApiKeyValidator;
use Seat\Web\Models\User;

/**
 * Class ApiKeyController.
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class ApiKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $keys = ApiKey::all();

        return response()->json($keys);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Seat\Web\Http\Validation\ApiKey $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ApiKeyValidator $request)
    {

        // If we dont have a user_id, set it to 1
        // which is the admin user.
        if (! $request->has('user_id'))
            $request['user_id'] = 1;

        if (ApiKey::find($request->input('key_id')))
            return response()->json([
                'msg' => 'This key already exists',
            ], 400);

        ApiKey::create($request->all());

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

        return ApiKey::with('info', 'characters')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Seat\Api\Validation\ApiKeyUpdate $request
     * @param  int                              $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ApiKeyUpdate $request, $id)
    {

        ApiKey::findOrFail($id)
            ->update($request->all());

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

        ApiKey::findOrFail($id)->delete();

        return response()->json(['ok']);
    }

    /**
     * Transfer an EVE API Key to a User.
     *
     * @param $key_id
     * @param $user_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function transfer($key_id, $user_id)
    {

        $key = ApiKey::findOrFail($key_id);
        User::findOrFail($user_id);

        $key->user_id = $user_id;
        $key->save();

        return response()->json(['ok']);

    }
}
