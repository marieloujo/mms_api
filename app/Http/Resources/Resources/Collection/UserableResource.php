<?php

namespace App\Http\Resources\Resources\Collection;

use Illuminate\Http\Resources\Json\JsonResource;

class UserableResource extends JsonResource
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
