<?php

namespace App\Http\Resources\Resources\Collection;

use App\Http\Resources\Resources\UserResources;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'profession' => $this->profession,
            'employeur' => $this->employeur,
            //'user' => new UserResources($this->user),
            'userable'       => [
                'user'       =>new UserResources($this->userable()->user)
            ],
            
        ];
    }
}
