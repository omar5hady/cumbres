<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipamiento;
use App\Contrato;
use DB;
use Carbon\Carbon;
use App\Solic_equipamiento;

class EquipamientoController extends Controller
{

    public function index(Request $request){

            $equipamientos = Equipamiento::select('id','proveedor_id','equipamiento')
                ->where('proveedor_id','=', $request->proveedor_id)->orderBy('equipamiento','asc')
                    ->paginate(20);
        

        return [
            'pagination' => [
                'total'         => $equipamientos->total(),
                'current_page'  => $equipamientos->currentPage(),
                'per_page'      => $equipamientos->perPage(),
                'last_page'     => $equipamientos->lastPage(),
                'from'          => $equipamientos->firstItem(),
                'to'            => $equipamientos->lastItem(),
            ],
            'equipamientos' => $equipamientos
        ];

    }

    public function store(Request $request){

        $equipamiento = new Equipamiento();
        $equipamiento->proveedor_id = $request->proveedor_id;
        $equipamiento->equipamiento = $request->equipamiento;
        $equipamiento->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $equipamiento = Equipamiento::findOrFail($request->id);
        $equipamiento->delete();
    }

    public function indexContratos (Request $request){
       // if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;


        if ($buscar == '') {
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->select(
                    'contratos.id as folio',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.descripcion_paquete',
                    'creditos.descripcion_promocion',
                    'licencias.avance as avance_lote',
                    'licencias.visita_avaluo',
                    'contratos.fecha_status',
                    'contratos.status',
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'lotes.fraccionamiento_id',
                    'lotes.id as lote_id'
                )
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->orderBy('licencias.avance','desc')
                ->orderBy('licencias.visita_avaluo','asc')
                ->paginate(8);
        } 

        return [
            'pagination' => [
                'total'        => $contratos->total(),
                'current_page' => $contratos->currentPage(),
                'per_page'     => $contratos->perPage(),
                'last_page'    => $contratos->lastPage(),
                'from'         => $contratos->firstItem(),
                'to'           => $contratos->lastItem(),
            ],
            'contratos' => $contratos,
        ];
    }

    public function selectEquipamiento(Request $request){
        $equipamiento = Equipamiento::select('id','equipamiento')
                    ->where('proveedor_id','=',$request->buscar)->get();

        return ['equipamiento' => $equipamiento];
    }

    public function solicitud_equipamiento(Request $request){
        $fecha_hoy = Carbon::today()->toDateString();
        $solicitud = new Solic_equipamiento();
        $solicitud->lote_id = $request->lote_id;
        $solicitud->contrato_id = $request->contrato_id;
        $solicitud->equipamiento_id = $request->equipamiento_id;
        $solicitud->fecha_solicitud = $fecha_hoy;
        $solicitud->save();

        $contrato = Contrato::findOrFail($request->contrato_id);
        $contrato->equipamiento = 1;
        $contrato->save();

    }

    public function eliminarSolicitud_lote(Request $request){
        $equipamientoLote = Solic_equipamiento::findOrFail($request->id);
        $equipamientoLote->delete();
        $sinEquipamiento = Solic_equipamiento::select('id')->where('contrato_id','=',$request->contrato_id)->count();
        if($sinEquipamiento < 1){
            $contrato = Contrato::findOrFail($request->contrato_id);
            $contrato->equipamiento = 0;
            $contrato->save();
        }
    }

    public function index_equipamientos_lotes(Request $request){
        $lote_id = $request->lote_id;
        $contrato_id = $request->contrato_id;
        $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                        ->select('solic_equipamientos.fecha_solicitud','proveedores.proveedor','equipamientos.equipamiento','solic_equipamientos.id')
                        ->where('solic_equipamientos.contrato_id','=',$contrato_id)
                        ->where('solic_equipamientos.lote_id','=',$lote_id)
                        ->get();
        return ['equipamientos' => $equipamientos];
    }
}
