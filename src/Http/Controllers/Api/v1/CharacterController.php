<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Api\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Seat\Services\Repositories\Character\CharacterRepository;

/**
 * Class CharacterController
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class CharacterController extends Controller
{

    // There is an unintended sideeffect of this trait
    // actually introducing more API methods due to
    // the naming scheme of the methods.
    use CharacterRepository;

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
    public function getInfo($character_id)
    {

        return response()->json(
            $this->getCharacterInformation($character_id));
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
