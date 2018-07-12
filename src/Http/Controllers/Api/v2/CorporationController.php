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
use Seat\Api\Http\Resources\BookmarkResource;
use Seat\Api\Http\Resources\ContactResource;
use Seat\Api\Http\Resources\ContractResource;
use Seat\Api\Http\Resources\CorporationSheetResource;
use Seat\Api\Http\Resources\IndustryResource;
use Seat\Api\Http\Resources\KillmailResource;
use Seat\Api\Http\Resources\MarketOrderResource;
use Seat\Api\Http\Resources\MemberTrackingResource;
use Seat\Api\Http\Resources\WalletJournalResource;
use Seat\Api\Http\Resources\WalletTransactionResource;
use Seat\Eveapi\Models\Assets\CorporationAsset;
use Seat\Eveapi\Models\Bookmarks\CorporationBookmark;
use Seat\Eveapi\Models\Contacts\CorporationContact;
use Seat\Eveapi\Models\Contracts\CorporationContract;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Eveapi\Models\Industry\CorporationIndustryJob;
use Seat\Eveapi\Models\Killmails\CorporationKillmail;
use Seat\Eveapi\Models\Market\CorporationOrder;
use Seat\Eveapi\Models\Wallet\CorporationWalletJournal;
use Seat\Eveapi\Models\Wallet\CorporationWalletTransaction;

/**
 * Class CorporationController.
 * @package Seat\Api\Http\Controllers\Api\v2
 */
class CorporationController extends ApiController
{
    /**
     * @SWG\Get(
     *      path="/corporation/assets/{corporation_id}",
     *      tags={"Assets"},
     *      summary="Get a paginated list of a assets for a corporation",
     *      description="Returns a list of assets",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAssets(int $corporation_id)
    {

        return AssetResource::collection(CorporationAsset::where('corporation_id', $corporation_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/corporation/bookmarks/{corporation_id}",
     *      tags={"Bookmarks"},
     *      summary="Get a list of bookmarks for a corporation",
     *      description="Returns a list of bookmarks",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  type="array",
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/CorporationBookmark")
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getBookmarks(int $corporation_id)
    {

        return BookmarkResource::collection(CorporationBookmark::where('corporation_id', $corporation_id)->get());
    }

    /**
     * @SWG\Get(
     *      path="/corporation/contacts/{corporation_id}",
     *      tags={"Contacts"},
     *      summary="Get a list of contacts for a corporation",
     *      description="Returns a list of contacts",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  type="array",
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/CorporationContact")
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getContacts(int $corporation_id)
    {

        return ContactResource::collection(CorporationContact::where('corporation_id', $corporation_id)
            ->get());
    }

    /**
     * @SWG\Get(
     *      path="/corporation/contracts/{corporation_id}",
     *      tags={"Contracts"},
     *      summary="Get a paginated list of contracts for a corporation",
     *      description="Returns a list of contracts",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  type="array",
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/CorporationContract")
     *              ),
     *              @SWG\Property(
     *                  type="object",
     *                  property="links",
     *                  description="Provide pagination urls for navigation",
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="first",
     *                      description="First page"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="last",
     *                      description="Last page"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="prev",
     *                      description="Previous page"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="next",
     *                      description="Next page"
     *                  )
     *              ),
     *              @SWG\Property(
     *                  type="object",
     *                  property="meta",
     *                  description="Information related to the paginated response",
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="current_page",
     *                      description="The current page"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="from",
     *                      description="The first entity number on the page"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="last_page",
     *                      description="The last page available"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="path",
     *                      description="The base endpoint"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="per_page",
     *                      description="The pagination step"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="to",
     *                      description="The last entity number on the page"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="total",
     *                      description="The total of available entities"
     *                  )
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getContracts(int $corporation_id)
    {

        return ContractResource::collection(CorporationContract::where('corporation_id', $corporation_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/corporation/industry/{corporation_id}",
     *      tags={"Industry"},
     *      summary="Get a paginated list of industry jobs for a corporation",
     *      description="Returns a list of industry jobs",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  type="array",
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/CorporationIndustryJob")
     *              ),
     *              @SWG\Property(
     *                  type="object",
     *                  property="links",
     *                  description="Provide pagination urls for navigation",
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="first",
     *                      description="First page"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="last",
     *                      description="Last page"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="prev",
     *                      description="Previous page"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="next",
     *                      description="Next page"
     *                  )
     *              ),
     *              @SWG\Property(
     *                  type="object",
     *                  property="meta",
     *                  description="Information related to the paginated response",
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="current_page",
     *                      description="The current page"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="from",
     *                      description="The first entity number on the page"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="last_page",
     *                      description="The last page available"
     *                  ),
     *                  @SWG\Property(
     *                      type="string",
     *                      format="uri",
     *                      property="path",
     *                      description="The base endpoint"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="per_page",
     *                      description="The pagination step"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="to",
     *                      description="The last entity number on the page"
     *                  ),
     *                  @SWG\Property(
     *                      type="integer",
     *                      property="total",
     *                      description="The total of available entities"
     *                  )
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getIndustry(int $corporation_id)
    {

        return IndustryResource::collection(CorporationIndustryJob::where('corporation_id', $corporation_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/corporation/killmails/{corporation_id}",
     *      tags={"Killmails"},
     *      summary="Get a paginated list of killmails for a corporation",
     *      description="Returns a list of killmails",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getKillmails(int $corporation_id)
    {

        return KillmailResource::collection(CorporationKillmail::where('corporation_id', $corporation_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/corporation/market-orders/{corporation_id}",
     *      tags={"Market"},
     *      summary="Get a paginated list of market orders for a corporation",
     *      description="Returns a list of market orders",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMarketOrders(int $corporation_id)
    {

        return MarketOrderResource::collection(CorporationOrder::where('corporation_id', $corporation_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/corporation/member-tracking/{corporation_id}",
     *      tags={"Corporation"},
     *      summary="Get a list of members for a corporation with tracking",
     *      description="Returns a list of members for a corporation",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#definitions/CorporationMemberTracking"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMemberTracking(int $corporation_id)
    {

        return MemberTrackingResource::collection(CorporationMemberTracking::where('corporation_id', $corporation_id)
            ->get());
    }

    /**
     * @SWG\Get(
     *      path="/corporation/sheet/{corporation_id}",
     *      tags={"Corporation"},
     *      summary="Get a corporation sheet",
     *      description="Returns a corporation sheet",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  type="object",
     *                  property="data",
     *                  ref="#definitions/CorporationInfo"
     *              )
     *          )
     *      ),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Seat\Api\Http\Resources\CorporationSheetResource
     */
    public function getSheet(int $corporation_id)
    {

        return new CorporationSheetResource(CorporationInfo::findOrFail($corporation_id));
    }

    /**
     * @SWG\Get(
     *      path="/corporation/wallet-journal/{corporation_id}",
     *      tags={"Wallet"},
     *      summary="Get a paginated wallet journal for a corporation",
     *      description="Returns a wallet journal",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWalletJournal(int $corporation_id)
    {

        return WalletJournalResource::collection(CorporationWalletJournal::where('corporation_id', $corporation_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/corporation/wallet-transactions/{corporation_id}",
     *      tags={"Wallet"},
     *      summary="Get paginated wallet transactions for a corporation",
     *      description="Returns wallet transactions",
     *      security={"ApiKeyAuth"},
     *      @SWG\Parameter(
     *          name="corporation_id",
     *          description="Corporation id",
     *          required=true,
     *          type="integer",
     *          in="path"
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWalletTransactions(int $corporation_id)
    {

        return WalletTransactionResource::collection(CorporationWalletTransaction::where('corporation_id', $corporation_id)
            ->paginate());
    }
}
