<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notificacion_aviso;
use Auth;
use DB;


class NotificacionesAvisosController extends Controller
{
    public function store($user_id,$mensaje){
        $aviso = new Notificacion_aviso();
        $aviso->user_id = $user_id;
        $aviso->mensaje = $mensaje;
        $aviso->save();
    }

    public function storeAviso(Request $request){
        $aviso = new Notificacion_aviso();
        $aviso->user_id = $request->user_id;
        $aviso->mensaje = $request->mensaje;
        $aviso->periodo = $request->periodo;
        $aviso->finPeriodo = $request->finPeriodo;
        $aviso->save();
    }

    public function updateAviso(Request $request){
        $aviso = Notificacion_aviso::findOrFail($request->id);
        $aviso->mensaje = $request->mensaje;
        $aviso->periodo = $request->periodo;
        $aviso->finPeriodo = $request->finPeriodo;
        $aviso->enterado = 0;
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

    public function indexAvisos(Request $request){
        $aviso = Notificacion_aviso::join('personal','notificaciones_avisos.user_id','=','personal.id')
                ->select('notificaciones_avisos.id','notificaciones_avisos.mensaje', 'notificaciones_avisos.finPeriodo',
                            'notificaciones_avisos.periodo','notificaciones_avisos.created_at',
                            'personal.nombre', 'personal.apellidos'    
                        )
                ->where('periodo','!=',0);
                if($request->nombre != '')
                    $aviso = $aviso->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->nombre . '%');
                if($request->fecha_inicio != '' && $request->fecha_fin != '')
                    $aviso = $aviso->whereBetween('notificaciones_avisos.created_at', [$request->fecha_inicio, $request->fecha_fin]);
                
                $aviso = $aviso->paginate(10);
        return [
            'pagination' => [
                'total'         => $aviso->total(),
                'current_page'  => $aviso->currentPage(),
                'per_page'      => $aviso->perPage(),
                'last_page'     => $aviso->lastPage(),
                'from'          => $aviso->firstItem(),
                'to'            => $aviso->lastItem(),
            ],
            'aviso' => $aviso
        ];
    }
}
