<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagos_lotes extends Model
{
    protected $table = 'pagos_lotes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'cotizacion_lotes_id',
        'folio',
        'pago',
        'cantidad',
        'fecha',
        'descuento',
        'dias',
        'descuento_porc',
        'interes_monto',
        'total_a_pagar',
        'saldo',
        'estatus_pagado'
    ];
    public function cotizacion_lotes(){
        return $this->belongsTo('App\Cotizacion_lotes');
    }
}
