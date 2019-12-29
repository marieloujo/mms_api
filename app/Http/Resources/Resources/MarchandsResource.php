<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Resources\UserResources;
class MarchandsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'matricule' => $this->matricule,
            'credit_virtuel' => $this->credit_virtuel,
            'commission' => $this->commission,
            'userable'       => [
                'user'       => new UserResources($this->userable()->user)
            ],
        ];
    }
}
