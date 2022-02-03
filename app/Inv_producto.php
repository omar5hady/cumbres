<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inv_producto extends Model
{
    protected $table = 'inv_productos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
            'fecha',
            'concepto',
            'proveedor',
            'num_factura',
            'cantidad',
            'unidad',
            'p_unit',
            'total',
            'stock',
            'tipo_producto',
            'user_id',
            'tipo_mov'
                        ];
}
