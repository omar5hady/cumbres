<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitudes_pago extends Model
{

    protected $table = 'solicitudes_pagos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [ 'concepto',
                            'autorizacion_orden',
                            'doc_orden',
                            'solic_cheque',
                            'cotizacion',
                            'pago_partes',
                            'factura',
                            'check1',
                            'check2',
                            'check3',
                            'status',
                            'fecha_status',
                            'user_id'
                        ];
    



    //
    public function documentos_pago(){
        return $this->hasMany('App\Documentos_pago');
    }

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }

    public function obs_orden_pago(){
        return $this->hasMany('App\Obs_orden_pago');
    }
}
