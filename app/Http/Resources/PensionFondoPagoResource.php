<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PensionFondoPagoResource extends JsonResource
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
            'monto' => $this->monto,
            'concepto' => $this->concepto,
            'tipo_movimiento' => $this->tipo_movimiento,
            'fecha_movimiento' => $this->fecha_movimiento,
            'status' => $this->status,
        ];
    }
}
