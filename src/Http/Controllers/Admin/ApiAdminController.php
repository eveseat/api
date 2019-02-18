<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

namespace Seat\Api\Http\Controllers\Admin;

use Seat\Api\Http\Validation\NewToken;
use Seat\Api\Models\ApiToken;
use Seat\Api\Models\ApiTokenLog;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class ApiAdminController.
 * @package Seat\Api\Http\Controllers\Admin
 */
class ApiAdminController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listTokens()
    {

        $tokens = ApiToken::all();

        return view('api::list', compact('tokens'));
    }

    /**
     * @param \Seat\Api\Http\Validation\NewToken $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateToken(NewToken $request)
    {

        $fields = $request->all();
        $fields['token'] = str_random(32);

        ApiToken::create($fields);

        return redirect()->back()
            ->with('success', 'New API Token generated');
    }

    /**
     * @param $token_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteToken($token_id)
    {

        ApiToken::findOrFail($token_id)->delete();

        return redirect()->back()
            ->with('success', 'Token deleted');
    }

    /**
     * @param $token_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLogs($token_id)
    {

        $logs = ApiTokenLog::where('api_token_id', $token_id)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('api::logs', compact('logs'));
    }
}
