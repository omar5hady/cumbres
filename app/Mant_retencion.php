<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mant_retencion extends Model
{
    protected $table = 'mant_retenciones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'mantenimiento_id',
        'fecha_retencion',
        'importe',
        'status',
        'fecha_real',
        'autorizacion'
    ];

    public function mant_vehiculos(){
        return $this->belongsTo('App\Mant_vehiculo');
    }
}
