<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notaria extends Model
{
    protected $table = 'notarias'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['notaria','titular','estado','ciudad','colonia','cp','direccion',
    'telefono_1','telefono_2','telefono_3', 'telefono_4'];
    public $timestamps = false;
}
