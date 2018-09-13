<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['nombre','direccion','cp','colonia','estado','ciudad','telefono','ext'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function personal(){
        return $this->hasMany('App\Personal');
    }

    public function lote(){
        return $this->hasMany('App\Lote');
    }
}
