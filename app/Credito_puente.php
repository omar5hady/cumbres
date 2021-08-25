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
        'apertura',
        'fraccionamiento',
        'fecha_sig_int',
        'fecha_ini_int',
        'fecha_firma',
        'tiie_firma',
        'fecha_banco',
        'fecha_integracion',
        'motivo_rechazo',

        'credito_otorgado',
        'num_cuenta',
        'archivo_siembra'
    ];
}
