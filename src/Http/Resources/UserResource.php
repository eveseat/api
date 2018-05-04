<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserResource.
 * @package Seat\Api\Http\Resources
 */
class UserResource extends Resource
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

            'id'                       => $this->id,
            'name'                     => $this->name,
            'email'                    => $this->email,
            'active'                   => $this->active,
            'character_owner_hash'     => $this->character_owner_hash,
            'last_login'               => $this->last_login,
            'last_login_source'        => $this->last_login_source,
            'group_ids'                => $this->groups->map(function ($group) {

                return $group->id;
            }),
            'associated_character_ids' => $this->associatedCharacterIds(),
        ];
    }
}
