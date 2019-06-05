<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avaluo;
use App\Contrato;

class AvaluoController extends Controller
{
    public function store(Request $request){
        $avaluo = new Avaluo();
        $avaluo->contrato_id = $request->folio;
        $avaluo->fecha_solicitud = $request->fecha_solicitud;
        $avaluo->valor_requerido = $request->valor_requerido;
        $avaluo->save();

        $contrato = Contrato::findOrFail($request->folio);
        $contrato->avaluo_preventivo = $request->fecha_solicitud;
        $contrato->save();

    }

    public function noAplicaAvaluo(Request $request){
        $contrato = Contrato::findOrFail($request->folio);
        $contrato->avaluo_preventivo = "0000-01-01";
        $contrato->save();
    }
}
