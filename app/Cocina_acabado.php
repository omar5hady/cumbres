<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cocina_acabado extends Model
{
    protected $table = 'cocina_acabados'; // se referencia a que tabla pertenece el modelo
    protected $fillable = ['id',
                //Cubierta 
                'cubierta_acab_uniones',
                'cubierta_acab_silicon',
                'cubierta_acab_cortes',
                //Puertas
                'puerta_acab_alineados',
                'puerta_acab_cantos'
            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function recep_equipamiento(){
        return $this->belongsTo('App\Recep_equipamiento');
    }
}
