<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /* return parent::toArray($request); */
        return [
            'image' => $this->image,
            'bio' => $this->bio,
            'address' => $this->address,
            'phone' => $this->phone,
        ];
    }
}
