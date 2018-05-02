<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class WalletTransactionResource.
 * @package Seat\Api\Http\Resources
 */
class WalletTransactionResource extends Resource
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
            'transaction_id' => $this->transaction_id,
            'date'           => $this->date,
            'type_id'        => $this->type_id,
            'location_id'    => $this->location_id,
            'unit_price'     => $this->unit_price,
            'quantity'       => $this->quantity,
            'client_id'      => $this->client_id,
            'is_buy'         => $this->is_buy,
            'is_personal'    => $this->is_personal,
            'journal_ref_id' => $this->journal_ref_id,
            'type'           => $this->type,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
