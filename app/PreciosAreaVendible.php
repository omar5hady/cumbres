<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreciosAreaVendible extends Model
{
    protected $table = 'precios_area_vendibles'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'habitacional',
        'comercial',
        'reserva',
        'usuario',
        'fraccionamiento_id'
    ];
}
