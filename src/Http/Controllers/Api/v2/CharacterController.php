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


use Seat\Api\Http\Resources\AssetResource;
use Seat\Api\Http\Resources\ContactResource;
use Seat\Api\Http\Resources\IndustryResource;
use Seat\Api\Http\Resources\KillmailResource;
use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\Contacts\CharacterContact;
use Seat\Eveapi\Models\Industry\CharacterIndustryJob;
use Seat\Eveapi\Models\Killmails\CharacterKillmail;

/**
 * Class CharacterController.
 *
 * @package  Seat\Api\v2
 */
class CharacterController extends ApiController
{
    /**
     * @SWG\Get(
     *      path="/character/assets/{character_id}",
     *      tags={"Assets"},
     *      summary="Get a paginated list of a assets for a character",
     *      description="Returns a list of assets",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * Returns list of assets
     */

    /**
     * @SWG\Get(
     *      path="/character/assets/{character_id}/{item_id}",
     *      tags={"Assets"},
     *      summary="Get a specific asset",
     *      description="Returns list of assets",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="item_id",
     *          description="Asset item id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * Returns an asset
     */

    /**
     * @param int $character_id
     * @param int $item_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAssets(int $character_id, int $item_id = null)
    {

        if (is_null($item_id))
            return AssetResource::collection(CharacterAsset::where('character_id', $character_id)
                ->paginate());

        return new AssetResource(CharacterAsset::where('character_id', $character_id)
            ->where('item_id', $item_id)->firstOrFail());
    }

    /**
     * @SWG\Get(
     *      path="/character/bookmarks/{character_id}",
     *      tags={"Bookmarks"},
     *      summary="Get a paginated list of bookmarks for a character",
     *      description="Returns list of bookmarks",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * Returns a list of bookmarks
     */

    /**
     * @param int $chacter_id
     */
    public function getBookmarks(int $chacter_id)
    {

        // TODO
    }

    /**
     * @SWG\Get(
     *      path="/character/contacts/{character_id}",
     *      tags={"Contacts"},
     *      summary="Get a paginated list of contacs for a character",
     *      description="Returns list of contacs",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * Returns a list of contacts
     */

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getContacts(int $character_id)
    {

        return ContactResource::collection(CharacterContact::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/character/industry/{character_id}",
     *      tags={"Industry"},
     *      summary="Get a paginated list of industry jobs for a character",
     *      description="Returns list of industry jobs",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * Returns a list of industry jobs
     */

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getIndustry(int $character_id)
    {

        return IndustryResource::collection(CharacterIndustryJob::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/character/killmails/{character_id}",
     *      tags={"Killmails"},
     *      summary="Get a paginated list of killmails for a character",
     *      description="Returns list of killmails",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * Returns a list of killmails
     */

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getKillmails(int $character_id)
    {

        return KillmailResource::collection(CharacterKillmail::where('character_id', $character_id)
            ->paginate());
    }
}
