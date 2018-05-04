<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class BookmarkResource.
 * @package Seat\Api\Http\Resources
 */
class BookmarkResource extends Resource
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
            'bookmark_id' => $this->bookmark_id,
            'folder_id'   => $this->folder_id,
            'folder_name' => $this->folder->name,
            'system'      => $this->system,
            'created'     => $this->created,
            'label'       => $this->label,
            'notes'       => $this->notes,
            'location_id' => $this->location_id,
            'creator_id'  => $this->creator_id,
            'item_id'     => $this->item_id,
            'type_id'     => $this->type_id,
            'x'           => $this->x,
            'y'           => $this->y,
            'z'           => $this->z,
            'map_id'      => $this->map_id,
        ];
    }
}
