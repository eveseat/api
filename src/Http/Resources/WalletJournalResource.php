<?php

namespace Seat\Api\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class WalletJournalResource extends Resource
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

            'id'              => $this->id,
            'date'            => $this->date,
            'ref_type'        => $this->ref_type,
            'first_party_id'  => $this->first_party_id,
            'first_party'     => $this->first_party,
            'second_party_id' => $this->second_party_id,
            'second_party'    => $this->second_party,
            'amount'          => $this->amount,
            'balance'         => $this->balance,
            'reason'          => $this->reason,
            'tax_receiver_id' => $this->tax_receiver_id,
            'tax'             => $this->tax,
            'context_id'      => $this->context_id,
            'context_id_type' => $this->context_id_type,
            'description'     => $this->description,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
