<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manzana extends Model
{
    protected $table = 'manzanas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['manzana','fraccionamiento_id'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function fraccionamiento(){
        return $this->belongsTo('App\Fraccionamiento');
    }
}
