<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use App\Obs_auditoria;
use Auth;
use Carbon\Carbon;

class ObsAuditoriaController extends Controller
{
    // funcion para crear una nueva observacion "auditoria"  en la tabla de Obs_auditoria  anexando en el folio del contrato 
    //   y en la tabla de contrato se guarda la fecha actual de "auditoria" 
    public function auditar(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();

        $observacion = new Obs_auditoria();
        $observacion->contrato_id = $request->id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();

        $contrato = Contrato::findOrFail($request->id);
        $contrato->fecha_audit = $fecha;
        $contrato->save();
        
    }

    public function store(Request $request)
    {
         // funcion para crear una nueva observacion "auditoria"  en la tabla de Obs_auditoria 
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_auditoria();
        $observacion->contrato_id = $request->id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }


    public function index(Request $request){ // funcion de consulta para optener observaciones de la tabla Obs_auditoria 
                                            // filtrando por el folio de contrato 
        if(!$request->ajax())return redirect('/');
        $id = $request->id;
        $observacion = Obs_auditoria::select('comentario','usuario','created_at','id')
                    ->where('contrato_id','=', $id)->orderBy('created_at','desc')->get();

        return [
            'observacion' => $observacion
        ];
    }
}
