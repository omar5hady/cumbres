<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asign_proyecto extends Model
{
    protected $table = 'asign_proyectos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'asesor_id','proyecto_id'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
