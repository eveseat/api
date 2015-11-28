<?php

namespace Seat\Api\Http\Middleware;

use Closure;

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

        $token = $request->header('X-Token');

        if (!$token)
            return response('Unauthorized', 401);

        return $next($request);
    }
}
