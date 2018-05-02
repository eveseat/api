<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class CorporationHistoryResource.
 * @package Seat\Api\Http\Resources
 */
class CorporationHistoryResource extends Resource
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

            'start_date'     => $this->start_date,
            'corporation_id' => $this->corporation_id,
            'is_deleted'     => $this->is_deleted,
            'record_id'      => $this->record_id,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
