<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_ruv extends Model
{
    protected $table = 'obs_ruvs'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['ruv_id','observacion','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function ruv(){
        return $this->belongsTo('App\Ruv');
    }
}
