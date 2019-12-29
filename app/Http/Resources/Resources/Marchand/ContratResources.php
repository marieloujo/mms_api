<?php

namespace App\Http\Resources\Resources\Marchand;

use App\Http\Resources\Resources\Collection\AssurerResource;
use App\Http\Resources\Resources\Collection\ClientResource;
use App\Http\Resources\Resources\Collection\PortefeuilleResource;
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
            'assure' =>new AssurerResource($this->assure),
            'portefeuilles' => PortefeuilleResource::collection($this->portefeuilles), 
        ];
    }
}
