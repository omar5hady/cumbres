<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lote_puente extends Model
{
    protected $table = 'lotes_puente'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'solicitud_id',
        'lote_id',
        'modelo_id',
        'precio_p',
        'precio_c',
        'modeloAnt1',
        'precio1',
        'modeloAnt2',
        'precio2'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
}
