<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuentas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['num_cuenta','sucursal','banco'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
