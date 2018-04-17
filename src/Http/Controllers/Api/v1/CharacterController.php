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

namespace Seat\Api\Http\Controllers\Api\v1;

use Illuminate\Routing\Controller;
use Seat\Services\Repositories\Character\Assets;
use Seat\Services\Repositories\Character\Bookmarks;
use Seat\Services\Repositories\Character\Calendar;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Character\ChatChannels;
use Seat\Services\Repositories\Character\Contacts;
use Seat\Services\Repositories\Character\Contracts;
use Seat\Services\Repositories\Character\Implants;
use Seat\Services\Repositories\Character\Industry;
use Seat\Services\Repositories\Character\Info;
use Seat\Services\Repositories\Character\JumpClone;
use Seat\Services\Repositories\Character\Killmails;
use Seat\Services\Repositories\Character\Mail;
use Seat\Services\Repositories\Character\Market;
use Seat\Services\Repositories\Character\Notifications;
use Seat\Services\Repositories\Character\Pi;
use Seat\Services\Repositories\Character\Research;
use Seat\Services\Repositories\Character\Skills;
use Seat\Services\Repositories\Character\Standings;
use Seat\Services\Repositories\Character\Wallet;

/**
 * Class CharacterController.
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class CharacterController extends Controller
{
    // There is an unintended sideeffect of this trait
    // actually introducing more API methods due to
    // the naming scheme of the methods.
    use Assets;
    use Bookmarks;
    use Calendar;
    use Character;
    use ChatChannels;
    use Contacts;
    use Contracts;
    use Industry;
    use Info;
    use Implants;
    use JumpClone;
    use Killmails;
    use Mail;
    use Market;
    use Notifications;
    use Pi;
    use Research;
    use Skills;
    use Standings;
    use Wallet;

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssets($character_id)
    {

        return response()->json(
            $this->getCharacterAssets($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBookmarks($character_id)
    {

        return response()->json(
            $this->getCharacterBookmarks($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChannels($character_id)
    {

        return response()->json(
            $this->getCharacterChatChannelsFull($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContacts($character_id)
    {

        return response()->json(
            $this->getCharacterContacts($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndustry($character_id)
    {

        return response()->json(
            $this->getCharacterIndustry($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKillmails($character_id)
    {

        return response()->json(
            $this->getCharacterKillmails($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMarketOrders($character_id)
    {

        return response()->json(
            $this->getCharacterMarketOrders($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContracts($character_id)
    {

        return response()->json(
            $this->getCharacterContracts($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSheet($character_id)
    {

        return response()->json(
            $this->getCharacterSheet($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkills($character_id)
    {

        return response()->json(
            $this->getCharacterSkillsInformation($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkillInTraining($character_id)
    {

        return response()->json(
            $this->getCharacterSkillInTraining($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkillQueue($character_id)
    {

        return response()->json(
            $this->getCharacterSkilQueue($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWalletJournal($character_id)
    {

        return response()->json(
            $this->getCharacterWalletJournal($character_id, 1000));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWalletTransactions($character_id)
    {

        return response()->json(
            $this->getCharacterWalletTransactions($character_id, 1000));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmploymentHistory($character_id)
    {

        return response()->json(
            $this->getCharacterEmploymentHistory($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImplants($character_id)
    {

        return response()->json(
            $this->getCharacterImplants($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJumpClones($character_id)
    {

        return response()->json(
            $this->getCharacterJumpClones($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAccountInfo($character_id)
    {

        return response()->json(
            $this->getCharacterAccountInfo($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMail($character_id)
    {

        return response()->json(
            $this->getCharacterMail($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications($character_id)
    {

        return response()->json(
            $this->getCharacterNotifications($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPi($character_id)
    {

        return response()->json(
            $this->getCharacterPlanetaryColonies($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStandings($character_id)
    {

        return response()->json(
            $this->getCharacterStandings($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResearch($character_id)
    {

        return response()->json(
            $this->getCharacterResearchAgents($character_id));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendar($character_id)
    {

        return response()->json(
            $this->getCharacterUpcomingCalendarEvents($character_id));
    }
}
