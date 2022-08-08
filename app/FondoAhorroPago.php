<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FondoAhorroPago extends Model
{
    protected $fillable = [
        'monto', 'concepto', 'tipo_movimiento',
        'fecha_movimiento', 'status', 'fondo_id'
    ];

    public function fondo()
    {
        return $this->belongsTo('App\FondoAhorroPago');
    }
}
