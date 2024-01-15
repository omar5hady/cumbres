<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solic_equipamiento extends Model
{
    protected $table = 'solic_equipamientos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['lote_id','contrato_id','fecha_solicitud','costo','fecha_colocacion',
                            'anticipo','fecha_anticipo','equipamiento_id','status','control',
                            'liquidacion', 'fecha_liquidacion', 'avance', 'num_factura', 'render',
                            'fin_instalacion','anticipo_cand','liquidacion_cand'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function lote(){
        return $this->belongsTo('App\Lote');
    }

    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }

    public function equipamiento(){
        return $this->belongsTo('App\Equipamiento');
    }

    public function obs_solic_equipamiento()
    {
        return $this->hasMany('App\Obs_solic_equipamiento');
    }

    public function recep_equipamiento()
    {
        return $this->hasMany('App\Recep_equipamiento');
    }


}
