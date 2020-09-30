<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\datos_calc_lotes;
use App\lotes_individuales;
use App\Fraccionamiento;

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

    public function selectFraccionamientoLotes(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        
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
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;

        $etapas = lotes_individuales::join('etapas', 'lotes_individuales.etapa_id', '=', 'etapas.id')
            ->select('etapas.num_etapa','etapas.id')
            ->where('etapas.fraccionamiento_id', '=', $buscar )
            ->groupBy('etapas.id')
        ->get();
        
        return['etapas' => $etapas];
    }

    public function lotesDisponiblesLotes (Request $request)
    {
        //if(!$request->ajax())return redirect('/');

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')

            ->leftJoin('apartados','lotes.id','apartados.lote_id')
            ->leftJoin('clientes','apartados.cliente_id','clientes.id')
            ->leftJoin('vendedores','apartados.vendedor_id','vendedores.id')
            ->leftJoin('personal','clientes.id','personal.id')
            ->leftJoin('personal as v','vendedores.id','v.id')

            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance','lotes.extra','lotes.extra_ext',
                        'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste','lotes.excedente_terreno','lotes.apartado','lotes.obra_extra','lotes.fecha_termino_ventas',
                        'personal.nombre as c_nombre', 'personal.apellidos as c_apellidos', 'lotes.emp_constructora', 'lotes.emp_terreno',
        'v.nombre as v_nombre', 'apartados.fecha_apartado','lotes.regimen_condom');
                        
        $lotes = $query

            ->where('modelos.nombre', '!=', 'Terreno')
            ->where('etapas.id', '=', $request->etapaId)
            
            ->where('lotes.habilitado','=',1)
            ->where('lotes.contrato','=',0)
            ->where('lotes.apartado','=',0)

            ->where('lotes.casa_muestra', '=', 0)
            ->where('lotes.lote_comercial', '=', 0)
            //->where('lotes.regimen_condom', '=', 0)

            ->orderBy('fraccionamientos.nombre','DESC')
            ->orderBy('etapas.num_etapa','ASC')
            ->orderBy('lotes.manzana','ASC')
            ->orderBy('lotes.num_lote','ASC')
        ->get();
        //->paginate(10);

        return $lotes;

    }
}
