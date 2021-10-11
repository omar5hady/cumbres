<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposito_conc extends Model
{
    protected $table = 'depositos_conc'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['contrato_id', 
                            'lote_id',
                            'monto',
                            'cuenta',
                            'fecha',
                            'devolucion',
                            'cheque'
                        ];
}
