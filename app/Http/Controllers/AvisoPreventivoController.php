<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aviso_preventivo;
use App\Contrato;

class AvisoPreventivoController extends Controller
{
    public function store(Request $request){
        $folio = $request->folio;
        
        $aviso = new Aviso_preventivo();
        $aviso->contrato_id = $folio;
        $aviso->notaria_id = $request->notaria_id;
        $aviso->fecha_solicitud = $request->fecha_solicitud;
        $aviso->save();

        $contrato = Contrato::findOrFail($folio);
        $contrato->aviso_prev = $request->fecha_solicitud;
        $contrato->save();
     }

     public function noAplicaAviso(Request $request){
        $contrato = Contrato::findOrFail($request->folio);
        $contrato->aviso_prev = "0000-01-01";
        $contrato->save();
    }
}
