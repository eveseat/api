<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

use Seat\Api\Http\Resources\SquadResource;
use Seat\Api\Http\Validation\NewSquad;
use Seat\Web\Models\Squads\Squad;

/**
 * Class SquadController.
 *
 * @package Seat\Api\Http\Controllers\Api\v2
 */
class SquadController extends ApiController
{
    /**
     * @OA\Get(
     *      path="/v2/squads",
     *      tags={"Squads"},
     *      summary="Get a list of squads",
     *      description="Returns list of squads",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/SquadResource")
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
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return SquadResource::collection(Squad::paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/squads/{squad_id}",
     *      tags={"Squads"},
     *      summary="Get details about a Squad",
     *      description="Return detailled information from a Squad",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="squad_id",
     *          description="Squad id",
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
     *                  @OA\Items(ref="#/components/schemas/Squad")
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     * )
     *
     * @param int $squad_id
     * @return \Seat\Api\Http\Resources\SquadResource
     */
    public function show(int $squad_id)
    {
        return SquadResource::make(Squad::with('roles', 'moderators', 'members', 'applications')->findOrFail($squad_id));
    }

    /**
     * @OA\Post(
     *      path="/v2/squads/",
     *      tags={"Squads"},
     *      summary="Create a new SeAT squad",
     *      description="Creates a new SeAT Squad",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"name", "type", "description"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Squad name"
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                      enum={"hidden", "manual", "auto"},
     *                      description="Squad type"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="Squad description"
     *                  ),
     *                  @OA\Property(
     *                      property="logo",
     *                      type="string",
     *                      format="byte",
     *                      description="Squad logo"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#/components/schemas/Squad"
     *              ),
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *  )
     *
     * @param \Seat\Api\Http\Validation\NewSquad $request
     * @return \Seat\Api\Http\Resources\SquadResource
     */
    public function store(NewSquad $request)
    {
        $squad = new Squad();
        $squad->name = $request->get('name');
        $squad->type = $request->get('type');
        $squad->description = $request->description;

        if ($request->has('logo'))
            $squad->logo = $request->get('logo');

        $squad->save();

        return SquadResource::make($squad->load('roles', 'moderators', 'members', 'applications'));
    }

    /**
     * @OA\Delete(
     *      path="/v2/squads/{squad_id}",
     *      tags={"Squads"},
     *      summary="Delete a SeAT squad",
     *      description="Deletes a squad",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="squad_id",
     *          description="A SeAT squad id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized")
     *   )
     *
     * @param int $squad_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $squad_id)
    {
        Squad::findOrFail($squad_id)->delete();

        return response()->json();
    }
}
