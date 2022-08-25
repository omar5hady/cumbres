<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class PensionFondoResource extends JsonResource
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
            'usuario' => new PersonalResource($this->usuario),
            'saldo' => $this->saldo,
            'pagos' => $this->pagos($request),
        ];
    }
}
