<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avaluo;
use App\Hist_visita;
use App\Avaluo_status;
use Auth;

class HistVisitasController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $historial = Hist_visita::select('id','fecha_visita','observacion')
                ->where('contrato_id','=',$request->buscar)
                ->get();

        return ['historial' => $historial];
    }

    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $visita = new Hist_visita();
        $visita->contrato_id = $request->contrato_id;
        $visita->fecha_visita = $request->visita_avaluo;
        $visita->observacion = $request->observacion;
        $visita->save();
    }

    public function indexStatus(Request $request){
        if(!$request->ajax())return redirect('/');
        $status = Avaluo_status::select('id','created_at','observacion','status')
                ->where('avaluo_id','=',$request->buscar)
                ->get();

        return ['status' => $status];
    }

    public function storeStatus(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $visita = new Avaluo_status();
        $visita->avaluo_id = $request->avaluoId;
        $visita->status = $request->status;
        $visita->observacion = $request->observacion;
        $visita->save();

        $avaluo = Avaluo::findOrFail($request->avaluoId);
        $avaluo->status = $request->status;
        $avaluo->save();
    }
}
