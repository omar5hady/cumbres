<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_inst_selec extends Model
{
    protected $table = 'obs_inst_selec'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['inst_selec_id','comentario','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
                           
    public function inst_seleccionada(){
        return $this->belongsTo('App\inst_seleccionada');
    }
}
