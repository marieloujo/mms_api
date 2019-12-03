<?php

namespace App\Http\Resources\Departement;

use App\Http\Resources\Departement\UserResources;
use Illuminate\Http\Resources\Json\JsonResource;

class CommuneResources extends JsonResource
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
            'users' =>UserResources::collection($this->users->where('usereable_type','App\\Models\\Marchand')),
        ];
    }
}
