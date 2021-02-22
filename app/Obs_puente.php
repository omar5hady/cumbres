<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_puente extends Model
{
    protected $table = 'obs_puentes'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['usuario','observacion','puente_id'];
}
