<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class CorporationStructureResource.
 * @package Seat\Api\Http\Resources
 */
class CorporationStructureResource extends Resource
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
            'name'=> $this->info->name, 
            'structure_id'=> $this->structure_id,
            
            'type_name'=> $this->type->typeName
            'type_id'=> $this->type_id,
            
            'system_name'=>$this->solar_system->name,
            'system_id'=> $this->system_id,
            
            ////
            //
            // Proposing :
            //
            // 'structure' => [
            //      'name' => $this->info->name,
            //      'id' => $this->structure_id
            // ],
            //
            // 'type' => [
            //      'name' => $this->type->typeName,
            //      'id' => $this->type_id
            // ],
            //
            // 'solar_system' => [
            //      'name' => $this->solar_system->name,
            //      'id' => $this->system_id
            // ],
            //
            ////
            
            'fuel_expires'=>$this->fuel_expires,
            'state'=>$this->state,
            'state_timer_start'=>$this->state_timer_start,
            'state_timer_end'=>$this->state_timer_end,
            'unanchors_at'=>$this->unanchors_at,
            'reinforce_hour'=>$this->reinforce_hour,
            'next_reinforce_hour'=>$this->next_reinforce_hour,
            'next_reinforce_apply'=>$this->next_reinforce_apply,    
        ];
    }
}
