<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contratos';
    protected $fillable = [
        'id', 'infonavit', 'fovisste', 'comision_apertura', 'investigacion','avaluo','prima_unica',
        'escrituras','credito_neto','status','fecha_status','avaluo_cliente','fecha','monto_total_credito','total_pagar',
        'direccion_empresa','cp_empresa','colonia_empresa','estado_empresa','ciudad_empresa','avance_lote',
        'telefono_empresa','ext_empresa','direccion_empresa_coa','cp_empresa_coa','colonia_empresa_coa',
        'estado_empresa_coa','ciudad_empresa_coa','telefono_empresa_coa','ext_empresa_coa','enganche_total',
        'observacion'
    ];

    public $timestamps = false;
 
    public function Credito()
    {
        return $this->belongsTo('App\Credito');
    }

    public function pago_contrato(){
        return $this->hasMany('App\Pago_contrato');
    }
}
