<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\DropboxFiles;

class DocProyectoResource extends JsonResource
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
            'lote_id' => $this->lote_id,
            'tipo' => $this->tipo,
            'carpeta' => $this->carpeta,
            'file' => $file
        ];
    }
}
