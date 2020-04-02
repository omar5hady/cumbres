<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documentos_pago extends Model
{
    protected $table = 'documentos_pagos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [ 'solicitud_id',
                            'archivo',
                            'usuario',
                            'nombre'
                        ];

    //
    public function solicitudes_pago(){
        return $this->belongsTo('App\Solicitudes_pago');
    }
}
