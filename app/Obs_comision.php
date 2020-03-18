<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_comision extends Model
{
    protected $table = 'obs_comisiones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['contrato_id','comentario','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }
}
