<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dep_credito extends Model
{
    protected $table = 'dep_creditos';
    protected $fillable = [
            'inst_sel_id','cant_depo',
            'banco','concepto','fecha_deposito',
    ];
 
 
    public function Inst_seleccionada()
    {
        return $this->belongsTo('App\inst_seleccionada');
    }

    public function factura_dep_creditos(){
        return $this->hasMany('App\Factura_dep_creditos', 'id_dep_creditos');
    }

}
