<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liquidacion'; // se referencia a que tabla pertenece el modelo
    
    protected $fillable = ['id','interes_ord','interes_mor','fecha_ini_interes',
                            'nombre_aval','direccion','telefono',
                            'nombre_aval2','direccion2','telefono2'
                            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function expediente()
    {
        return $this->belongsTo('App\Expediente');
    }

}
