<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_solic_equipamiento extends Model
{
    protected $table = 'obs_solic_equipamientos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['solic_id','comentario','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public function solic_equipamiento(){
        return $this->belongsTo('App\Solic_equipamiento');
    }
}
