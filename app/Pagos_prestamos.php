<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagos_prestamos extends Model
{
    protected $table = 'pagos_prestamos'; // se referencia a que tabla pertenece el modelo
    protected $fillable = [
                            'id',
                            'solic_id',
                            'monto_pago',
                            'fecha_pago',
                            'concepto',
                            'status',
                            'monto_pago_extra',
                            'saldo',
                            'fecha_quincena'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
