<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avaluo;
use App\Contrato;

class AvaluoController extends Controller
{
    public function store(Request $request){
        if(!$request->ajax())return redirect('/');
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
        if(!$request->ajax())return redirect('/');
        $contrato = Contrato::findOrFail($request->folio);
        $contrato->avaluo_preventivo = "0000-01-01";
        $contrato->save();
    }

    public function index(Request $request){
        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->select(
                        'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                        'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                        'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                        'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                        'inst_seleccionadas.tipo_credito'

                    )
                    ->where('inst_seleccionadas.elegido','=','1')
                    ->orderBy('contratos.id','asc')
                    ->paginate(25);
        
        return [
            'pagination' => [
            'total'         => $avaluos->total(),
            'current_page'  => $avaluos->currentPage(),
            'per_page'      => $avaluos->perPage(),
            'last_page'     => $avaluos->lastPage(),
            'from'          => $avaluos->firstItem(),
            'to'            => $avaluos->lastItem(),
        ],'avaluos' => $avaluos];
    }
}
