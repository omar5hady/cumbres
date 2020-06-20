<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Det_com_venta extends Model
{
    protected $table = 'det_com_ventas';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'contrato_id', 'porcentaje_comision', 'comision_pagar', 'iva', 'isr',
        'retencion', 'este_pago', 'por_pagar', 'comision_id','pendiente',
        'proyecto', 'etapa', 'manzana', 'lote', 'valor_venta'
    ];

    public $timestamps = false;

    public function Contrato()
    {
        return $this->belongsTo('App\Contrato');
    }

    public function Comision()
    {
        return $this->belongsTo('App\Comision_venta');
    }
}
