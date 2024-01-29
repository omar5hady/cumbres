<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpDetalle extends Model
{
    protected $fillable = [
        'solic_id','obra','sub_obra','cargo','concepto','cca','observacion',
        'tipo_mov','total','pago','saldo','status','pendiente_id','vb',
        'contrato_id', 'fecha_factura', 'folio_fisc'
    ];
}
