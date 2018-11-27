<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contratista extends Model
{
    protected $table = 'contratistas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['nombre','tipo','rfc',
                            'direccion','colonia','cp','estado','ciudad','representante','IMSS','telefono'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
}
