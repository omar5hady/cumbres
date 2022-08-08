<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\FondoAhorroPago;

class FondoAhorro extends Model
{
    protected $fillable = [
        'user_id', 'saldo'
    ];

    public function pagos(Request $request){
        $pagos = FondoAhorroPago::where('fondo_id','=',$this->id);
        if($request->fechaIni != '')
            $pagos = $pagos->whereBetween('fecha_movimiento', [$request->fechaIni, $request->fechaFin]);
        $pagos = $pagos->orderBy('fecha_movimiento','desc')->paginate(10);
        return $pagos;
    }

    public function usuario(){
        return $this->belongsTo('App\Personal','user_id');
    }
}
