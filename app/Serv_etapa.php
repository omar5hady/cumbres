<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serv_etapa extends Model
{
    protected $table = 'serv_etapas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['etapa_id','serv_id'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function etapa(){
        return $this->belongsTo('App\Etapa');
    }

    public function servicio(){
        return $this->belongsTo('App\Servicio');
    }
}
