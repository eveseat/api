<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class SkillsResource.
 * @package Seat\Api\Http\Resources
 */
class SkillsResource extends Resource
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
            'skill_id'             => $this->skill_id,
            'skillpoints_in_skill' => $this->skillpoints_in_skill,
            'trained_skill_level'  => $this->trained_skill_level,
            'active_skill_level'   => $this->active_skill_level,
            'type'                 => $this->type,
        ];
    }
}
