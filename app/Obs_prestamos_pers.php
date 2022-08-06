<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_prestamos_pers extends Model
{
    protected $table = 'obs_prestamos_pers'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['prestamo_id','observacion','usuario_id'];
}
