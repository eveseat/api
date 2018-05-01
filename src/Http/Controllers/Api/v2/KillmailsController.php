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

namespace Seat\Api\Http\Controllers\Api\v2;

use Seat\Api\Http\Resources\KillmailDetailResource;
use Seat\Eveapi\Models\Killmails\KillmailDetail;


/**
 * Class KillmailsController
 * @package Seat\Api\Http\Controllers\Api\v2
 */
class KillmailsController extends ApiController
{
    /**
     * @SWG\Get(
     *      path="/killmails/detail/{killmail_id}",
     *      tags={"Killmails"},
     *      summary="Get full details about a killmail",
     *      description="Returns a detailed killmail",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="killmail_id",
     *          description="Killmail id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * Returns a detailed killmail
     */

    /**
     * @param int $killmail_id
     *
     * @return \Seat\Api\Http\Resources\KillmailDetailResource
     */
    public function getDetail(int $killmail_id)
    {

        return new KillmailDetailResource(KillmailDetail::findOrFail($killmail_id));
    }
}
