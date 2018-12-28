<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table = 'partidas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['partida','lote_id','costo',
            'porcentaje','avance','porcentaje_avance'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function lote(){
        return $this->belongsTo('App\Lote');
    }

    
}
