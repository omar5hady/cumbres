<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fraccionamiento extends Model
{
    protected $table = 'fraccionamientos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['nombre','tipo_proyecto','calle','colonia','estado','ciudad'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function etapa(){
        return $this->hasMany('App\Etapa');
    }

    public function modelo(){
        return $this->hasMany('App\Modelo');
    }
}
