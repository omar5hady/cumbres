<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventoRegistro extends Model
{
    protected $table = 'evento_registros'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'nombre',
        'ap_paterno',
        'ap_materno',
        'clv_lada',
        'celular',
        'f_nacimiento',
        'email',
        'asist_adult',
        'asist_kid',
        'extra_adult',
        'extra_kid',
        'rfc',
        'is_cliente',
        'cliente_id',
        'fraccionamiento',
        'evento',
        'status'
    ];
}
