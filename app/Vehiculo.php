<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'vehiculo',
        'modelo',
        'marca',
        'clave',
        'placas',
        'numero_serie',
        'numero_motor',
        'responsable_id',
        'comodato'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
