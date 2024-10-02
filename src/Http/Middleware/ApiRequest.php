<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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
use Symfony\Component\HttpFoundation\HeaderBag;

class ApiRequest
{
    /**
     * @param  $request
     * @param  \Closure  $next
     * @return mixed|void
     */
    public function handle($request, Closure $next)
    {
        if ($request->headers->has('Accept') && ! in_array($request->headers->get('Accept'), ['', '*/*', 'application/json']))
            return response()->json("Invalid Accept header. Either accept all response types, or specify 'application/json'.", 406);

        // force return to be JSON formatted
        $request->server->set('HTTP_ACCEPT', 'application/json');
        $request->headers = new HeaderBag($request->server->getHeaders());

        // ensure the request has been made using application/json (for PATCH, PUT or POST queries)
        if ($request->headers->has('Content-Type') && ! in_array($request->headers->get('Content-Type'), ['', 'application/json']))
            return response()->json("Invalid Content-type header. Only the 'application/json' type is accepted.", 415);

        return $next($request);
    }
}
