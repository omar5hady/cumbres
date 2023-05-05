<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpSolicitud extends Model
{
    protected $fillable = [
        'empresa_solic','solicitante_id','departamento',
        'proveedor_id','importe','tipo_pago',
        'status','vb_gerente','vb_direccion', 'rechazado',
        'vb_tesoreria','autorizar','fecha_compra','banco',
        'num_cuenta','clabe','num_factura','fecha_pago','monto_aut',
        'rechazado',
        'entrega_pago',
        'comprobante_pago',
        'cuenta_pago',
        'pagado',
        'beneficiario', 'extraordinaria', 'rev_op', 'convencio', 'referencia'
    ];
}
