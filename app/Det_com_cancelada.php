<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Det_com_cancelada extends Model
{
    protected $table = 'det_com_canceladas';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'detalle_id', 'monto', 'bono_cancel', 'comision_id'
    ];

    public $timestamps = false;

    public function Det_com_venta()
    {
        return $this->belongsTo('App\Det_com_venta');
    }

    public function Comision()
    {
        return $this->belongsTo('App\Comision_venta');
    }
}
