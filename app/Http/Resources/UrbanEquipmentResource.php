<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UrbanEquipmentResource extends JsonResource
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
            'fraccionamiento_id'=> $this->fraccionamiento_id,
            'categoria'=> $this->categoria,
            'nombre'=> $this->nombre,
            'descripcion' => $this->descripcion
        ];
    }
}
