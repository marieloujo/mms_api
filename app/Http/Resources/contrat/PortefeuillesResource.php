<?php

namespace App\Http\Resources\contrat;

use Illuminate\Http\Resources\Json\JsonResource;

class PortefeuillesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'montant' => $this->sum('montant')
        ];
    }
}
