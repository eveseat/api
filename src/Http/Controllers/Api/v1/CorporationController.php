<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

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
use Seat\Services\Repositories\Corporation\CorporationRepository;

/**
 * Class CorporationController
 * @package Seat\Api\Http\Controllers\Api\v1
 */
class CorporationController extends Controller
{

    use CorporationRepository;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {

        return response()->json(
            $this->getAllCorporationSheets());
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssets($corporation_id)
    {

        return response()->json(
            $this->getCorporationAssets($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssetsByLocation($corporation_id)
    {

        return response()->json(
            $this->getCorporationAssetByLocation($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBookmarks($corporation_id)
    {

        return response()->json(
            $this->getCorporationBookmarks($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContacts($corporation_id)
    {

        return response()->json(
            $this->getCorporationContacts($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContracts($corporation_id)
    {

        return response()->json(
            $this->getCorporationContracts($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDivisions($corporation_id)
    {

        return response()->json(
            $this->getCorporationDivisions($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndustry($corporation_id)
    {

        return response()->json(
            $this->getCorporationIndustry($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKillmails($corporation_id)
    {

        return response()->json(
            $this->getCorporationKillmails($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMarketOrders($corporation_id)
    {

        return response()->json(
            $this->getCorporationMarketOrders($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMemberSecurity($corporation_id)
    {

        return response()->json(
            $this->getCorporationMemberSecurity($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMemberSecurityLogs($corporation_id)
    {

        return response()->json(
            $this->getCorporationMemberSecurityLogs($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMemberSecurityTitles($corporation_id)
    {

        return response()->json(
            $this->getCorporationMemberSecurityTitles($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMemberTracking($corporation_id)
    {

        return response()->json(
            $this->getCorporationMemberTracking($corporation_id));
    }

    /**
     * @param $coporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPocos($coporation_id)
    {

        return response()->json(
            $this->getCorporationCustomsOffices($coporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSheet($corporation_id)
    {

        return response()->json(
            $this->getCorporationSheet($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStandings($corporation_id)
    {

        return response()->json(
            $this->getCorporationStandings($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStarbases($corporation_id, $starbase_id = null)
    {

        return response()->json(
            $this->getCorporationStarbases($corporation_id, $starbase_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWalletDivisions($corporation_id)
    {

        return response()->json(
            $this->getCorporationWalletDivisions($corporation_id));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWalletJournal($corporation_id)
    {

        return response()->json(
            $this->getCorporationWalletJournal($corporation_id, 1000));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWalletTransactions($corporation_id)
    {

        return response()->json(
            $this->getCorporationWalletTransactions($corporation_id));
    }

}
