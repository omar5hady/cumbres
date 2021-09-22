<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dev_virtual extends Model
{
    protected $table = 'dev_virtuales'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id';
    protected $fillable = ['contrato_id', 'monto',
                            'fecha', 'cheque', 'cuenta', 'observaciones'
                            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

}
