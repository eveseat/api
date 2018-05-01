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
use Seat\Eveapi\Models\Assets\CharacterAsset;

/**
 * Class CharacterController.
 *
 * @package  Seat\Api\v2
 */
class CharacterController extends ApiController
{

    /*
     * Models
     */

    /**
     * @SWG\Definition(
     *      definition="CharacterAsset",
     *      type="object",
     *      @SWG\Property(property="item_id", type="integer", format="int64"),
     *      @SWG\Property(property="type_id", type="integer", format="int32"),
     *      @SWG\Property(property="quantity", type="integer", format="int32"),
     *      @SWG\Property(property="location_type", type="string"),
     *      @SWG\Property(property="location_flag", type="boolean"),
     *      @SWG\Property(property="is_singleton", type="boolean"),
     *      @SWG\Property(property="x", type="number"),
     *      @SWG\Property(property="y", type="number"),
     *      @SWG\Property(property="z", type="number"),
     *      @SWG\Property(property="map_id", type="integer", format="int64"),
     *      @SWG\Property(property="map_name", type="string"),
     *      @SWG\Property(property="name", type="string"),
     * )
     */

    /**
     * @SWG\Get(
     *      path="/character/assets/{character_id}",
     *      tags={"Assets"},
     *      summary="Get a paginated list of a characters assets",
     *      description="Returns list of assets",
     *      security={
     *          {"ApiKeyAuth"}
     *      },
     *      @SWG\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *             @SWG\Items(ref="#/definitions/CharacterAsset")
     *         ),
     *       ),
     *       @SWG\Response(response=400, description="Bad request"),
     *       @SWG\Response(response=401, description="Unauthorized"),
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
     *      security={
     *          {"ApiKeyAuth"}
     *      },
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
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @SWG\Response(response=400, description="Bad request"),
     *       @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * Returns an asset
     */

    /**
     * Get the assets for a character.
     *
     * @param      $character_id
     * @param null $item_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAssets($character_id, $item_id = null)
    {

        if (is_null($item_id))
            return AssetResource::collection(CharacterAsset::where('character_id', $character_id)
                ->paginate());

        return new AssetResource(CharacterAsset::where('character_id', $character_id)
            ->where('item_id', $item_id)->firstOrFail());
    }
}
