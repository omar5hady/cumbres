<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notificacion_aviso;
use Auth;


class NotificacionesAvisosController extends Controller
{
    public function store($user_id,$mensaje){
        $aviso = new Notificacion_aviso();
        $aviso->user_id = $user_id;
        $aviso->mensaje = $mensaje;
        $aviso->save();
    }

    public function getAvisos(Request $request){
        $avisos =   Notificacion_aviso::select('id','mensaje','enterado')
                    ->where('user_id','=',Auth::user()->id)
                    ->where('enterado','=',0)
                    ->distinct()
                    ->get();

        return $avisos;
    }

    public function setEnterado(Request $request){
        $aviso = Notificacion_aviso::findOrFail($request->id);
        $aviso->enterado = 1;
        $aviso->save();
    }
}
