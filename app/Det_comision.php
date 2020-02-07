<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Det_comision extends Model
{
    protected $table = 'det_comisiones';
    protected $fillable = [
        'id', 'fecha_anticipo', 'porcentaje_exp', 'porcentaje_casa', 
        'total', 'idComision','fecha_exp','comisionReal', 'extra', 'anticipo','bono','fecha_bono'
    ];

    public $timestamps = false;

    public function Contrato()
    {
        return $this->belongsTo('App\Contrato');
    }

    public function Comision()
    {
        return $this->belongsTo('App\Comision');
    }
 
}
