<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito_puente extends Model
{
    protected $table = 'creditos_puente';
    protected $primaryKey = 'id';

    protected $fillable = [
        'banco',
        'interes',
        'fecha_solic',
        'status',
        'total',
        'cobrado',
        'folio',
        'terreno',
        'apertura',
        'fraccionamiento',
        'etapa_id'
    ];
}
