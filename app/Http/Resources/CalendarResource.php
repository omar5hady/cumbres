<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->event_name. ' ('.$this->nombre.') '.$this->description,
            'event_name' => $this->event_name,
            'start' => $this->start_date,//.'T00:00:00',
            'end' => $this->end_date,
            'color' => $this->color,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'nombre' => $this->nombre.' '.$this->apellidos
        ];
    }
}
