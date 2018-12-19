<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $table = 'observaciones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['lote_id','comentario','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
                           
    public function lote(){
        return $this->belongsTo('App\Lote');
    }

}
