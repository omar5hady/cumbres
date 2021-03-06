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
        'observacion','avaluo_preventivo','aviso_prev','integracion','aviso_prev_venc','saldo','devolucion',
        'motivo_cancel', 'entregado','comision','fecha_exp','porcentaje_exp','publicidad_id','fecha_audit','exp_bono'
    ];

    public $timestamps = false;
 
    public function Credito()
    {
        return $this->belongsTo('App\Credito');
    }

    public function Publicidad()
    {
        return $this->belongsTo('App\Medio_publicitario');
    }

    public function pago_contrato(){
        return $this->hasMany('App\Pago_contrato');
    }

    public function avaluo(){
        return $this->hasMany('App\Avaluo');
    }

    public function aviso_preventivo(){
        return $this->hasMany('App\Aviso_preventivo');
    }

    public function obs_expediente(){
        return $this->hasMany('App\Obs_expediente');
    }

    public function hist_visita(){
        return $this->hasMany('App\Hist_visita');
    }

    public function gasto_admin(){
        return $this->hasMany('App\Gasto_admin');
    }

    public function expediente(){
        return $this->hasOne('App/Expediente');
    }

    public function det_comision(){
        return $this->hasOne('App/Det_comision');
    }

    public function bono_recomendado(){
        return $this->hasOne('App/Bono_recomendado');
    }

    public function devolucion(){
        return $this->hasMany('App/Devolucion');
    }

    public function entrega(){
        return $this->hasOne('App/Entrega');
    }

    public function revision_previa(){
        return $this->hasOne('App/Revision_previa');
    }

    public function solic_detalles(){
        return $this->hasMany('App\Solic_detalle');
    }

    public function obs_auditoria()
    {
        return $this->hasMany('App\Obs_auditoria');
    }
}
