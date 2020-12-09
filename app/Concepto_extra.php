<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concepto_extra extends Model
{
    protected $table = 'conceptos_extras'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fecha', 'concepto','importe','aviso_id'];
}
