<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table = 'partidas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['partida','modelo_id','costo',
            'porcentaje'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function modelo(){
        return $this->belongsTo('App\Modelo');
    }

    public function avance(){
        return $this->hasMany('App\Avance');
    }

    
}
