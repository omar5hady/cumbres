<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_orden_pago extends Model
{
    protected $table = 'obs_ordenes_pagos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['solicitud_id','observacion','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function solicitud_pago(){
        return $this->belongsTo('App\Solicitudes_pago');
    }
}
