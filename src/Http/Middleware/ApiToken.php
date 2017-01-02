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

namespace Seat\Api\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Seat\Api\Models\ApiToken as ApiTokenModel;
use Seat\Api\Models\ApiTokenLog;

/**
 * Class ApiToken.
 * @package Seat\Api\Http\Middleware
 */
class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (! $this->valid_token_ip($request)) {

            $this->log_activity($request, 'deny');

            return response('Unauthorized', 401);
        }

        $this->log_activity($request, 'allow');

        return $next($request);
    }

    /**
     * Validate a token / ip pair from a Request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function valid_token_ip(Request $request)
    {

        return ApiTokenModel::where('token', $request->header('X-Token'))
            ->where('allowed_src', $request->getClientIp())
            ->first();
    }

    /**
     * Log an API request based on the config setting.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $action
     */
    public function log_activity(Request $request, $action)
    {

        if (config('api.config.log_requests')) {

            $token_id = ApiTokenModel::where('token',
                $request->header('X-Token'))
                ->value('id');

            ApiTokenLog::create([
                'api_token_id' => $token_id,
                'action'       => $action,
                'request_path' => $request->path(),
                'src_ip'       => $request->getClientIp(),
            ]);
        }
    }
}
