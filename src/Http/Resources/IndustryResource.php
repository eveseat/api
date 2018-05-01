<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class IndustryResource.
 * @package Seat\Api\Http\Resources
 */
class IndustryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
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
            'blueprint_type_id'      => $this->blueprint_type_id,
            'blueprint_location_id'  => $this->blueprint_location_id,
            'output_location_id'     => $this->output_location_id,
            'runs'                   => $this->runs,
            'cost'                   => $this->cost,
            'licensed_runs'          => $this->licensed_runs,
            'probability'            => $this->probability,
            'product_type_id'        => $this->product_type_id,
            'status'                 => $this->status,
            'duration'               => $this->duration,
            'start_date'             => $this->start_date,
            'end_date'               => $this->end_date,
            'pause_date'             => $this->pause_date,
            'completed_date'         => $this->completed_date,
            'completed_character_id' => $this->completed_character_id,
            'successful_runs'        => $this->successful_runs,
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
        ];
    }
}
