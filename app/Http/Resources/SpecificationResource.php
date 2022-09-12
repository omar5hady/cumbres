<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationResource extends JsonResource
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
            'modelo_id' => $this->modelo_id,
            'general' => $this->general,
            'subconcepto' => $this->subconcepto,
            'descripcion' => $this->descripcion,
        ];
    }
}
