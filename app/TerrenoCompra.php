<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TerrenoCompra extends Model
{
    protected $table = 'terreno_compras'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'nombre',
        'direccion',
        'valor_venta',
        'valor_compra',
        'valor_escritura',
        'fecha_firma_promesa',
        'fecha_firma_esc',
        'tamanio',
        'comprador',
        'vendedor'
    ];
}
