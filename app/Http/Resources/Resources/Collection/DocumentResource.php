<?php

namespace App\Http\Resources\Resources\Collection;

use Illuminate\Http\Resources\Json\JsonResource;


class DocumentResource extends JsonResource
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
            'url' => $this->url,
        ];
    }
}
