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

    public function lote(){
        return $this->hasMany('App\Lote');
    }

    public function terreno(){
        return $this->hasMany('App\Terreno');
    }

    public function manzana(){
        return $this->hasMany('App\Manzana');
    }

    public function precio_etapa(){
        return $this->belongsTo('App\Precio_etapa');
    }
}
