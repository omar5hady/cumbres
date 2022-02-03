<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_proveedor extends Model
{
    protected $table = 'inv_proveedores'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
           'nombre'
                        ];
}
