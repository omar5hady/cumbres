<?php

namespace App\Http\Controllers\Contrato;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EcotecnologiaContrato;
use App\Credito;

class EcotecnologiasController extends Controller
{
    public function index(Request $request){
        $e = EcotecnologiaContrato::where('credito_id','=',$request->credito_id)->get();
        $total = 0;
        if(sizeOf($e)){
            foreach($e as $ecotecnologia){
                $total+=$ecotecnologia->precio;
            }
        }

        return ['ecotecnologias' => $e, 'total' => $total];
    }

    public function store(Request $request){
        $eco = new EcotecnologiaContrato();
        $eco->ecotecnologia = $request->ecotecnologia;
        $eco->credito_id = $request->credito_id;
        $eco->precio = $request->precio;
        $eco->save();

        $credito = Credito::findOrFail($request->credito_id);
        $credito->precio_venta = $credito->precio_venta + $request->precio;
        $credito->save();

        return $eco;
    }

    public function update(Request $request){
        $eco = EcotecnologiaContrato::findOrFail($request->id);
        $eco->ecotecnologia = $request->ecotecnologia;
        $eco->credito_id = $request->credito_id;
        $eco->precio = $request->precio;
        $eco->save();
    }

    public function destroy(Request $request){
        $eco = EcotecnologiaContrato::findOrFail($request->id);
        $eco->delete();
    }
}
