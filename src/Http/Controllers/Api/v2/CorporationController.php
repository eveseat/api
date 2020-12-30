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
use Seat\Api\Http\Resources\ContactResource;
use Seat\Api\Http\Resources\ContractResource;
use Seat\Api\Http\Resources\CorporationSheetResource;
use Seat\Api\Http\Resources\IndustryResource;
use Seat\Api\Http\Resources\MemberTrackingResource;
use Seat\Api\Http\Traits\Filterable;
use Seat\Eveapi\Models\Assets\CorporationAsset;
use Seat\Eveapi\Models\Contacts\CorporationContact;
use Seat\Eveapi\Models\Contracts\CorporationContract;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Eveapi\Models\Industry\CorporationIndustryJob;
use Seat\Eveapi\Models\Market\CorporationOrder;
use Seat\Eveapi\Models\Wallet\CorporationWalletJournal;
use Seat\Eveapi\Models\Wallet\CorporationWalletTransaction;

/**
 * Class CorporationController.
 * @package Seat\Api\Http\Controllers\Api\v2
 */
class CorporationController extends ApiController
{
    use Filterable;

    /**
     * @OA\Get(
     *      path="/v2/corporation/assets/{corporation_id}",
     *      tags={"Assets"},
     *      summary="Get a paginated list of a assets for a corporation",
     *      description="Returns a list of assets",
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
     *      @OA\Parameter(
     *          name="item_id",
     *          description="Specific Item ID",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="query"
     *      ),
     *      @OA\Parameter(
     *          in="query"
     *          name="$filter",
     *          description="Query filter following OData format",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/CorporationAsset")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAssets(int $corporation_id)
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationAsset::with('type')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return Resource::collection($query->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/corporation/contacts/{corporation_id}",
     *      tags={"Contacts"},
     *      summary="Get a list of contacts for a corporation",
     *      description="Returns a list of contacts",
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
     *      @OA\Parameter(
     *          in="query"
     *          name="$filter",
     *          description="Query filter following OData format",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/CorporationContact")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getContacts(int $corporation_id)
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationContact::with('labels')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return ContactResource::collection($query->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/corporation/contracts/{corporation_id}",
     *      tags={"Contracts"},
     *      summary="Get a paginated list of contracts for a corporation",
     *      description="Returns a list of contracts",
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
     *      @OA\Parameter(
     *          in="query"
     *          name="$filter",
     *          description="Query filter following OData format",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/ContractDetail")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getContracts(int $corporation_id)
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationContract::with('detail', 'detail.acceptor', 'detail.assignee', 'detail.issuer', 'detail.bids', 'detail.lines', 'detail.start_location', 'detail.end_location')
            ->where('corporation_id', $corporation_id)
            ->whereHas('detail', function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return ContractResource::collection($query->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/corporation/industry/{corporation_id}",
     *      tags={"Industry"},
     *      summary="Get a paginated list of industry jobs for a corporation",
     *      description="Returns a list of industry jobs",
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
     *      @OA\Parameter(
     *          in="query"
     *          name="$filter",
     *          description="Query filter following OData format",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/CorporationIndustryJob")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getIndustry(int $corporation_id)
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationIndustryJob::with('blueprint', 'product')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return IndustryResource::collection($query->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/corporation/market-orders/{corporation_id}",
     *      tags={"Market"},
     *      summary="Get a paginated list of market orders for a corporation",
     *      description="Returns a list of market orders",
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
     *      @OA\Parameter(
     *          in="query"
     *          name="$filter",
     *          description="Query filter following OData format",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/CorporationOrder")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMarketOrders(int $corporation_id)
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationOrder::with('type')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return Resource::collection($query->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/corporation/member-tracking/{corporation_id}",
     *      tags={"Corporation"},
     *      summary="Get a list of members for a corporation with tracking",
     *      description="Returns a list of members for a corporation",
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
     *      @OA\Parameter(
     *          in="query"
     *          name="$filter",
     *          description="Query filter following OData format",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/CorporationMemberTracking")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMemberTracking(int $corporation_id)
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationMemberTracking::with('ship')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return MemberTrackingResource::collection($query->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/corporation/sheet/{corporation_id}",
     *      tags={"Corporation"},
     *      summary="Get a corporation sheet",
     *      description="Returns a corporation sheet",
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
     *              @OA\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#definitions/CorporationInfo"
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Seat\Api\Http\Resources\CorporationSheetResource
     */
    public function getSheet(int $corporation_id)
    {
        return new CorporationSheetResource(CorporationInfo::with('ceo', 'creator', 'alliance', 'faction')
            ->findOrFail($corporation_id));
    }

    /**
     * @OA\Get(
     *      path="/v2/corporation/wallet-journal/{corporation_id}",
     *      tags={"Wallet"},
     *      summary="Get a paginated wallet journal for a corporation",
     *      description="Returns a wallet journal",
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
     *      @OA\Parameter(
     *          in="query"
     *          name="$filter",
     *          description="Query filter following OData format",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/CorporationWalletJournal")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWalletJournal(int $corporation_id)
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationWalletJournal::with('first_party', 'second_party')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return Resource::collection($query->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/corporation/wallet-transactions/{corporation_id}",
     *      tags={"Wallet"},
     *      summary="Get paginated wallet transactions for a corporation",
     *      description="Returns wallet transactions",
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
     *      @OA\Parameter(
     *          in="query"
     *          name="$filter",
     *          description="Query filter following OData format",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/CorporationWalletTransaction")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWalletTransactions(int $corporation_id)
    {
        request()->validate([
            '$filter' => 'string',
        ]);

        $query = CorporationWalletTransaction::with('party', 'type')
            ->where('corporation_id', $corporation_id)
            ->where(function ($sub_query) {
                $this->applyFilters(request(), $sub_query);
            });

        return Resource::collection($query->paginate());
    }
}
