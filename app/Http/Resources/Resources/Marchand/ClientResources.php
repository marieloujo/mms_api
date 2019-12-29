<?php

namespace App\Http\Resources\Resources\Marchand;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResources extends JsonResource
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
            'id' => $this->client->id,
            'profession' => $this->client->profession, 
            'userable' =>   [
                'user' => [
                    'id' => $this->client->userable()->user->id,
                    'nom' => $this->client->userable()->user->nom,
                    'prenom' =>$this->client->userable()->user->prenom,
                    'sexe' =>$this->client->userable()->user->sexe,
                    'telephone' =>$this->client->userable()->user->telephone,
                    'adresse' =>$this->client->userable()->user->adresse, 
                    'commune' =>[
                        'id' =>$this->client->userable()->user->commune->id,
                        'nom' =>$this->client->userable()->user->commune->nom
                    ],
                ]
            ]
        ];
    }
}
