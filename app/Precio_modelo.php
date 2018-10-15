<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio_modelo extends Model
{
    protected $table = 'precios_modelos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['precio_etapa_id','modelo_id','precio_modelo'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
                           
    public function precioEtapa(){
        return $this->hasMany('App\Precio_etapa');
    }

    public function Modelo(){
        return $this->hasMany('App\Modelo');
    }
}
