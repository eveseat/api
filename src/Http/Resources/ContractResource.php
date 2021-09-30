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

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ContractResource.
 *
 * @package Seat\Api\Http\Resources
 */
class ContractResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'contract_id'      => $this->contract_id,
            'type'             => $this->detail->type,
            'status'           => $this->detail->status,
            'title'            => $this->detail->title,
            'for_corporation'  => $this->detail->for_corporation,
            'availability'     => $this->detail->availability,
            'date_issued'      => $this->detail->date_issued,
            'date_expired'     => $this->detail->date_expired,
            'date_accepted'    => $this->detail->date_accepted,
            'days_to_complete' => $this->detail->days_to_complete,
            'date_completed'   => $this->detail->date_completed,
            'price'            => $this->detail->price,
            'reward'           => $this->detail->reward,
            'collateral'       => $this->detail->collateral,
            'buyout'           => $this->detail->buyout,
            'volume'           => $this->detail->volume,
            'issuer'           => $this->detail->issuer,
            'assignee'         => $this->detail->assignee,
            'acceptor'         => $this->detail->acceptor,
            'bids'             => $this->detail->bids,
            'lines'            => $this->detail->lines,
            'start_location'   => $this->detail->start_location,
            'end_location'     => $this->detail->end_location,
        ];
    }
}
