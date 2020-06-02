<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_bono_venta extends Model
{
    protected $table = 'obs_bonos_ventas';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'bono_id',
        'observacion',
        'usuario'
    ];
 
    public function bono_venta()
    {
        return $this->belongsTo('App\Bono_venta');
    }
}
