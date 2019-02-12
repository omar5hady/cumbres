<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipo_credito extends Model
{
    protected $table = 'tipos_creditos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['nombre','institucion_fin'];
        //asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
