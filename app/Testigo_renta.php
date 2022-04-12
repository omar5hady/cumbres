<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testigo_renta extends Model
{
    protected $table = 'testigos_rentas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['nombre'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
