<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Det_com_individualizada extends Model
{
    protected $table = 'det_com_individualizadas';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'detalle_id', 'pago', 'comision_id'
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
