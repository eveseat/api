<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ContactResource.
 * @package Seat\Api\Http\Resources
 */
class ContactResource extends Resource
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

            'contact_id'   => $this->contact_id,
            'standing'     => $this->standing,
            'contact_type' => $this->contact_type,
            'is_watched'   => $this->is_watched,
            'is_blocked'   => $this->is_blocked,
            'label_id'     => $this->label_id,
            'label_data'   => $this->label,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
