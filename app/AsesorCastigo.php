<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsesorCastigo extends Model
{
    protected $table = 'asesor_castigos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'asesor_id',
        'f_ini',
        'f_fin',
        'lead_id'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
