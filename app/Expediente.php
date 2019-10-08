<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    protected $table = 'expedientes'; // se referencia a que tabla pertenece el modelo
    
    protected $fillable = ['id','fecha_integracion','gestor_id','valor_escrituras',
                            'fecha_ingreso','fecha_infonavit','descuento','total_liquidar',
                            'fecha_liquidacion','infonavit','fovissste','liquidado','interes_ord',
                            'fecha_firma_esc','notaria_id','notaria','notario','hora_firma',
                            'direccion_firma', 'postventa'
                            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato()
    {
        return $this->belongsTo('App\Contrato');
    }

    public function gestor()
    {
        return $this->belongsTo('App\Personal');
    } 

    public function liquidacion(){
        return $this->hasOne('App/Expediente');
    }
}
