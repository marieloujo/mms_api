<?php

namespace App\Http\Resources\Resources\Collection;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class NotificationsUserResource extends JsonResource
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
            'data' => $this->data['data'] ,  
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
