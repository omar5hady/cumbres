<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Obs_devolucionCanc;
use App\Obs_devolucionCredit;
use Auth;

class ObsDevolucionController extends Controller
{
    public function listarObservacionesCanc(Request $request){
        if(!$request->ajax())return redirect('/');
        $observaciones = Obs_devolucionCanc::select('observacion','usuario','created_at')
        ->where('contrato_id','=', $request->id)->orderBy('created_at','desc')->get();

        return ['observacion' => $observaciones];
    }

    public function storeObservacionCanc(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_devolucionCanc();
        $observacion->contrato_id = $request->id;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function listarObservacionesCredit(Request $request){
        if(!$request->ajax())return redirect('/');
        $observaciones = Obs_devolucionCredit::select('observacion','usuario','created_at')
        ->where('contrato_id','=', $request->id)->orderBy('created_at','desc')->get();

        return ['observacion' => $observaciones];
    }

    public function storeObservacionCredit(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_devolucionCredit();
        $observacion->contrato_id = $request->id;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }
}
