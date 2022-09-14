<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationLoteResource extends JsonResource
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
            'lote_id' => $this->lote_id,
            'general' => $this->general,
            'subconcepto' => $this->subconcepto,
            'descripcion' => $this->descripcion,
        ];
    }
}
