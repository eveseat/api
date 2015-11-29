<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

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
use Seat\Api\Validation\ApiKeyUpdate;
use Seat\Eveapi\Models\Eve\ApiKey;
use Seat\Web\Validation\ApiKey as ApiKeyValidator;

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
     * @param \Seat\Web\Validation\ApiKey $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ApiKeyValidator $request)
    {

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

        return ApiKey::findOrFail($id);
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
}
