<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_cobro extends Model
{
    protected $table = 'pagos_cobros'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'integracion_id',
        'deposito_id',
        'fecha_pago',
        'banco',
        'num_cheque',
        'forma_pago',
        'clave',
        'tarjeta',
        'cant_depo',
                        ];
}
