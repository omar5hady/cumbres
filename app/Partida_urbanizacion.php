<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partida_urbanizacion extends Model
{
    protected $table = 'partidas_urbanizacion'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['partida'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
}
