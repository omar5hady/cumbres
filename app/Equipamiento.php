<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipamiento extends Model
{
    protected $table = 'equipamientos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['proveedor_id', 'equipamiento','activo'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function proveedor(){
        return $this->belongsTo('App\Proveedor');
    }
    
}
