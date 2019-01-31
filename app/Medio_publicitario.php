<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medio_publicitario extends Model
{
    protected $table = 'medios_publicitarios'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'nombre'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public $timestamps = false;

    public function clientes(){
        return $this->hasMany('App\Cliente');
    }
}
