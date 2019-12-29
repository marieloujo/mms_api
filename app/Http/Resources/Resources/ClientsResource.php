<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Resources\UserResources;
class ClientsResource extends JsonResource
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
            'profession' => $this->profession,
            'employeur' => $this->employeur,
            'userable'       => [
                'user'       => new UserResources($this->userable()->user)
            ],
            
        ];
    }
}
