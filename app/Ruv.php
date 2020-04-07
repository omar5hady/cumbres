<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruv extends Model
{
    protected $table = 'ruvs';
    protected $fillable = [
        'id', 'empresa_id', 'user_siembra', 'fecha_siembra', 'fecha_carga', 'num_cuv',
        'fecha_asignacion', 'fecha_revision', 'fecha_dtu'
    ];

    public $timestamps = false;
 
    public function lote()
    {
        return $this->belongsTo('App\Lote');
    }

    public function personal()
    {
        return $this->belongsTo('App\Personal');
    }

    public function obs_ruv(){
        return $this->hasMany('App\Obs_ruv');
    }
}
