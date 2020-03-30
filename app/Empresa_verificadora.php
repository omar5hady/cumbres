<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa_verificadora extends Model
{
    protected $table = 'empresas_verificadoras'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['empresa','contacto','telefono'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function ruv(){
        return $this->hasMany('App\Ruv');
    }
}
