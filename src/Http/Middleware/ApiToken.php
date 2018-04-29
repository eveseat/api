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

namespace Seat\Api\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
     * @return bool
     */
    public function valid_token_ip(Request $request) : bool
    {
        $token = ApiTokenModel::where('token', $request->header('X-Token'))
            ->first();

        if ($token == null)
            return false;

        if ($this->isInternetProtocol($token->allowed_src))
        {
            return $token->allowed_src == $request->getClientIp();
        }

        if ($this->isCidrNotation($token->allowed_src))
        {
            return $this->isIPInRange($request->getClientIp(), $token->allowed_src);
        }

        // SPF check

        // attempt to retrieve any existing SPF records
        $ipRanges = Cache::get($token->allowed_src);
        // if none has been retrieved, renew DNS records
        if ($ipRanges == null) {
            $ipRanges = $this->getSpfRecord($token->allowed_src);

            if (count($ipRanges) < 1)
                return false;

            Cache::put($token->allowed_src, $ipRanges, Carbon::now()->addHours(12));
        }

        // iterate over all the IP range list
        foreach ($ipRanges as $ipRange)
        {
            // if the ipRange is /32 without a CIDR notation, check if it match to the request IP
            if (! $this->isCidrNotation($ipRange) && ($request->getClientIp() == $ipRange))
                return true;

            // check if the request IP is in the current IP range
            if ($this->isIPInRange($request->getClientIp(), $ipRange))
                return true;
        }

        return false;
    }

    /**
     * Check if the value is an IP.
     *
     * @param string $value The value to check
     * @return bool true if the value is an IP
     */
    private function isInternetProtocol(string $value) : bool
    {
        $regex = '/^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$/';

        return preg_match($regex, $value) === 1;
    }

    /**
     * Check if the value is using a CIDR notation.
     *
     * @param string $value The value to check
     * @return bool true if the value is using a CIDR notation
     */
    private function isCidrNotation(string $value) : bool
    {
        $regex = '/^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})\/([0-3]{2})$/';

        return preg_match($regex, $value) === 1;
    }

    /**
     * Check if a given ip is in a network.
     *
     * @param string $value IP to check in IPv4 format, eg. 127.0.0.1
     * @param string $range IP/CIDR netmask, eg. 127.0.0.1/24, also 127.0.0.1 is accepted and /32 assumed
     * @return bool true if the ip is in range
     */
    private function isIPInRange(string $value, string $range) : bool
    {
        if (strpos($range, '/') == false)
            $range .= '/32';

        // range is in IP/CIDR format, eg: 127.0.0.1/24
        list($range, $netmask) = explode('/', $range, 2);
        $range_decimal = ip2long($range);
        $ip_decimal = ip2long($value);
        $netmask_decimal = ~(pow(2, (32 - $netmask)) - 1);

        // apply binary and
        return ($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal);
    }

    /**
     * Check if a value is an SPF record.
     *
     * @param string $value The value to check
     * @return bool true if the value is an SPF record
     */
    private function isSpfRecord(string $value) : bool
    {
        return strpos($value, 'v=spf') !== false;
    }

    /**
     * Fetch all SPF records.
     *
     * @param string $value The domain from which SPF records should be collected
     * @return array A list of IPs
     */
    private function getSpfRecord(string $value) : array
    {
        $ips = [];

        $dns_records = dns_get_record($value, DNS_TXT);
        foreach ($dns_records as $spf_record) {
            if (! $this->isSpfRecord($spf_record['txt']))
                continue;

            $spf_record = explode(' ', $spf_record['txt']);

            foreach ($spf_record as $spf_value) {
                if (strpos($spf_value, 'v=spf') !== false)
                    continue;

                $spf_value = explode(':', $spf_value);

                switch ($spf_value[0])
                {
                    case 'include':
                        $ips = array_merge($ips, $this->getSpfRecord($spf_value[1]));
                        break;
                    case 'ip4':
                        $ips[] = $spf_value[1];
                        break;
                }
            }
        }

        return $ips;
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
