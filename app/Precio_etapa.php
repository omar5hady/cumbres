<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio_etapa extends Model
{
    protected $table = 'precios_etapas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fraccionamiento_id','etapa_id','precio_excedente'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
                           
    public function fraccionamiento(){
        return $this->hasMany('App\Fraccionamiento');
    }

    public function etapa(){
        return $this->hasMany('App\Etapa');
    }

    public function precio_modelo(){
        return $this->belongsTo('App\Precio_modelo');
    }
}
