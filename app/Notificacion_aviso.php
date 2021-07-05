<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificacion_aviso extends Model
{
    protected $table = 'notificaciones_avisos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'mensaje',
        'user_id',
        'enterado'
    ];
}
