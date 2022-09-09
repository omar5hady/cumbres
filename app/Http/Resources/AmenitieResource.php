<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AmenitieResource extends JsonResource
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
            'amenidad' => $this->amenidad,
            'etapa_id' => $this->etapa_id,
        ];
    }
}
