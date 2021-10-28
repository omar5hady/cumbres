<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    protected $table = 'depositos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['pago_id','cant_depo','interes_mor','interes_ord','obs_mor',
                            'cuenta','fecha_ingreso_concretania','lote_id','obs_ingreso',
                            'obs_ord','num_recibo','banco','concepto','fecha_pago','reubicado'];


    public function pagos(){
        return $this->belongsTo('App\Pago_contrato');
    }
}
