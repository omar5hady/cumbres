<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comision_venta extends Model
{
    protected $table = 'comisiones_ventas';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'mes', 'anio', 'total_pagado','total_comision', 'apoyo_mes', 'asesor_id'
    ];

    public function vendedor()
    {
        return $this->belongsTo('App\Vendedor');
    }

    public function det_com_individualizada()
    {
        return $this->hasMany('App\Det_com_individualizada');
    }

    public function det_com_cancelada()
    {
        return $this->hasMany('App\Det_com_cancelada');
    }

    public function det_com_pendiente()
    {
        return $this->hasMany('App\Det_com_pendiente');
    }

    public function det_com_ventas()
    {
        return $this->hasMany('App\Det_com_venta');
    }
}
