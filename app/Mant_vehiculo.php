<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mant_vehiculo extends Model
{
    protected $table = 'mant_vehiculos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'solicitante',
        'vehiculo',
        'reparacion',
        'descripcion',
        'taller',
        'forma_pago',
        'importe_total',
        'monto_comp',
        'monto_gcc',
        'status',

        'recep_jefe',
        'recep_rh',
        'recep_control',
        'recep_direccion'
    ];

    public function mant_retenciones(){
        return $this->hasMany('App\Mant_retencion');
    }

}
