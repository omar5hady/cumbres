<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_mant_vehiculo extends Model
{
    protected $table = 'obs_mant_vehiculos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['mantenimiento_id','observacion','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
