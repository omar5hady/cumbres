<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PersonalResource;

class DonativoResource extends JsonResource
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
            'user_id' => $this->user_id,
            'usuario' => new PersonalResource($this->usuario),
            'descripcion' => $this->descripcion,
            'titulo' => $this->titulo,
            'f_entrega' => $this->f_entrega,
            'picture' => $this->picture,
            'status' => $this->status,
            'historial' => $this->historial($request),
            'reservation' => $this->findMe($request),
            'elegido' => $this->elegido($request)
        ];
    }
}
