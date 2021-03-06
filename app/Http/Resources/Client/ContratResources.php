<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\Collection\AssurerResource;
use App\Http\Resources\Collection\MarchandResource;
use App\Http\Resources\Collection\PortefeuilleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ContratResources extends JsonResource
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
            'id' => $this->id,
             'numero_contrat' => $this->numero_contrat,
            'garantie' => $this->garantie,
            'prime' => $this->prime,
            'duree' => $this->duree,
            'date_debut' => $this->date_debut,
            'date_echeance' => $this->date_echeance,
            'date_effet' => $this->date_effet,
            'date_fin' => $this->date_fin,
            'fin' => $this->fin == 1 ? true : false ,
            'valider' => $this->valider == 1 ? true : false ,
            'marchand' => new MarchandResource($this->marchand),
            'assure' =>new AssurerResource($this->assure),
            'portefeuilles' => PortefeuilleResource::collection($this->portefeuilles), 
        ];
    }
}
