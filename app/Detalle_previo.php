<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_previo extends Model
{
    protected $table = 'detalles_previos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['rev_previas_id','identificador','concepto',
                            'observacion'
                            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function revision_previa(){
        return $this->belongsTo('App\Revision_previa');
    }
}
