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
 * Class CharacterOwnership.
 *
 * Ensures that the API token owner has access to the requested character_id.
 *
 * Tokens with no associated user (user_id is null) are treated as superuser-scoped
 * tokens with unrestricted access to all characters. Tokens linked to a specific
 * user may only access character_ids belonging to that user, unless that user has
 * the superuser role.
 *
 * @package Seat\Api\Http\Middleware
 */
class CharacterOwnership
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
        $character_id = $request->route('character_id');

        // If there is no character_id in the route, pass through.
        if (is_null($character_id)) {
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

        // Superusers have unrestricted access to all characters.
        if ($user->hasSuperUser()) {
            return $next($request);
        }

        $ownedCharacterIds = $user->characters->pluck('character_id');

        if (! $ownedCharacterIds->contains((int) $character_id)) {
            return response()->json([
                'error' => 'Access to this character is not authorized.',
            ], 403);
        }

        return $next($request);
    }
}
