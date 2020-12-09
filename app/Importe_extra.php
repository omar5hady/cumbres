<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Importe_extra extends Model
{
    protected $table = 'importes_extras'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['impExtra', 'aviso_id','fechaExtra'];
}
