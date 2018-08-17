<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['ciudad','municipio','estado','cp','colonia'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
