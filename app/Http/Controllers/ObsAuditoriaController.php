<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use App\Obs_auditoria;
use Auth;
use Carbon\Carbon;

class ObsAuditoriaController extends Controller
{
    public function auditar(Request $request){
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_auditoria();
        $observacion->contrato_id = $request->id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }


    public function index(Request $request){
        //if(!$request->ajax())return redirect('/');
        $id = $request->id;
        $observacion = Obs_auditoria::select('comentario','usuario','created_at','id')
                    ->where('contrato_id','=', $id)->orderBy('created_at','desc')->get();

        return [
            'observacion' => $observacion
        ];
    }
}
