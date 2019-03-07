<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inst_seleccionada extends Model
{
    
    protected $table = 'inst_seleccionadas';
    protected $fillable = [
        'credito_id', 'tipo_credito', 'institucion','elegido','status','fecha_ingreso',
        'fecha_respuesta','plazo_credito','monto_crediito'
    ];
 
 
    public function Credito()
    {
        return $this->belongsTo('App\Credito');
    }

    public function observacion_institucion_seleccionada()
    {
        return $this->hasMany('App\Observacion_institucion_seleccionada');
    }
}
