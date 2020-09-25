<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\datos_calc_lotes;
use App\lotes_individuales;
use App\Lote;
use App\Modelo;
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

    public function listarPrecios(){
        $lotes = lotes_individuales::join('etapas', 'lotes_individuales.etapa_id', '=', 'etapas.id')
        ->join('fraccionamientos', 'etapas.fraccionamiento_id', 'fraccionamientos.id')
            ->select('lotes_individuales.id', 'lotes_individuales.etapa_id', 'lotes_individuales.costom2', 
            'etapas.num_etapa', 'fraccionamientos.nombre')
        ->get();
        return $lotes;
    }

    public function editEnterPrice(Request $request){
        $lote = lotes_individuales::findOrFail($request->id);

        $lote->costom2 = $request->costom2;
        $lote->save();

        $this->actPrecioLotes($lote->etapa_id, $request->costom2);
    }

    public function addWindowPrice(Request $request){
        $lote = new lotes_individuales();

        $lote->etapa_id = $request->etapa_id;
        $lote->costom2 = $request->costom2;
        $lote->save();
    }

    public function editWindowPrice(Request $request){
        $lote = lotes_individuales::findOrFail($request->id);

        $lote->etapa_id = $request->etapa_id;
        $lote->costom2 = $request->costom2;
        $lote->save();

        $this->actPrecioLotes($request->etapa_id, $request->costom2);
    }

    private function actPrecioLotes($etapa, $costom2){
        $lotes = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
            ->select('lotes.id')->where('lotes.etapa_id','=',$etapa)
            ->where('lotes.contrato','=',0)
            ->where('modelos.nombre','=','Terreno')->get();
        
        if(sizeof($lotes)){
            foreach ($lotes as $index => $lot) {
                $l= Lote::findOrFail($lot->id);
                $l->precio_base = $costom2 * $l->terreno;
                $l->save();
            }
        }
    }
}
