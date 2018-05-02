<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ContractResource.
 * @package Seat\Api\Http\Resources
 */
class ContractResource extends Resource
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
            'contract_id' => $this->contract_id,
            'detail'      => $this->detail,
        ];
    }
}
