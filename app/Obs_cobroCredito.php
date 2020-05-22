<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_cobroCredito extends Model
{
    protected $table = 'obs_cobro_credito'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['credito_id','comentario','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public function inst_seleccionada(){
        return $this->belongsTo('App\inst_seleccionada');
    }
}
