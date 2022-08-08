<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class PersonalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    /**
     * The function toArray() is used to convert the object into an array
     * 
     * @param request The incoming HTTP request.
     * 
     * @return The data that is being returned is the id, name, rfc and photo of the user.
     */
    public function toArray($request)
    {

        $user = User::findOrFail($this->id);
        return [
            'id' => $this->id,
            'nombre' => $this->nombre.' '.$this->apellidos,
            'rfc' => $this->rfc,
            'foto' => $user->foto_user
        ];
    }
}
