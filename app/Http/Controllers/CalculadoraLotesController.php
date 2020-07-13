<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\datos_calc_lotes;
use App\lotes_individuales;
class CalculadoraLotesController extends Controller
{
    public function listarPorcentaje(){
        $lista = datos_calc_lotes::all();
        return $lista;
    }

    public function editaPorcentaje(Request $request){
        $element = datos_calc_lotes::findOrFail($request->id);

        $element->valor = $request->valor;
        //$element->descripcion = $request->descripcion;
        $element->save();
    }

    public function listaLotes(){
        $lotes = lotes_individuales::all();
        return $lotes;
    }

    public function editaLote(Request $request){
        $lote = lotes_individuales::findOrFail($request->id);

        $lote->costom2 = $request->costom2;
        $lote->terrenom2 = $request->terrenom2;
        $lote->save();
    }
}
