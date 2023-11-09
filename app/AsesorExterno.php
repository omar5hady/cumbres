<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsesorExterno extends Model
{
    protected $table = 'asesor_externos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
                    'mobiliaria_id',
                    'nombre',
                    'apellido',
                    'direccion',
                    'celular',
                    'photo',
                    'f_ini',
                    'f_fin'
                ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function inmobiliaria(){
        return $this->belongsTo('App\Inmobiliaria');
    }
}
