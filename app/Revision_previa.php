<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revision_previa extends Model
{
    protected $table = 'revisiones_previas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['observaciones','id_contratista'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }

    public function detalle_previo(){
        return $this->hasMany('App\Detalle_previo');
    }
}
