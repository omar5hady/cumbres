<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Closet_otro extends Model
{
    protected $table = 'closet_otros'; // se referencia a que tabla pertenece el modelo
    protected $fillable = ['id',
                //Paredes daños
                'pared_dan_der','pared_dan_izq','pared_dan_princ','pared_dan_baja',
                //Paredes limpieza
                'pared_limp_der','pared_limp_izq','pared_limp_princ','pared_limp_baja',
                //Closet Cenefa sup
                'clo_censup_der','clo_censup_izq','clo_censup_princ','clo_censup_baja',
                //Closet Cenefa inf
                'clo_ceninf_der','clo_ceninf_izq','clo_ceninf_princ','clo_ceninf_baja',
                //Closet color madera
                'clo_madera_der','clo_madera_izq','clo_madera_princ','clo_madera_baja',
                //Closet Alinec jalad
                'clo_alin_der','clo_alin_izq','clo_alin_princ','clo_alin_baja',
                //Closet pandeadura
                'clo_pande_der','clo_pande_izq','clo_pande_princ','clo_pande_baja',
                //Closet soporte
                'clo_soporte_der','clo_soporte_izq','clo_soporte_princ','clo_soporte_baja'
            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function recep_equipamiento(){
        return $this->belongsTo('App\Recep_equipamiento');
    }
}
