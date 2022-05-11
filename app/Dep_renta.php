<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dep_renta extends Model
{
    protected $table = 'dep_rentas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id';
    protected $fillable = [
        'renta_id',
        'monto_cap',
        'fecha_dep',
        'monto_int',
        'num_recibo',
        'banco',
        'concepto'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
