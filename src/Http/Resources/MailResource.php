<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class MailResource.
 * @package Seat\Api\Http\Resources
 */
class MailResource extends Resource
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
            'mail_id'    => $this->mail_id,
            'subject'    => $this->subject,
            'from'       => $this->from,
            'timestamp'  => $this->timestamp,
            'is_read'    => $this->is_read,
            'body'       => $this->body->body,
            'recipients' => $this->recipients->map(function ($recipient) {

                return [
                    'recipient_id'   => $recipient->recipient_id,
                    'recipient_type' => $recipient->recipient_type,
                ];
            }),
        ];
    }
}
