<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Avaluo;
use App\Hist_visita;
use App\Avaluo_status;
use App\Obs_avaluos;
use Auth;

//Controlador para modelo HistVisitas, historial para las visitas de avaluo.
class HistVisitasController extends Controller
{
    //Función que retorna todo el historial de un contrato.
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $historial = Hist_visita::select('id','fecha_visita','observacion')
                ->where('contrato_id','=',$request->buscar)
                ->get();

        return ['historial' => $historial];
    }

    //Función para registrar una nueva visita para avaluo.
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $visita = new Hist_visita();
        $visita->contrato_id = $request->contrato_id;
        $visita->fecha_visita = $request->visita_avaluo;
        $visita->observacion = $request->observacion;
        $visita->save();
    }

    //Función que retorna el historial de estatus de un avaluo.
    public function indexStatus(Request $request){
        if(!$request->ajax())return redirect('/');
        $status = Avaluo_status::select('id','created_at','observacion','status')
                ->where('avaluo_id','=',$request->buscar)
                ->get();

        return ['status' => $status];
    }

    //Función para registrar un nuevo estatus para un avaluo.
    public function storeStatus(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //Se crea un nuevo registro de estatus para avaluo
        $visita = new Avaluo_status();
        $visita->avaluo_id = $request->avaluoId;
        $visita->status = $request->status;
        $visita->observacion = $request->observacion;
        $visita->save();
        //Se gener una observacion ligada al avaluo 
        $obs = new Obs_avaluos();
        $obs->avaluo_id = $request->avaluoId;
        $obs->usuario = Auth::user()->usuario;
        $obs->observacion = $request->status.': '.$request->observacion;
        $obs->save();
        //Se actualiza el estatus en el registro del avaluo.
        $avaluo = Avaluo::findOrFail($request->avaluoId);
        $avaluo->status = $request->status;
        $avaluo->save();
    }
}
