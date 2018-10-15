<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['nombre','tipo','fraccionamiento_id','terreno','construccion','archivo'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function fraccionamiento(){
        return $this->belongsTo('App\Fraccionamiento');
    }

    public function lote(){
        return $this->hasMany('App\Lote');
    }

    public function precio_modelo(){
        return $this->belongsTo('App\precio_modelo');
    }
}
