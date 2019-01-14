<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avance extends Model
{
    protected $table = 'avances'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['lote_id','partida_id','avance','avance_porcentaje'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function lote(){
        return $this->belongsTo('App\Lote');
    }

    public function Partida(){
        return $this->belongsTo('App\Partida');
    }

}
