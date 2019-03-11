<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Serv_etapa;

class ServEtapaController extends Controller
{
    public function index(Request $request){
        $etapa_id = $request->etapa_id;
        $servicios = Serv_etapa::join('servicios','serv_etapas.serv_id','=','servicios.id')
            ->select('serv_etapas.id','serv_etapas.etapa_id','serv_etapas.serv_id','servicios.descripcion')
            ->where('serv_etapas.etapa_id','=',$etapa_id)->get();

        return ['servicios' => $servicios];
    }

    public function store(Request $request){
        $etapa_id = $request->etapa_id;
        $servicio = new Serv_etapa();
        $servicio->etapa_id = $request->etapa_id;
        $servicio->serv_id = $request->servicio_id;
        $servicio->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicio = Serv_etapa::findOrFail($request->id);
        $servicio->delete();
    }
}
