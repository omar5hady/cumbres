<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\datos_calc_lotes;
use App\lotes_individuales;
use App\Fraccionamiento;

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
    }

    public function selectFraccionamientoLotes(){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        //if(!$request->ajax())return redirect('/');
        
        $fraccionamientos = lotes_individuales::join('etapas', 'lotes_individuales.etapa_id', '=', 'etapas.id')
            ->join('fraccionamientos', 'etapas.fraccionamiento_id', 'fraccionamientos.id')
            ->select('fraccionamientos.nombre','fraccionamientos.id')
            ->where('fraccionamientos.id','!=','1')
            ->orderBy('fraccionamientos.nombre','asc')
            ->groupBy('fraccionamientos.id')
        ->get();

        return['fraccionamientos' => $fraccionamientos];
    }

    public function selectEtapa_proyectoLotes(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        //if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;

        $etapas = lotes_individuales::join('etapas', 'lotes_individuales.etapa_id', '=', 'etapas.id')
            ->select('etapas.num_etapa','etapas.id')
            ->where('etapas.fraccionamiento_id', '=', $buscar )
            ->groupBy('etapas.id')
        ->get();
        
        return['etapas' => $etapas];
    }
}
