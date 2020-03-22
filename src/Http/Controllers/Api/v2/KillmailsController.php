<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

use Illuminate\Http\Resources\Json\Resource;
use Seat\Api\Http\Resources\KillmailDetailResource;
use Seat\Eveapi\Models\Killmails\Killmail;
use Seat\Eveapi\Models\Killmails\KillmailDetail;

/**
 * Class KillmailsController.
 * @package Seat\Api\Http\Controllers\Api\v2
 */
class KillmailsController extends ApiController
{
    /**
     * @OA\Get(
     *      path="/v2/character/killmails/{character_id}",
     *      tags={"Killmails"},
     *      summary="Get a paginated list of killmails for a character",
     *      description="Returns list of killmails",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/Killmail")
     *              ),
     *              @OA\Property(
     *                  property="links",
     *                  ref="#/components/schemas/ResourcePaginatedLinks"
     *              ),
     *              @OA\Property(
     *                  property="meta",
     *                  ref="#/components/schemas/ResourcePaginatedMetadata"
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $character_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCharacterKillmails(int $character_id)
    {
        return Resource::collection(
            Killmail::with('detail', 'victim', 'attackers')
                ->whereHas('victim', function ($query) use ($character_id) {
                    $query->where('character_id', $character_id);
                })->orWhereHas('attackers', function ($query) use ($character_id) {
                    $query->where('character_id', $character_id);
                })->paginate()
            );
    }

    /**
     * @OA\Get(
     *      path="/v2/corporation/killmails/{corporation_id}",
     *      tags={"Killmails"},
     *      summary="Get a paginated list of killmails for a corporation",
     *      description="Returns list of killmails",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/Killmail")
     *              ),
     *              @OA\Property(
     *                  property="links",
     *                  ref="#/components/schemas/ResourcePaginatedLinks"
     *              ),
     *              @OA\Property(
     *                  property="meta",
     *                  ref="#/components/schemas/ResourcePaginatedMetadata"
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCorporationKillmails(int $corporation_id)
    {
        return Resource::collection(
            Killmail::whereHas('victim', function ($query) use ($corporation_id) {
                $query->where('corporation_id', $corporation_id);
            })->orWhereHas('attackers', function ($query) use ($corporation_id) {
                $query->where('corporation_id', $corporation_id);
            })->paginate()
        );
    }

    /**
     * @OA\Get(
     *      path="/v2/killmails/{killmail_id}",
     *      tags={"Killmails"},
     *      summary="Get full details about a killmail",
     *      description="Returns a detailed killmail",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="killmail_id",
     *          description="Killmail id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="object",
     *                  property="data",
     *                  @OA\Property(
     *                      property="killmail_time",
     *                      type="string",
     *                      format="date-time",
     *                      description="The date-time when kill append"
     *                  ),
     *                  @OA\Property(
     *                      property="solar_system_id",
     *                      type="integer",
     *                      description="The solar system identifier in which the kill occurs"
     *                  ),
     *                  @OA\Property(
     *                      property="moon_id",
     *                      type="integer",
     *                      description="The moon identifier near to which the kill occurs"
     *                  ),
     *                  @OA\Property(
     *                      property="war_id",
     *                      type="integer",
     *                      format="int64",
     *                      description="The war identifier in which the kill involves"
     *                  ),
     *                  @OA\Property(
     *                      property="attackers",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/KillmailAttacker")
     *                  ),
     *                  @OA\Property(
     *                      property="victim",
     *                      ref="#/components/schemas/KillmailVictim"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * Returns a detailed killmail
     *
     * @param int $killmail_id
     *
     * @return \Seat\Api\Http\Resources\KillmailDetailResource
     */
    public function getDetail(int $killmail_id)
    {

        return new KillmailDetailResource(KillmailDetail::with('attackers', 'victim')->findOrFail($killmail_id));
    }
}
