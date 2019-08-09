<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devoluciones'; // se referencia a que tabla pertenece el modelo
    
    protected $fillable = ['id', 'concepto', 'monto_cargo', 'devolver',
                            'fecha', 'cheque', 'cuenta', 'observaciones'
                            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato()
    {
        return $this->belongsTo('App\Contrato'); 
    }
}
