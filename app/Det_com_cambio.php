<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Det_com_cambio extends Model
{
    protected $table = 'det_com_cambios';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'monto', 'descripcion', 'comision_id'
    ];

    public function Comision()
    {
        return $this->belongsTo('App\Comision_venta');
    }
}
