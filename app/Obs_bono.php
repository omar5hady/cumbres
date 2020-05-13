<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_bono extends Model
{
    protected $table = 'obs_bonos';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'bono_id',
        'observacion',
        'usuario'
    ];
 
    public function bono_recomendado()
    {
        return $this->belongsTo('App\Bono_recomendado');
    }
}
