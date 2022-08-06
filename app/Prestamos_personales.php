<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestamos_personales extends Model
{

    protected $table = 'prestamos_personales'; // se referencia a que tabla pertenece el modelo
    protected $fillable = [
                            'id',
                            'user_id',
                            'jefe_id',
                            'monto_solicitado',
                            'fecha_ini',
                            'motivo',
                            'rh_band',
                            'jefe_band',
                            'dir_band',
                            'status',
                            'saldo',
                            'desc_quin',
        
    ];

}
