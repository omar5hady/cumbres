<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avaluo extends Model
{
    protected $table = 'avaluos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['contrato_id','fecha_solicitud','valor_requerido','observacion','fecha_recibido','resultado'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }
}
