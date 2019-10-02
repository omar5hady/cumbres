<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cocina_puerta extends Model
{
    protected $table = 'cocina_puertas'; // se referencia a que tabla pertenece el modelo
    protected $fillable = ['id',
                //Puertas
                'puerta_danos','puerta_tornillos','puerta_abatimiento',
                'puerta_limpieza','puerta_jaladera','puerta_gomas',
                //Puertas
                'cajones_uniones','cajones_silicon',
                'cajones_limpieza','cajones_jaladeras','cajones_cantos',
                'cajones_rieles','cajones_estantes','cajones_pzas_comp',
                //Alacenas
                'alacena_entrepano','alacena_pistones','alacena_jaladeras',
                'alacena_micro','alacena_cantos','alacena_limpieza','alacena_parches'
            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function recep_equipamiento(){
        return $this->belongsTo('App\Recep_equipamiento');
    }
}
