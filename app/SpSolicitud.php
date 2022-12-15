<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpSolicitud extends Model
{
    protected $fillable = [
        'empresa_solic','solicitante_id','departamento',
        'proveedor_id','importe','tipo_pago',
        'status','vb_gerente','vb_direccion',
        'vb_tesoreria','autorizar','fecha_compra','banco',
        'num_cuenta','clabe','num_factura','fecha_pago','monto_aut'
    ];
}
