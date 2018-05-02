<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class NotificationResource.
 * @package Seat\Api\Http\Resources
 */
class NotificationResource extends Resource
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
            'id'              => $this->id,
            'notification_id' => $this->notification_id,
            'type'            => $this->type,
            'sender_id'       => $this->sender_id,
            'sender_type'     => $this->sender_type,
            'timestamp'       => $this->timestamp,
            'is_read'         => $this->is_read,
            'text'            => $this->text,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
