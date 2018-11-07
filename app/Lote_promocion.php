<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lote_promocion extends Model
{
    protected $table = 'lotes_promocion'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['lote_id','promocion_id'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
                           
    public function promocion(){
        return $this->belongsTo('App\Promocion');
    }

    public function lote(){
        return $this->belongsTo('App\Lote');
    }
}
