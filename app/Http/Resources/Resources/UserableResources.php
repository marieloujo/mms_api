<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserableResources extends JsonResource
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
            'userable_id' => $this->userable_id,
            'userable_type' => $this->userable_type,
            'userable' => $this->verifData($this->userable_type), 
            'user' => [
                'id' => $this->user->id,
                'nom' => $this->user->nom,
                'prenom' => $this->user->prenom,
                'sexe' => $this->user->sexe,
                'telephone' => $this->user->telephone,
                'adresse' => $this->user->adresse,
                'actif' => $this->user->actif == 1 ? true : false,
                'prospect' => $this->user->prospect == 1 ? true : false,
                'situation_matrimoniale' => $this->user->situation_matrimoniale,
                'date_naissance' => $this->user->date_naissance,
                'email' => $this->user->email,
                'commune' => [
                    'id' => $this->user->commune->id,
                    'nom' => $this->user->commune->nom,
                    'departement' => [
                        'id' => $this->user->commune->departement->id,
                        'nom' => $this->user->commune->departement->nom,
                        'code' => $this->user->commune->departement->code,
                    ]
                ]
            ],
        ];
    }

    public function verifData($modele){
        if ($modele == "App\\Models\\Assurer") {
            return [
                'id' => $this->userable->id,
                'profession' => $this->userable->profession,
                'employeur' => $this->userable->employeur,
                'etat' => $this->userable->etat == 1 ? true : false,
            ];
        } else {
            return $this->userable;
        }
    }
}
