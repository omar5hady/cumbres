<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstitucionFinanciamiento extends Model
{
    protected $table = 'instituciones_financiamiento'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['nombre','lic'];
        //asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
}
