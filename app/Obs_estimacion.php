<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_estimacion extends Model
{
    protected $table = 'obs_estimaciones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['aviso_id','observacion','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
