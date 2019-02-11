<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lugar_contacto extends Model
{
    protected $table = 'lugares_contacto'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['nombre','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
}
