<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_devolucionCanc extends Model
{
    protected $table = 'obs_devoluciones_canc';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'contrato_id',
        'observacion',
        'usuario'
    ];
 
    public function contrato()
    {
        return $this->belongsTo('App\Contrato');
    }
}
