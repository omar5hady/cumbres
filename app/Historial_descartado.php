<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historial_descartado extends Model
{
    protected $table = 'historial_descartados'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['vendedor_id','cliente_id'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public function inst_seleccionada(){
        return $this->belongsTo('App\inst_seleccionada');
    }
}
