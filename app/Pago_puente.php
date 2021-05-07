<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_puente extends Model
{
    protected $table = 'pagos_puentes'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'credito_puente_id', 'fecha',
        'concepto', 'abono', 'cargo',
        'comisiones', 'honorarios',
        'deposito_id', 'porc_interes'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
