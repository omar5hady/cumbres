<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campania extends Model
{
    protected $table = 'campanias'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'nombre_campania',
        'medio_digital',
        'fecha_ini',
        'fecha_fin'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function lead(){
        return $this->hasMany('App\Digital_lead');
    }
}
