<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dev_credito extends Model
{
    protected $table = 'dev_creditos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id';
    protected $fillable = ['contrato_id', 'devolver',
                            'fecha', 'cheque', 'cuenta', 'observaciones'
                            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato()
    {
        return $this->belongsTo('App\Contrato'); 
    }
}
