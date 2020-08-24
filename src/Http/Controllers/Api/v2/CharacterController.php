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
use Seat\Api\Http\Resources\BookmarkResource;
use Seat\Api\Http\Resources\CharacterSheetResource;
use Seat\Api\Http\Resources\ContactResource;
use Seat\Api\Http\Resources\ContractResource;
use Seat\Api\Http\Resources\CorporationHistoryResource;
use Seat\Api\Http\Resources\IndustryResource;
use Seat\Api\Http\Resources\JumpcloneResource;
use Seat\Api\Http\Resources\MailResource;
use Seat\Api\Http\Resources\NotificationResource;
use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\Bookmarks\CharacterBookmark;
use Seat\Eveapi\Models\Character\CharacterCorporationHistory;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Eveapi\Models\Clones\CharacterJumpClone;
use Seat\Eveapi\Models\Contacts\CharacterContact;
use Seat\Eveapi\Models\Contracts\CharacterContract;
use Seat\Eveapi\Models\Industry\CharacterIndustryJob;
use Seat\Eveapi\Models\Mail\MailHeader;
use Seat\Eveapi\Models\Market\CharacterOrder;
use Seat\Eveapi\Models\Skills\CharacterSkillQueue;
use Seat\Eveapi\Models\Wallet\CharacterWalletJournal;
use Seat\Eveapi\Models\Wallet\CharacterWalletTransaction;

/**
 * Class CharacterController.
 *
 * @package  Seat\Api\v2
 */
class CharacterController extends ApiController
{
    /**
     * @OA\Get(
     *      path="/v2/character/assets/{character_id}",
     *      tags={"Assets"},
     *      summary="Get a paginated list of a assets for a character",
     *      description="Returns a list of assets",
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
     *      @OA\Parameter(
     *          name="item_id",
     *          description="Specific Item ID",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *          in="query"
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/CharacterAsset")
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
     * @param int $item_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAssets(int $character_id)
    {
        $query = CharacterAsset::with('type')
            ->where('character_id', $character_id);

        if (request()->exists('item_id'))
            $query->where('item_id', request()->query('item_id'));

        return Resource::collection($query->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/bookmarks/{character_id}",
     *      tags={"Bookmarks"},
     *      summary="Get a paginated list of bookmarks for a character",
     *      description="Returns a list of bookmarks",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterBookmark")
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $chacter_id
     */
    public function getBookmarks(int $character_id)
    {

        return BookmarkResource::collection(CharacterBookmark::where('character_id', $character_id)->get());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/contacts/{character_id}",
     *      tags={"Contacts"},
     *      summary="Get a paginated list of contacts for a character",
     *      description="Returns list of contacs",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterContact")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getContacts(int $character_id)
    {

        return ContactResource::collection(CharacterContact::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/contracts/{character_id}",
     *      tags={"Contracts"},
     *      summary="Get a paginated list of contracts for a character",
     *      description="Returns list of contracts",
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getContracts(int $character_id)
    {

        return ContractResource::collection(CharacterContract::with('detail', 'detail.acceptor', 'detail.assignee', 'detail.issuer', 'detail.bids', 'detail.lines', 'detail.start_location', 'detail.end_location')
            ->where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/corporation-history/{character_id}",
     *      tags={"Character"},
     *      summary="Get the corporation history for a character",
     *      description="Returns a corporation history",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterCorporationHistory")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCorporationHistory(int $character_id)
    {

        return CorporationHistoryResource::collection(CharacterCorporationHistory::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/industry/{character_id}",
     *      tags={"Industry"},
     *      summary="Get a paginated list of industry jobs for a character",
     *      description="Returns list of industry jobs",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterIndustryJob")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getIndustry(int $character_id)
    {

        return IndustryResource::collection(CharacterIndustryJob::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/jump-clones/{character_id}",
     *      tags={"Character"},
     *      summary="Get a paginated list of jump clones for a character",
     *      description="Returns list of jump clones",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterJumpClone")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getJumpClones(int $character_id)
    {

        return JumpcloneResource::collection(CharacterJumpClone::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/mail/{character_id}",
     *      tags={"Character"},
     *      summary="Get a paginated list of mail for a character",
     *      description="Returns mail",
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
     *                  @OA\Items(ref="#/components/schemas/MailResource")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMail(int $character_id)
    {

        return MailResource::collection(
            MailHeader::with('sender', 'body', 'recipients', 'recipients.entity')
                ->where(function ($query) use ($character_id) {
                    $query->whereHas('recipients', function ($sub_query) use ($character_id) {
                        $sub_query->where('recipient_id', $character_id);
                    });

                    $query->orWhere('from', $character_id);
                })
                ->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/market-orders/{character_id}",
     *      tags={"Market"},
     *      summary="Get a paginated list of market orders for a character",
     *      description="Returns list of market orders",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          in="path"
     *      ),
     *      @OA\Response(response=200, description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(ref="#/components/schemas/CharacterOrder")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMarketOrders(int $character_id)
    {

        return Resource::collection(CharacterOrder::with('type')
            ->where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/notifications/{character_id}",
     *      tags={"Character"},
     *      summary="Get a paginated list of notifications for a character",
     *      description="Returns a list of notifications",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterNotification")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getNotifications(int $character_id)
    {

        return NotificationResource::collection(CharacterNotification::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/sheet/{character_id}",
     *      tags={"Character"},
     *      summary="Get the character sheet for a character",
     *      description="Returns a character sheet",
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
     *                  type="object",
     *                  property="data",
     *                  ref="#/components/schemas/CharacterSheetResource"
     *              )
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     * @param int $character_id
     *
     * @return \Seat\Api\Http\Resources\CharacterSheetResource
     */
    public function getSheet(int $character_id)
    {

        return new CharacterSheetResource(
            CharacterInfo::with('affiliation.corporation', 'affiliation.alliance', 'affiliation.faction', 'balance', 'skillpoints')
                ->findOrFail($character_id));
    }

    /**
     * @OA\Get(
     *      path="/v2/character/skills/{character_id}",
     *      tags={"Character"},
     *      summary="Get the skills for a character",
     *      description="Returns character skills",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterSkill")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getSkills(int $character_id)
    {

        return Resource::collection(
            CharacterSkill::with('type')->where('character_id', $character_id)->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/skill-queue/{character_id}",
     *      tags={"Character"},
     *      summary="Get a list of characters skill queue",
     *      description="Returns a skill queue",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterSkillQueue")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getSkillQueue(int $character_id)
    {

        return Resource::collection(
            CharacterSkillQueue::with('type')->where('character_id', $character_id)->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/wallet-journal/{character_id}",
     *      tags={"Wallet"},
     *      summary="Get a paginated wallet journal for a character",
     *      description="Returns a wallet journal",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterWalletJournal")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWalletJournal(int $character_id)
    {

        return Resource::collection(CharacterWalletJournal::with('first_party', 'second_party')
            ->where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @OA\Get(
     *      path="/v2/character/wallet-transactions/{character_id}",
     *      tags={"Wallet"},
     *      summary="Get paginated wallet transactions for a character",
     *      description="Returns wallet transactions",
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
     *                  @OA\Items(ref="#/components/schemas/CharacterWalletTransaction")
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWalletTransactions(int $character_id)
    {

        return Resource::collection(
                CharacterWalletTransaction::with('party', 'type')
                    ->where('character_id', $character_id)
            ->paginate());
    }
}
