<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entrega;
use App\Obs_entrega;
use DB;
use Auth;
use App\Expediente;
use App\lote;

class EntregaController extends Controller
{
    public function store(Request $request){
        if(!$request->ajax())return redirect('/');

        try{
            DB::beginTransaction();
            $entrega = new Entrega();
            $entrega->id = $request->id;
            $entrega->save();

            $expediente = Expediente::findOrFail($request->id);
            $expediente->postventa = 1;
            $expediente->save();

            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->id;
            $observacion->comentario = $request->comentario;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function indexPendientes(Request $request){
        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        if($buscar == ''){
            $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio', 
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.paquete',
                        'creditos.promocion',
                        'creditos.descripcion_paquete',
                        'creditos.descripcion_promocion',
                        'licencias.avance as avance_lote',
                        'licencias.visita_avaluo',
                        'contratos.fecha_status',
                        'contratos.status',
                        'contratos.equipamiento',
                        'expedientes.fecha_firma_esc',
                        'lotes.fecha_entrega_obra',
                        'lotes.id as loteId',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 0)
                    ->orderBy('licencias.avance','desc')
                    ->paginate(8);

                return [
                    'pagination' => [
                        'total'         => $contratos->total(),
                        'current_page'  => $contratos->currentPage(),
                        'per_page'      => $contratos->perPage(),
                        'last_page'     => $contratos->lastPage(),
                        'from'          => $contratos->firstItem(),
                        'to'            => $contratos->lastItem(),
                    ],'contratos' => $contratos,
                ];
        }
            
    }

    public function indexObservaciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $observacion = Obs_entrega::select('comentario','usuario','created_at')
                    ->where('entrega_id','=', $buscar)->orderBy('created_at','desc')->paginate(20);

        return [
            'pagination' => [
                'total'         => $observacion->total(),
                'current_page'  => $observacion->currentPage(),
                'per_page'      => $observacion->perPage(),
                'last_page'     => $observacion->lastPage(),
                'from'          => $observacion->firstItem(),
                'to'            => $observacion->lastItem(),
            ],
            'observacion' => $observacion
        ];
    }

    public function storeObservacion(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $observacion = new Obs_entrega();
        $observacion->entrega_id = $request->entrega_id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function setFechaObra(Request $request){
        if(!$request->ajax())return redirect('/');
        try{
            DB::beginTransaction();
            $lote = Lote::findOrFail($request->lote_id);
            $lote->fecha_entrega_obra = $request->fecha_entrega_obra;
            $lote->save();

            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->folio;
            $observacion->comentario = $request->comentario;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }
}
