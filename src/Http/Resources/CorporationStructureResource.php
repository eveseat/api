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
            // name = "playerInputString" (not 'Athanor' / 'Fortizar' / ...) 
            // i feel like it would be in CorporationAsset, since you can't get a Structure Name if it is not public
            'name'=> $this->info->name, 

            // type_name = "Athanor"
            // nothing returns the 'name' in invType Model, and i couldn't find the create_inType to get the columns names
            // so my bet is there's a column 'name' in invType Table
            // but i'd have to put the type_id which is 35835 for Athanor f.ex
            // so i'm lost about how the thingy gets it, if it ever gets it this way... i feel like there's something missing
            'type_name'=> $this->type->typeName
            // what is the difference between 'type_id' and 'typeID' btw ?
            
            // system_name = "Jita"
            'system_name'=>$this->solar_system->name,

            // in base corporation_structure Table
            'type_id'=> $this->type_id, // can be skipped if i get name (string)
            'system_id'=> $this->system_id, // can be skipped if i get name (string)

            'structure_id'=> $this->structure_id,
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
