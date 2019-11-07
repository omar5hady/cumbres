<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipamiento;
use App\Contrato;
use DB;
use Carbon\Carbon;
use App\Solic_equipamiento;
use Auth;

class EquipamientoController extends Controller
{

    public function index(Request $request){

            $equipamientos = Equipamiento::select('id','proveedor_id','equipamiento','activo')
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

        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $equipamiento = new Equipamiento();
        $equipamiento->proveedor_id = $request->proveedor_id;
        $equipamiento->equipamiento = $request->equipamiento;
        $equipamiento->save();
    }

    public function destroy(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $equipamiento = Equipamiento::findOrFail($request->id);
        $equipamiento->activo = 0;
        $equipamiento->save();
    }

    public function activar(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $equipamiento = Equipamiento::findOrFail($request->id);
        $equipamiento->activo = 1;
        $equipamiento->save();
    }

    public function indexContratos(Request $request){
       if(!$request->ajax())return redirect('/');
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
                ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                ->select(
                    'expedientes.fecha_firma_esc',
                    'inst_seleccionadas.tipo_credito',
                    'contratos.id as folio',
                    'contratos.equipamiento',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.tipo_pagare = 0
                                and pagos_contratos.contrato_id = contratos.id
                                and pagos_contratos.pagado != 3) as totPagare"),
                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.tipo_pagare = 0
                                and pagos_contratos.contrato_id = contratos.id
                                and pagos_contratos.pagado != 3) as totRest"),
                    'expedientes.fecha_firma_esc',
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
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'lotes.fraccionamiento_id',
                    'lotes.id as lote_id'
                )
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->where('contratos.entregado', '=',0)
                ->where('inst_seleccionadas.elegido','=',1)
                ->where('inst_seleccionadas.status','=',2)
                ->whereNotNull('creditos.descripcion_paquete')
                ->orWhere('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->where('contratos.entregado', '=',0)
                ->where('inst_seleccionadas.elegido','=',1)
                ->where('inst_seleccionadas.status','=',2)
                ->whereNotNull('creditos.descripcion_promocion')
                ->orderBy('licencias.avance','desc')
                ->orderBy('licencias.visita_avaluo','asc')
                ->paginate(8);
        } else{
            switch($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->select(
                                'expedientes.fecha_firma_esc',
                                'inst_seleccionadas.tipo_credito',
                                'contratos.id as folio',
                                'contratos.equipamiento',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totPagare"),
                                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totRest"),
                                'expedientes.fecha_firma_esc',
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
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.fraccionamiento_id',
                                'lotes.id as lote_id'
                            )
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->whereNotNull('creditos.descripcion_paquete')
                            ->orWhere('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->whereNotNull('creditos.descripcion_promocion')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('licencias.visita_avaluo','asc')
                            ->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->select(
                                'expedientes.fecha_firma_esc',
                                'inst_seleccionadas.tipo_credito',
                                'contratos.id as folio',
                                'contratos.equipamiento',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totPagare"),
                                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totRest"),
                                'expedientes.fecha_firma_esc',
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
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.fraccionamiento_id',
                                'lotes.id as lote_id'
                            )
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->whereNotNull('creditos.descripcion_paquete')
                            ->orWhere('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->whereNotNull('creditos.descripcion_promocion')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('licencias.visita_avaluo','asc')
                            ->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->select(
                                'expedientes.fecha_firma_esc',
                                'inst_seleccionadas.tipo_credito',
                                'contratos.id as folio',
                                'contratos.equipamiento',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totPagare"),
                                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totRest"),
                                'expedientes.fecha_firma_esc',
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
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.fraccionamiento_id',
                                'lotes.id as lote_id'
                            )
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->whereNotNull('creditos.descripcion_paquete')
                            ->orWhere('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->whereNotNull('creditos.descripcion_promocion')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('licencias.visita_avaluo','asc')
                            ->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->select(
                                'expedientes.fecha_firma_esc',
                                'inst_seleccionadas.tipo_credito',
                                'contratos.id as folio',
                                'contratos.equipamiento',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totPagare"),
                                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totRest"),
                                'expedientes.fecha_firma_esc',
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
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.fraccionamiento_id',
                                'lotes.id as lote_id'
                            )
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->whereNotNull('creditos.descripcion_paquete')
                            ->orWhere('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->whereNotNull('creditos.descripcion_promocion')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('licencias.visita_avaluo','asc')
                            ->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->select(
                                'expedientes.fecha_firma_esc',
                                'inst_seleccionadas.tipo_credito',
                                'contratos.id as folio',
                                'contratos.equipamiento',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totPagare"),
                                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totRest"),
                                'expedientes.fecha_firma_esc',
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
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.fraccionamiento_id',
                                'lotes.id as lote_id'
                            )
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->whereNotNull('creditos.descripcion_paquete')
                            ->orWhere('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->whereNotNull('creditos.descripcion_promocion')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('licencias.visita_avaluo','asc')
                            ->paginate(8);
                    }

                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->select(
                                'expedientes.fecha_firma_esc',
                                'inst_seleccionadas.tipo_credito',
                                'contratos.id as folio',
                                'contratos.equipamiento',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totPagare"),
                                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totRest"),
                                'expedientes.fecha_firma_esc',
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
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.fraccionamiento_id',
                                'lotes.id as lote_id'
                            )
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->whereNotNull('creditos.descripcion_paquete')
                            ->orWhere('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->whereNotNull('creditos.descripcion_promocion')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('licencias.visita_avaluo','asc')
                            ->paginate(8);
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->select(
                                'expedientes.fecha_firma_esc',
                                'inst_seleccionadas.tipo_credito',
                                'contratos.id as folio',
                                'contratos.equipamiento',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totPagare"),
                                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totRest"),
                                            'expedientes.fecha_firma_esc',
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
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.fraccionamiento_id',
                                'lotes.id as lote_id'
                            )
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->whereNotNull('creditos.descripcion_paquete')
                            ->orWhere('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->whereNotNull('creditos.descripcion_promocion')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('licencias.visita_avaluo','asc')
                            ->paginate(8);
                    }
                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->select(
                                'expedientes.fecha_firma_esc',
                                'inst_seleccionadas.tipo_credito',
                                'contratos.id as folio',
                                'contratos.equipamiento',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totPagare"),
                                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.tipo_pagare = 0
                                            and pagos_contratos.contrato_id = contratos.id
                                            and pagos_contratos.pagado != 3) as totRest"),
                                'expedientes.fecha_firma_esc',
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
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.fraccionamiento_id',
                                'lotes.id as lote_id'
                            )
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->whereNotNull('creditos.descripcion_paquete')
                            ->orWhere('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('contratos.entregado', '=',0)
                            ->where('inst_seleccionadas.elegido','=',1)
                            ->where('inst_seleccionadas.status','=',2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->whereNotNull('creditos.descripcion_promocion')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('licencias.visita_avaluo','asc')
                            ->paginate(8);
                    }

                    break;
                }

                case 'c.nombre':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                    ->select(
                        'expedientes.fecha_firma_esc',
                        'inst_seleccionadas.tipo_credito',
                        'contratos.id as folio',
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.tipo_pagare = 0
                                    and pagos_contratos.contrato_id = contratos.id
                                    and pagos_contratos.pagado != 3) as totPagare"),
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.tipo_pagare = 0
                                    and pagos_contratos.contrato_id = contratos.id
                                    and pagos_contratos.pagado != 3) as totRest"),
                        'expedientes.fecha_firma_esc',
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
                        'contratos.avaluo_preventivo',
                        'contratos.aviso_prev',
                        'contratos.aviso_prev_venc',
                        'lotes.fraccionamiento_id',
                        'lotes.id as lote_id'
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=',0)
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('inst_seleccionadas.status','=',2)
                    ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%')
                    ->whereNotNull('creditos.descripcion_paquete')
                    ->orWhere('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=',0)
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('inst_seleccionadas.status','=',2)
                    ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%')
                    ->whereNotNull('creditos.descripcion_promocion')
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('licencias.visita_avaluo','asc')
                    ->paginate(8);
                    break;
                }
                case 'contratos.id':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                    ->select(
                        'expedientes.fecha_firma_esc',
                        'inst_seleccionadas.tipo_credito',
                        'contratos.id as folio',
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.tipo_pagare = 0
                                    and pagos_contratos.contrato_id = contratos.id
                                    and pagos_contratos.pagado != 3) as totPagare"),
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.tipo_pagare = 0
                                    and pagos_contratos.contrato_id = contratos.id
                                    and pagos_contratos.pagado != 3) as totRest"),
                        'expedientes.fecha_firma_esc',
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
                        'contratos.avaluo_preventivo',
                        'contratos.aviso_prev',
                        'contratos.aviso_prev_venc',
                        'lotes.fraccionamiento_id',
                        'lotes.id as lote_id'
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=',0)
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('inst_seleccionadas.status','=',2)
                    ->where($criterio, '=', $buscar)
                    ->whereNotNull('creditos.descripcion_paquete')
                    ->orWhere('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=',0)
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('inst_seleccionadas.status','=',2)
                    ->where($criterio, '=', $buscar)
                    ->whereNotNull('creditos.descripcion_promocion')
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('licencias.visita_avaluo','asc')
                    ->paginate(8);
                    break;
                }
            }
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
        if(!$request->ajax())return redirect('/');
        $equipamiento = Equipamiento::select('id','equipamiento')
                    ->where('proveedor_id','=',$request->buscar)->where('activo','=',1)->get();

        return ['equipamiento' => $equipamiento];
    }

    public function solicitud_equipamiento(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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
                        ->select('solic_equipamientos.fecha_solicitud','proveedores.proveedor',
                                'solic_equipamientos.equipamiento_id','solic_equipamientos.id','equipamientos.equipamiento')
                        ->where('solic_equipamientos.contrato_id','=',$contrato_id)
                        ->where('solic_equipamientos.lote_id','=',$lote_id)
                        ->get();
        return ['equipamientos' => $equipamientos];
    }

    public function terminarSolicitud (Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $contrato = Contrato::findOrFail($request->id);
        $contrato->equipamiento = 2;
        $contrato->save();
    }

    
}
