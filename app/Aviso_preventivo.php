<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aviso_preventivo extends Model
{
    protected $table = 'aviso_preventivos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['contrato_id','notaria_id','fecha_solicitud'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }
}
