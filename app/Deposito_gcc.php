<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposito_gcc extends Model
{
    protected $table = 'depositos_gcc'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['contrato_id', 
                            'lote_id',
                            'monto',
                            'cuenta',
                            'fecha',
                            'cheque'
                        ];
}
