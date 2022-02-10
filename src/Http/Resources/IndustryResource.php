<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

use Seat\Api\Http\Resources\Json\JsonResource;

class IndustryResource extends JsonResource
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
            'job_id'                 => $this->job_id,
            'installer_id'           => $this->installer_id,
            'facility_id'            => $this->facility_id,
            'station_id'             => $this->station_id,
            'activity_id'            => $this->activity_id,
            'blueprint_id'           => $this->blueprint_id,
            'blueprint_location_id'  => $this->blueprint_location_id,
            'output_location_id'     => $this->output_location_id,
            'runs'                   => $this->runs,
            'cost'                   => $this->cost,
            'licensed_runs'          => $this->licensed_runs,
            'probability'            => $this->probability,
            'status'                 => $this->status,
            'duration'               => $this->duration,
            'start_date'             => $this->start_date,
            'end_date'               => $this->end_date,
            'pause_date'             => $this->pause_date,
            'completed_date'         => $this->completed_date,
            'completed_character_id' => $this->completed_character_id,
            'successful_runs'        => $this->successful_runs,
            'blueprint'              => $this->blueprint,
            'product'                => $this->product,
        ];
    }
}
