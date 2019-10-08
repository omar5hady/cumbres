<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_entrega extends Model
{
    protected $table = 'obs_entregas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['entrega_id','comentario','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function entrega(){
        return $this->belongsTo('App\Entrega');
    }
}
