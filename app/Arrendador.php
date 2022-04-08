<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arrendador extends Model
{
    protected $table = 'arrendadores'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'tipo',
        'nombre',
        'rfc',
        'direccion',
        'cp',
        'colonia',
        'municipio',
        'estado',
        'cuenta',
        'clabe',
        'banco'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

}
