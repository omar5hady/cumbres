<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_salida extends Model
{
    protected $table = 'inv_salidas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'fecha',
        'concepto',
        'cantidad',
        'tipo_producto',
        'user_id',
        'tipo_mov'
                        ];
}
