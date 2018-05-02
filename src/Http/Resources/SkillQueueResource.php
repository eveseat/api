<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SkillQueueResource extends Resource
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
            'skill_id'          => $this->skill_id,
            'finish_date'       => $this->finish_date,
            'start_date'        => $this->start_date,
            'finished_level'    => $this->finished_level,
            'queue_position'    => $this->queue_position,
            'training_start_sp' => $this->training_start_sp,
            'level_end_sp'      => $this->level_end_sp,
            'level_start_sp'    => $this->level_start_sp,
            'type'              => $this->type,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
