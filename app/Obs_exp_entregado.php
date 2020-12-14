<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_exp_entregado extends Model
{
    protected $table = 'obs_exp_entregados'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['contrato_id','observacion','usuario'];
}
