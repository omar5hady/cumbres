<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\DropboxFiles;

class DocSolicPagosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $file = DropboxFiles::findOrFail($this->file_id);
        return [
            'id' => $this->id,
            'solic_id' => $this->solic_id,
            'tipo' => $this->tipo,
            'carpeta' => $this->carpeta,
            'file' => $file
        ];
    }
}
