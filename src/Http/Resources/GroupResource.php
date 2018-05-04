<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class GroupResource.
 * @package Seat\Api\Http\Resources
 */
class GroupResource extends Resource
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
            'id'    => $this->id,
            'users' => $this->users->map(function ($user) {

                return [
                    'active'       => $user->active,
                    'character_id' => $user->id,
                    'name'         => $user->name,
                ];
            }),
        ];
    }
}
