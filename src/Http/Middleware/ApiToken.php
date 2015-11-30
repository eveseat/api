<?php

namespace Seat\Api\Http\Middleware;

//use App\Http\Requests\Request;
use Closure;
use Illuminate\Http\Request;
use Seat\Api\Models\ApiToken as ApiTokenModel;
use Seat\Api\Models\ApiTokenLog;

/**
 * Class ApiToken
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

        if (!$this->valid_token_ip($request->header('X-Token'),
            $request->getClientIp())
        ) {

            $this->log_activity($request, 'deny');

            return response('Unauthorized', 401);
        }

        $this->log_activity($request, 'allow');

        return $next($request);
    }

    /**
     * Validate a token / ip pair
     *
     * @param $token
     * @param $ip
     *
     * @return mixed
     */
    public function valid_token_ip($token, $ip)
    {

        return ApiTokenModel::where('token', $token)
            ->where('allowed_src', $ip)
            ->first();
    }

    /**
     * Log an API request based on the config setting
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $action
     */
    public function log_activity(Request $request, $action)
    {

        if (config('api.config.log_requests')) {

            $token_id = ApiTokenModel::where('token',
                $request->header('X-Token'))
                ->pluck('id');

            ApiTokenLog::create([
                'api_token_id' => $token_id,
                'action'       => $action,
                'src_ip'       => $request->getClientIp()
            ]);
        }
    }
}
