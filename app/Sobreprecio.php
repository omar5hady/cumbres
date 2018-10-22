<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sobreprecio extends Model
{
    protected $table = 'sobreprecios'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['id','nombre'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    


    public function sobreprecio(){
        return $this->belongsTo('App\Sobreprecio_etapa');
    }
}
