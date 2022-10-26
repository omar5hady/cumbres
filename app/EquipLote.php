<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipLote extends Model
{
    protected $fillable = [
        'lote_id','fecha_solicitud','costo','fecha_colocacion','anticipo','fecha_anticipo','equipamiento_id',
        'liquidacion','fecha_liquidacion','fin_instalacion','num_factura','status','control','recepcion',
        'anticipo_cand','liquidacion_cand','comp_pago_1','comp_pago_2','obs_recep','fecha_revision',
        'supervisor'
    ];

    public function lote(){
        return $this->belongsTo('App\Lote','lote_id');
    }

    public function equipamiento(){
        return $this->belongsTo('App\Equipamiento','equipamiento_id');
    }
}
