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
use Seat\Api\Http\Resources\CharacterSheetResource;
use Seat\Api\Http\Resources\ContactResource;
use Seat\Api\Http\Resources\ContractResource;
use Seat\Api\Http\Resources\CorporationHistoryResource;
use Seat\Api\Http\Resources\IndustryResource;
use Seat\Api\Http\Resources\JumpcloneResource;
use Seat\Api\Http\Resources\KillmailResource;
use Seat\Api\Http\Resources\MailResource;
use Seat\Api\Http\Resources\MarketOrderResource;
use Seat\Api\Http\Resources\NotificationResource;
use Seat\Api\Http\Resources\SkillQueueResource;
use Seat\Api\Http\Resources\SkillsResource;
use Seat\Api\Http\Resources\WalletJournalResource;
use Seat\Api\Http\Resources\WalletTransactionResource;
use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\Character\CharacterCorporationHistory;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Eveapi\Models\Clones\CharacterJumpClone;
use Seat\Eveapi\Models\Contacts\CharacterContact;
use Seat\Eveapi\Models\Contracts\CharacterContract;
use Seat\Eveapi\Models\Industry\CharacterIndustryJob;
use Seat\Eveapi\Models\Killmails\CharacterKillmail;
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
     * @SWG\Get(
     *      path="/character/assets/{character_id}",
     *      tags={"Assets"},
     *      summary="Get a paginated list of a assets for a character",
     *      description="Returns a list of assets",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @SWG\Get(
     *      path="/character/assets/{character_id}/{item_id}",
     *      tags={"Assets"},
     *      summary="Get a specific asset",
     *      description="Returns list of assets",
     *      security={
     *          {"ApiKeyAuth": {}}
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
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *     )
     *
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
     *      description="Returns a list of bookmarks",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     *      summary="Get a paginated list of contacts for a character",
     *      description="Returns list of contacs",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     *      path="/character/contracts/{character_id}",
     *      tags={"Contracts"},
     *      summary="Get a paginated list of contracts for a character",
     *      description="Returns list of contracts",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getContracts(int $character_id)
    {

        return ContractResource::collection(CharacterContract::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/character/corporation-history/{character_id}",
     *      tags={"Character"},
     *      summary="Get the corporation history for a character",
     *      description="Returns a corporation history",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCorporationHistory(int $character_id)
    {

        return CorporationHistoryResource::collection(CharacterCorporationHistory::where('character_id', $character_id)
            ->get());
    }

    /**
     * @SWG\Get(
     *      path="/character/industry/{character_id}",
     *      tags={"Industry"},
     *      summary="Get a paginated list of industry jobs for a character",
     *      description="Returns list of industry jobs",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     *      path="/character/jump-clones/{character_id}",
     *      tags={"Character"},
     *      summary="Get a paginated list of jump clones for a character",
     *      description="Returns list of jump clones",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getJumpClones(int $character_id)
    {

        return JumpcloneResource::collection(CharacterJumpClone::where('character_id', $character_id)
            ->get());
    }

    /**
     * @SWG\Get(
     *      path="/character/killmails/{character_id}",
     *      tags={"Killmails"},
     *      summary="Get a paginated list of killmails for a character",
     *      description="Returns list of killmails",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getKillmails(int $character_id)
    {

        return KillmailResource::collection(CharacterKillmail::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/character/mail/{character_id}",
     *      tags={"Character"},
     *      summary="Get a paginated list of mail for a character",
     *      description="Returns mail",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMail(int $character_id)
    {

        return MailResource::collection(MailHeader::where('character_id', $character_id)->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/character/market-orders/{character_id}",
     *      tags={"Market"},
     *      summary="Get a paginated list of market orders for a character",
     *      description="Returns list of market orders",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMarketOrders(int $character_id)
    {

        return MarketOrderResource::collection(CharacterOrder::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/character/notifications/{character_id}",
     *      tags={"Character"},
     *      summary="Get a paginated list of notifications for a character",
     *      description="Returns a list of notifications",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @SWG\Get(
     *      path="/character/sheet/{character_id}",
     *      tags={"Character"},
     *      summary="Get the character sheet for a character",
     *      description="Returns a character sheet",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Seat\Api\Http\Resources\CharacterSheetResource
     */
    public function getSheet(int $character_id)
    {

        return new CharacterSheetResource(CharacterInfo::findOrFail($character_id));
    }

    /**
     * @SWG\Get(
     *      path="/character/skills/{character_id}",
     *      tags={"Character"},
     *      summary="Get the skills for a character",
     *      description="Returns character skills",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getSkills(int $character_id)
    {

        return SkillsResource::collection(CharacterSkill::where('character_id', $character_id)->get());
    }

    /**
     * @SWG\Get(
     *      path="/character/skill-queue/{character_id}",
     *      tags={"Character"},
     *      summary="Get a list of characters skill queue",
     *      description="Returns a skill queue",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getSkillQueue(int $character_id)
    {

        return SkillQueueResource::collection(CharacterSkillQueue::where('character_id', $character_id)->get());
    }

    /**
     * @SWG\Get(
     *      path="/character/wallet-journal/{character_id}",
     *      tags={"Wallet"},
     *      summary="Get a paginated wallet journal for a character",
     *      description="Returns a wallet journal",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWalletJournal(int $character_id)
    {

        return WalletJournalResource::collection(CharacterWalletJournal::where('character_id', $character_id)
            ->paginate());
    }

    /**
     * @SWG\Get(
     *      path="/character/wallet-transactions/{character_id}",
     *      tags={"Wallet"},
     *      summary="Get paginated wallet transactions for a character",
     *      description="Returns wallet transactions",
     *      security={
     *          {"ApiKeyAuth": {}}
     *      },
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
     * @param int $character_id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWalletTransactions(int $character_id)
    {

        return WalletTransactionResource::collection(CharacterWalletTransaction::where('character_id', $character_id)
            ->paginate());
    }
}
