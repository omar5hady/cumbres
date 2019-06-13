<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hist_visita extends Model
{
    protected $table = 'hist_visitas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['contrato_id','fecha_visita','observacion'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }
}
