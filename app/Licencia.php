<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{
    protected $table = 'licencias';
    protected $fillable = [
        'id', 'f_planos','f_planos_obra','f_ingreso', 'f_salida', 'num_licencia','perito_dro','avance',
        'term_ingreso','term_salida','cambios','foto_lic','num_acta','foto_acta','foto_predial','modelo_ant',
        'visita_avaluo','fecha_licencia','fecha_acta','fecha_predial'
    ];
 
    public $timestamps = false;
 
    public function lote()
    {
        return $this->belongsTo('App\Lote');
    }
}
