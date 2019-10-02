<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cocina_otra extends Model
{
    protected $table = 'cocina_otras'; // se referencia a que tabla pertenece el modelo
    protected $fillable = ['id',
                //Estufa
                'estufa_instalacion','estufa_pzas_extra',
                'estufa_manuales','estufa_danos',
                //Tarja
                'tarja_danos','tarja_pzas_extra'
            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function recep_equipamiento(){
        return $this->belongsTo('App\Recep_equipamiento');
    }
}
