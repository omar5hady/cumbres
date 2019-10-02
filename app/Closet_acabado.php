<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Closet_acabado extends Model
{
    protected $table = 'closet_acabados'; // se referencia a que tabla pertenece el modelo
    protected $fillable = ['id',
                //Puertas alineados
                'p_ali_der', 'p_ali_izq', 'p_ali_princ', 'p_ali_baja',
                //Puertas limpieza
                'p_limp_der', 'p_limp_izq', 'p_limp_princ','p_limp_baja',
                //Puertas silicon
                'p_sil_der', 'p_sil_izq', 'p_sil_princ', 'p_sil_baja',
                //Cajones alineados
                'c_ali_der', 'c_ali_izq', 'c_ali_princ', 'c_ali_baja',
                //Cajones cantos
                'c_cant_der', 'c_cant_izq', 'c_cant_princ', 'c_cant_baja',
                //Cajones uniones
                'c_union_der', 'c_union_izq', 'c_union_princ', 'c_union_baja',
                //Cajones silicon
                'c_sil_der', 'c_sil_izq', 'c_sil_princ', 'c_sil_baja',
                //Cajones limpieza
                'c_limp_der', 'c_limp_izq', 'c_limp_princ', 'c_limp_baja',
                //Cajones tornillos
                'c_torn_der', 'c_torn_izq', 'c_torn_princ', 'c_torn_baja',
                //Cajones parches
                'c_parch_der', 'c_parch_izq', 'c_parch_princ', 'c_parch_baja'
            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function recep_equipamiento(){
        return $this->belongsTo('App\Recep_equipamiento');
    }
}
