<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_contrato extends Model
{
    protected $table = 'pagos_contratos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['contrato_id','num_pago','monto_pago','fecha_pago'];


    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }
}
