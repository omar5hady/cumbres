<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Serv_etapa;
use Auth;

class ServEtapaController extends Controller
{
    // En esta funcion se optienen los servicios que provee cada etapa  
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $etapa_id = $request->etapa_id;
        $servicios = Serv_etapa::join('servicios','serv_etapas.serv_id','=','servicios.id')
            ->select('serv_etapas.id','serv_etapas.etapa_id','serv_etapas.serv_id','servicios.descripcion')
            ->where('serv_etapas.etapa_id','=',$etapa_id)->get(); // se filtra por etapa

        return ['servicios' => $servicios];
    }

    // crea nuevo registro (servicio) en la tabla 
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $etapa_id = $request->etapa_id;
        $servicio = new Serv_etapa();
        $servicio->etapa_id = $request->etapa_id;
        $servicio->serv_id = $request->servicio_id;
        $servicio->save();
    }

    // elimina registro
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $servicio = Serv_etapa::findOrFail($request->id);
        $servicio->delete();
    }
}
