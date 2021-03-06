<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComptesResource extends JsonResource
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
            'montant' => $this->montant,
            'compte' => $this->compte,
            'compteable_id' => $this->compteable_id,
            'compteable_type' => $this->compteable_type,
            'created_at' => $this->created_at,
        ];
    }
}
