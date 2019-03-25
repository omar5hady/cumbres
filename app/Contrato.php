<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contratos';
    protected $fillable = [
        'id', 'infonavit', 'fovisste', 'comision_apertura', 'investigacion','avaluo','prima_unica',
        'escrituras','credito_neto','status','avaluo_cliente','fecha',
        'direccion_empresa','cp_empresa','colonia_empresa','estado_empresa','ciudad_empresa','telefono_empresa','ext_empresa',
        'direccion_empresa_coa','cp_empresa_coa','colonia_empresa_coa','estado_empresa_coa','ciudad_empresa_coa','telefono_empresa_coa','ext_empresa_coa'
    ];

    public $timestamps = false;
 
    public function Credito()
    {
        return $this->belongsTo('App\Credito');
    }
}
