<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dev_concretania extends Model
{
    protected $table = 'dev_concretania'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id';
    protected $fillable = ['contrato_id', 'fecha_dev',
                            'monto', 'cheque', 'cuenta', 'observaciones'
                            ];

    public function contrato()
    {
        return $this->belongsTo('App\Contrato'); 
    }
}
