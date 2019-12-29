<?php

namespace App\Http\Resources\Resources\SuperMarchand;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Resources\Zone\UserResource;
class MarchandResources extends JsonResource
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
            'id'                => $this->id,
            'credit_virtuel'    => $this->credit_virtuel,
            'commission'        => $this->commission,
            'userable'          => [
                'user'          => [
                         
                        'id' => $this->userable()->user->id,
                        'nom' => $this->userable()->user->nom,
                        'prenom' => $this->userable()->user->prenom,
                        'sexe' => $this->userable()->user->sexe,
                        'telephone' => $this->userable()->user->telephone,
                        'adresse' => $this->userable()->user->adresse,
                        'email' => $this->userable()->user->email,
                    'commune'    =>[
                        'id'     => $this->userable()->user->commune->id,
                        'nom'    => $this->userable()->user->commune->nom
                    ],
                ],
            ],
        ];
    }
}
