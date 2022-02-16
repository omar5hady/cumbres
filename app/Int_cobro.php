<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Int_cobro extends Model
{
    protected $table = 'int_cobros'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'contrato_id',
        'valor_terreno',
        'valor_const',
        'porcentaje_terreno',
        'porcentaje_construccion',
        'valor_escrituras',
        'status'
                        ];
}
