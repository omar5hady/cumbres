<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bono_venta extends Model
{
    protected $table = 'bonos_ventas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'contrato_id',
        'fecha_pago',
        'monto',
        'descripcion',
        'status',
        'num_bono'

    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato()
    {
        return $this->belongsTo('App\Contrato');
    }
}
