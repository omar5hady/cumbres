<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gasto_admin extends Model
{
    protected $table = 'gastos_admin'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['contrato_id','fecha','concepto','costo','observacion'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }
}
