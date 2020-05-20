<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_avaluos extends Model
{
    protected $table = 'obs_avaluos';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'avaluo_id',
        'observacion',
        'usuario'
    ];
 
    public function avaluo()
    {
        return $this->belongsTo('App\Avaluo');
    }
}
