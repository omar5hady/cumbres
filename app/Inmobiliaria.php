<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inmobiliaria extends Model
{
    protected $table = 'inmobiliarias'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
                    'nombre',
                    'logo'
                ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function asesores(){
        return $this->hasMany('App\AsesorExterno');
    }
}
