<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio_puente extends Model
{
    protected $table = 'precios_puente'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'solicitud_id',
        'modelo_id',
        'precio', 'precio_c'
    ];//asi
}
