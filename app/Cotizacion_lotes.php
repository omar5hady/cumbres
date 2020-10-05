<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion_lotes extends Model
{
    protected $table = 'cotizacion_lotes';
    protected $primaryKey = 'id';

    protected $fillable = ['id',
    'cliente_id',
    'lotes_id',
    'valor_venta',
    'valor_descuento',
    'fecha',
    'fecha',
    'mensualidades',
    'estatus'];

    public function etapa(){
        return $this->belongsTo('App\Etapa');
    }

    public function pagos_lotes()
    {
        return $this->hasMany('App\Pagos_lotes');
    }
}
