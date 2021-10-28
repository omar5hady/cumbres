<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dep_credito extends Model
{
    protected $table = 'dep_creditos';
    protected $fillable = [
            'inst_sel_id','cant_depo',
            'banco','concepto','fecha_deposito',
            'cuenta',
            'fecha_ingreso_concretania',
            'obs_ingreso'
    ];
 
 
    public function Inst_seleccionada()
    {
        return $this->belongsTo('App\inst_seleccionada');
    }

}
