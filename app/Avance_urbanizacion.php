<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avance_urbanizacion extends Model
{
    protected $table = 'avances_urbanizacion'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['lote_id','partida_id','avance'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

}
