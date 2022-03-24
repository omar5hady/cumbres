<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Obs_devolucionCanc;
use App\Obs_devolucionCredit;
use Auth;

class ObsDevolucionController extends Controller
{
    // Funcion de consulta para optener observaciones de  canceladas,
    // se selecciona de tabla la observacion , el usuario y la fecha en la que se creo
    // filtrada por el folio del contrato

    public function listarObservacionesCanc(Request $request){
        if(!$request->ajax())return redirect('/');  // * 
        $observaciones = Obs_devolucionCanc::select('observacion','usuario','created_at')
        ->where('contrato_id','=', $request->id)->orderBy('created_at','desc')->get();

        return ['observacion' => $observaciones];
    }

    // funcion para crear una nueva observacion en la tabla Obs_devolucionCanc (canceladas)
    //donde se guradan el folio del contrato , la observacion y el id del usuaro que registro la observacion 

    public function storeObservacionCanc(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');  //*
        $observacion = new Obs_devolucionCanc();
        $observacion->contrato_id = $request->id;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    //Funcion de consulta para listar las observaciones de Credito en la tabla Obs_devolucionCredit 
    //donde se selecciona la observacion , el usuario y la fecha de creacion  
    public function listarObservacionesCredit(Request $request){
        if(!$request->ajax())return redirect('/');
        $observaciones = Obs_devolucionCredit::select('observacion','usuario','created_at')
        ->where('contrato_id','=', $request->id)->orderBy('created_at','desc')->get();

        return ['observacion' => $observaciones];
    }
    // funcion para crear una nueva observacion en la tabla Obs_devolucionCredit (devolucion credito)
    //donde se guradan el folio del contrato , la observacion y el id del usuaro que registro la observacion 
    public function storeObservacionCredit(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/'); //*
        $observacion = new Obs_devolucionCredit();
        $observacion->contrato_id = $request->id;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }
}


// * Se valida que la peticion sea ajax  y el rol del usuario no pertenezca a un directivo.
