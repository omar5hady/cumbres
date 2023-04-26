<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PersonalResource;

class MedicalRecordResource extends JsonResource
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
            'alerta' => $this->alerta,
            'tipo_sangre' => $this->tipo_sangre,
            'estatura' => $this->estatura,
            'alergias' => $this->alergias,
            'regimen_alimenticio' => $this->regimen_alimenticio,
            'historial' => $this->historial($request),
            'afiliaciones' => $this->afiliaciones(),
            'vaccines' => $this->vaccines(),
            'contacts' => $this->contacts()
        ];
    }
}
