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
use Illuminate\Http\Request;
use Seat\Api\Models\ApiToken;

/**
 * Class CorporationOwnership.
 *
 * Ensures that the API token owner has access to the requested corporation_id.
 *
 * A user is considered to have access to a corporation if at least one of their
 * characters belongs to that corporation. Tokens with no associated user
 * (user_id is null) are treated as superuser-scoped tokens with unrestricted
 * access. Tokens linked to a specific user with the superuser role also retain
 * unrestricted access.
 *
 * @package Seat\Api\Http\Middleware
 */
class CorporationOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $corporation_id = $request->route('corporation_id');

        // If there is no corporation_id in the route, pass through.
        if (is_null($corporation_id)) {
            return $next($request);
        }

        $token = ApiToken::where('token', $request->header('X-Token'))->first();

        // Token not found — the api.auth middleware will already have rejected
        // this request, but we guard defensively here as well.
        if (is_null($token)) {
            return response()->json('Unauthorized', 401);
        }

        // Tokens with no associated user are superuser-scoped (unrestricted).
        if (is_null($token->user_id)) {
            return $next($request);
        }

        $user = $token->user;

        // Superusers have unrestricted access to all corporations.
        if ($user->hasSuperUser()) {
            return $next($request);
        }

        // A user has access to a corporation if at least one of their characters
        // is a member of that corporation.
        $userCorporationIds = $user->characters
            ->map(function ($character) {
                return optional($character->affiliation)->corporation_id;
            })
            ->filter()
            ->unique()
            ->values();

        if (! $userCorporationIds->contains((int) $corporation_id)) {
            return response()->json([
                'error' => 'Access to this corporation is not authorized.',
            ], 403);
        }

        return $next($request);
    }
}
