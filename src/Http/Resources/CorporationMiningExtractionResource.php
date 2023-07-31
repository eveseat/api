<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class CorporationMiningExtractionResource.
 * @package Seat\Api\Http\Resources
 */
class CorporationMiningExtractionResource extends Resource
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
            'system_name'=>$this->moon->solar_system->name,
            'moon_name'=>$this->moon->name,
            'structure_name'=>$this->moon->extraction->structure->info->name,

            'extraction_start_time'=>$this->moon->extraction->extraction_start_time,
            'chunk_arrival_time'=>$this->moon->extraction->chunk_arrival_time,
            'natural_decay_time'=>$this->moon->extraction->natural_decay_time,

            'content'=>$this->content
        ];
    }
}
