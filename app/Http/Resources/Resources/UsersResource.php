<?php

namespace App\Http\Resources\Resources;

use App\Http\Resources\Resources\Collection\UserableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'sexe' => $this->sexe,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'actif' => $this->actif == 1 ? true : false ,
            'prospect' => $this->prospect == 1 ? true : false ,
            'situation_matrimoniale' => $this->situation_matrimoniale,
            'date_naissance' => $this->date_naissance,
            'email' => $this->email,
            'commune' => [
                'id' => $this->commune->id,
                'nom' => $this->commune->nom,
                'departement' => [
                    'id' => $this->commune->departement->id,
                    'nom' => $this->commune->departement->nom,
                    'code' => $this->commune->departement->code,
                ] 
            ],
            'userables'       => UserableResource::collection($this->userables),
            
        ];
    }
}
