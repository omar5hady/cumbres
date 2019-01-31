<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'nombre','descripcion','condicion'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public $timestamps = false;

    public function users(){
        return $this->hasMany('App\User');
    }
}
