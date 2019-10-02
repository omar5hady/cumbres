<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Closet_interior extends Model
{
    protected $table = 'closet_interiores'; // se referencia a que tabla pertenece el modelo
    protected $fillable = ['id',
                //Puertas tiradores
                'p_tira_der','p_tira_izq','p_tira_princ','p_tira_baja',
                //Puertas funcionamiento
                'p_func_der','p_func_izq','p_func_princ','p_func_baja',
                //Cajones jaladeras
                'c_jalad_der','c_jalad_izq','c_jalad_princ','c_jalad_baja',
                //Cajones rieles
                'c_riel_der','c_riel_izq','c_riel_princ','c_riel_baja',
                //Cajones estantes
                'c_estant_der','c_estant_izq','c_estant_princ','c_estant_baja',
                //Cajones entrepaños
                'c_entr_der','c_entr_izq','c_entr_princ','c_entr_baja',
                //Cajones tubos colga
                'c_tubos_der','c_tubos_izq','c_tubos_princ','c_tubos_baja',
                //Cajones daños
                'c_danos_der','c_danos_izq','c_danos_princ','c_danos_baja',
                //Cajones abre correct
                'c_correct_der','c_correct_izq','c_correct_princ','c_correct_baja',
                //Cajones pzas compl
                'c_pzasc_der','c_pzasc_izq','c_pzasc_princ','c_pzasc_baja',
                //Cajones abatimiento
                'c_abatim_der','c_abatim_izq','c_abatim_princ','c_abatim_baja',
                //Cajones visagras
                'c_visagras_der','c_visagras_izq','c_visagras_princ','c_visagras_baja'
            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function recep_equipamiento(){
        return $this->belongsTo('App\Recep_equipamiento');
    }
}
