<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamento'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id_departamento'; //Referenciar la llave primaria
    protected $fillable = ['departamento','user_alta'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
