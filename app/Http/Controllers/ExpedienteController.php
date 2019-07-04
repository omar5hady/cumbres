<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use DB;
use App\Obs_expediente;
use Auth;
use App\Expediente;
use Excel;
use Carbon\Carbon;
use App\Pago_contrato;
use App\Liquidacion;

class ExpedienteController extends Controller
{
    public function indexContratos(Request $request)
    {
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
                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->select(
                    'contratos.id as folio',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'licencias.avance as avance_lote',
                    'contratos.fecha_status',
                    'i.tipo_credito',
                    'i.institucion',
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'lotes.regimen_condom',
                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                    'clientes.coacreditado',
                    'contratos.integracion',
                    'lotes.fraccionamiento_id'
                )
                ->where('i.elegido', '=', 1)
                ->where('contratos.integracion', '=', 0)
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->paginate(8);
        } else {
            if ($criterio != 'lotes.fraccionamiento_id' && $criterio != 'c.nombre' && $criterio != 'v.nombre') {
                $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->join('personal as v', 'vendedores.id', '=', 'v.id')
                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                    ->select(
                        'contratos.id as folio',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'licencias.avance as avance_lote',
                        'contratos.avance_lote',
                        'contratos.fecha_status',
                        'i.tipo_credito',
                        'i.institucion',
                        'contratos.avaluo_preventivo',
                        'contratos.aviso_prev',
                        'contratos.aviso_prev_venc',
                        'lotes.regimen_condom',
                        DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                        DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                        'clientes.coacreditado',
                        'contratos.integracion',
                        'lotes.fraccionamiento_id'
                    )
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.integracion', '=', 0)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where($criterio, 'like', '%' . $buscar . '%')
                    ->paginate(8);
            } else {

                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote == '') {
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select(
                            'contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'licencias.avance as avance_lote',
                            'contratos.avance_lote',
                            'contratos.fecha_status',
                            'i.tipo_credito',
                            'i.institucion',
                            'contratos.avaluo_preventivo',
                            'contratos.aviso_prev',
                            'contratos.aviso_prev_venc',
                            'lotes.regimen_condom',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                            'clientes.coacreditado',
                            'contratos.integracion',
                            'lotes.fraccionamiento_id'
                        )
                        ->where('i.elegido', '=', 1)
                        ->where('contratos.integracion', '=', 0)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where($criterio, '=', $buscar)
                        ->paginate(8);
                } else {
                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote == '') {
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'licencias.avance as avance_lote',
                                'contratos.avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.integracion', '=', 0)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->paginate(8);
                    } else {
                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana != '' && $b_lote == '') {
                            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->select(
                                    'contratos.id as folio',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                    'creditos.fraccionamiento as proyecto',
                                    'creditos.etapa',
                                    'creditos.manzana',
                                    'creditos.num_lote',
                                    'licencias.avance as avance_lote',
                                    'contratos.avance_lote',
                                    'contratos.fecha_status',
                                    'i.tipo_credito',
                                    'i.institucion',
                                    'contratos.avaluo_preventivo',
                                    'contratos.aviso_prev',
                                    'contratos.aviso_prev_venc',
                                    'lotes.regimen_condom',
                                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                    'clientes.coacreditado',
                                    'contratos.integracion',
                                    'lotes.fraccionamiento_id'
                                )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.integracion', '=', 0)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                ->paginate(8);
                        } else {
                            if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote != '') {
                                $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                                    ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                    ->select(
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote',
                                        'licencias.avance as avance_lote',
                                        'contratos.avance_lote',
                                        'contratos.fecha_status',
                                        'i.tipo_credito',
                                        'i.institucion',
                                        'contratos.avaluo_preventivo',
                                        'contratos.aviso_prev',
                                        'contratos.aviso_prev_venc',
                                        'lotes.regimen_condom',
                                        DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                        DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                        'clientes.coacreditado',
                                        'contratos.integracion',
                                        'lotes.fraccionamiento_id'
                                    )
                                    ->where('i.elegido', '=', 1)
                                    ->where('contratos.integracion', '=', 0)
                                    ->where('contratos.status', '!=', 0)
                                    ->where('contratos.status', '!=', 2)
                                    ->where($criterio, '=', $buscar)
                                    ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                    ->paginate(8);
                            } else {
                                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote == '') {
                                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                        ->select(
                                            'contratos.id as folio',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                            'creditos.fraccionamiento as proyecto',
                                            'creditos.etapa',
                                            'creditos.manzana',
                                            'creditos.num_lote',
                                            'licencias.avance as avance_lote',
                                            'contratos.avance_lote',
                                            'contratos.fecha_status',
                                            'i.tipo_credito',
                                            'i.institucion',
                                            'contratos.avaluo_preventivo',
                                            'contratos.aviso_prev',
                                            'contratos.aviso_prev_venc',
                                            'lotes.regimen_condom',
                                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                            'clientes.coacreditado',
                                            'contratos.integracion',
                                            'lotes.fraccionamiento_id'
                                        )
                                        ->where('i.elegido', '=', 1)
                                        ->where('contratos.integracion', '=', 0)
                                        ->where('contratos.status', '!=', 0)
                                        ->where('contratos.status', '!=', 2)
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                        ->paginate(8);
                                } else {
                                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote != '') {
                                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                            ->select(
                                                'contratos.id as folio',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                'creditos.fraccionamiento as proyecto',
                                                'creditos.etapa',
                                                'creditos.manzana',
                                                'creditos.num_lote',
                                                'licencias.avance as avance_lote',
                                                'contratos.avance_lote',
                                                'contratos.fecha_status',
                                                'i.tipo_credito',
                                                'i.institucion',
                                                'contratos.avaluo_preventivo',
                                                'contratos.aviso_prev',
                                                'contratos.aviso_prev_venc',
                                                'lotes.regimen_condom',
                                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                                'clientes.coacreditado',
                                                'contratos.integracion',
                                                'lotes.fraccionamiento_id'
                                            )
                                            ->where('i.elegido', '=', 1)
                                            ->where('contratos.integracion', '=', 0)
                                            ->where('contratos.status', '!=', 0)
                                            ->where('contratos.status', '!=', 2)
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                            ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                            ->paginate(8);
                                    } else {
                                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote != '') {
                                            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                                ->select(
                                                    'contratos.id as folio',
                                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                    'creditos.fraccionamiento as proyecto',
                                                    'creditos.etapa',
                                                    'creditos.manzana',
                                                    'creditos.num_lote',
                                                    'licencias.avance as avance_lote',
                                                    'contratos.avance_lote',
                                                    'contratos.fecha_status',
                                                    'i.tipo_credito',
                                                    'i.institucion',
                                                    'contratos.avaluo_preventivo',
                                                    'contratos.aviso_prev',
                                                    'contratos.aviso_prev_venc',
                                                    'lotes.regimen_condom',
                                                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                                    'clientes.coacreditado',
                                                    'contratos.integracion',
                                                    'lotes.fraccionamiento_id'
                                                )
                                                ->where('i.elegido', '=', 1)
                                                ->where('contratos.integracion', '=', 0)
                                                ->where('contratos.status', '!=', 0)
                                                ->where('contratos.status', '!=', 2)
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                                ->paginate(8);
                                        } else {
                                            if ($criterio == 'c.nombre' && $buscar != '') {
                                                $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                                                    ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                                    ->select(
                                                        'contratos.id as folio',
                                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                        'creditos.fraccionamiento as proyecto',
                                                        'creditos.etapa',
                                                        'creditos.manzana',
                                                        'creditos.num_lote',
                                                        'licencias.avance as avance_lote',
                                                        'contratos.avance_lote',
                                                        'contratos.fecha_status',
                                                        'i.tipo_credito',
                                                        'i.institucion',
                                                        'contratos.avaluo_preventivo',
                                                        'contratos.aviso_prev',
                                                        'contratos.aviso_prev_venc',
                                                        'lotes.regimen_condom',
                                                        DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                                        DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                                        'clientes.coacreditado',
                                                        'contratos.integracion',
                                                        'lotes.fraccionamiento_id'
                                                    )
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 0)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                                    ->orWhere('c.apellidos', 'like', '%' . $buscar . '%')
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 0)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->paginate(8);
                                            } else {
                                                if ($criterio == 'v.nombre' && $buscar != '') {
                                                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                                                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                                        ->select(
                                                            'contratos.id as folio',
                                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                            'creditos.fraccionamiento as proyecto',
                                                            'creditos.etapa',
                                                            'creditos.manzana',
                                                            'creditos.num_lote',
                                                            'licencias.avance as avance_lote',
                                                            'contratos.avance_lote',
                                                            'contratos.fecha_status',
                                                            'i.tipo_credito',
                                                            'i.institucion',
                                                            'contratos.avaluo_preventivo',
                                                            'contratos.aviso_prev',
                                                            'contratos.aviso_prev_venc',
                                                            'lotes.regimen_condom',
                                                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                                            'clientes.coacreditado',
                                                            'contratos.integracion',
                                                            'lotes.fraccionamiento_id'
                                                        )
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->where($criterio, 'like', '%' . $buscar . '%')
                                                        ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->paginate(8);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
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

    public function listarObservaciones(Request $request){
        $observaciones = Obs_expediente::select('observacion','usuario','created_at')
        ->where('contrato_id','=', $request->folio)->orderBy('created_at','desc')->get();

        return ['observacion' => $observaciones];
    }

    public function storeObservacion(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $observacion = new Obs_expediente();
        $observacion->contrato_id = $request->folio;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();


    }

    public function exportExcel(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;


        if ($buscar == '') {
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->select(
                    'contratos.id as folio',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'contratos.avance_lote',
                    'contratos.fecha_status',
                    'i.tipo_credito',
                    'i.institucion',
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'lotes.regimen_condom',
                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                    'clientes.coacreditado',
                    'contratos.integracion',
                    'lotes.fraccionamiento_id'
                )
                ->where('i.elegido', '=', 1)
                ->where('contratos.integracion', '=', 0)
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->get();
        } else {
            if ($criterio != 'lotes.fraccionamiento_id' && $criterio != 'c.nombre' && $criterio != 'v.nombre') {
                $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->join('personal as v', 'vendedores.id', '=', 'v.id')
                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                    ->select(
                        'contratos.id as folio',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'contratos.avance_lote',
                        'contratos.fecha_status',
                        'i.tipo_credito',
                        'i.institucion',
                        'contratos.avaluo_preventivo',
                        'contratos.aviso_prev',
                        'contratos.aviso_prev_venc',
                        'lotes.regimen_condom',
                        DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                        DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                        'clientes.coacreditado',
                        'contratos.integracion',
                        'lotes.fraccionamiento_id'
                    )
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.integracion', '=', 0)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where($criterio, 'like', '%' . $buscar . '%')
                    ->get();
            } else {

                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote == '') {
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select(
                            'contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'contratos.avance_lote',
                            'contratos.fecha_status',
                            'i.tipo_credito',
                            'i.institucion',
                            'contratos.avaluo_preventivo',
                            'contratos.aviso_prev',
                            'contratos.aviso_prev_venc',
                            'lotes.regimen_condom',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                            'clientes.coacreditado',
                            'contratos.integracion',
                            'lotes.fraccionamiento_id'
                        )
                        ->where('i.elegido', '=', 1)
                        ->where('contratos.integracion', '=', 0)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where($criterio, '=', $buscar)
                        ->get();
                } else {
                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote == '') {
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'contratos.avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.integracion', '=', 0)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->get();
                    } else {
                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana != '' && $b_lote == '') {
                            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->select(
                                    'contratos.id as folio',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                    'creditos.fraccionamiento as proyecto',
                                    'creditos.etapa',
                                    'creditos.manzana',
                                    'creditos.num_lote',
                                    'contratos.avance_lote',
                                    'contratos.fecha_status',
                                    'i.tipo_credito',
                                    'i.institucion',
                                    'contratos.avaluo_preventivo',
                                    'contratos.aviso_prev',
                                    'contratos.aviso_prev_venc',
                                    'lotes.regimen_condom',
                                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                    'clientes.coacreditado',
                                    'contratos.integracion',
                                    'lotes.fraccionamiento_id'
                                )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.integracion', '=', 0)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                ->get();
                        } else {
                            if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote != '') {
                                $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                                    ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                    ->select(
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote',
                                        'contratos.avance_lote',
                                        'contratos.fecha_status',
                                        'i.tipo_credito',
                                        'i.institucion',
                                        'contratos.avaluo_preventivo',
                                        'contratos.aviso_prev',
                                        'contratos.aviso_prev_venc',
                                        'lotes.regimen_condom',
                                        DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                        DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                        'clientes.coacreditado',
                                        'contratos.integracion',
                                        'lotes.fraccionamiento_id'
                                    )
                                    ->where('i.elegido', '=', 1)
                                    ->where('contratos.integracion', '=', 0)
                                    ->where('contratos.status', '!=', 0)
                                    ->where('contratos.status', '!=', 2)
                                    ->where($criterio, '=', $buscar)
                                    ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                    ->get();
                            } else {
                                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote == '') {
                                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                        ->select(
                                            'contratos.id as folio',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                            'creditos.fraccionamiento as proyecto',
                                            'creditos.etapa',
                                            'creditos.manzana',
                                            'creditos.num_lote',
                                            'contratos.avance_lote',
                                            'contratos.fecha_status',
                                            'i.tipo_credito',
                                            'i.institucion',
                                            'contratos.avaluo_preventivo',
                                            'contratos.aviso_prev',
                                            'contratos.aviso_prev_venc',
                                            'lotes.regimen_condom',
                                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                            'clientes.coacreditado',
                                            'contratos.integracion',
                                            'lotes.fraccionamiento_id'
                                        )
                                        ->where('i.elegido', '=', 1)
                                        ->where('contratos.integracion', '=', 0)
                                        ->where('contratos.status', '!=', 0)
                                        ->where('contratos.status', '!=', 2)
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                        ->get();
                                } else {
                                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote != '') {
                                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                            ->select(
                                                'contratos.id as folio',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                'creditos.fraccionamiento as proyecto',
                                                'creditos.etapa',
                                                'creditos.manzana',
                                                'creditos.num_lote',
                                                'contratos.avance_lote',
                                                'contratos.fecha_status',
                                                'i.tipo_credito',
                                                'i.institucion',
                                                'contratos.avaluo_preventivo',
                                                'contratos.aviso_prev',
                                                'contratos.aviso_prev_venc',
                                                'lotes.regimen_condom',
                                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                                'clientes.coacreditado',
                                                'contratos.integracion',
                                                'lotes.fraccionamiento_id'
                                            )
                                            ->where('i.elegido', '=', 1)
                                            ->where('contratos.integracion', '=', 0)
                                            ->where('contratos.status', '!=', 0)
                                            ->where('contratos.status', '!=', 2)
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                            ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                            ->get();
                                    } else {
                                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote != '') {
                                            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                                ->select(
                                                    'contratos.id as folio',
                                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                    'creditos.fraccionamiento as proyecto',
                                                    'creditos.etapa',
                                                    'creditos.manzana',
                                                    'creditos.num_lote',
                                                    'contratos.avance_lote',
                                                    'contratos.fecha_status',
                                                    'i.tipo_credito',
                                                    'i.institucion',
                                                    'contratos.avaluo_preventivo',
                                                    'contratos.aviso_prev',
                                                    'contratos.aviso_prev_venc',
                                                    'lotes.regimen_condom',
                                                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                                    'clientes.coacreditado',
                                                    'contratos.integracion',
                                                    'lotes.fraccionamiento_id'
                                                )
                                                ->where('i.elegido', '=', 1)
                                                ->where('contratos.integracion', '=', 0)
                                                ->where('contratos.status', '!=', 0)
                                                ->where('contratos.status', '!=', 2)
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                                ->get();
                                        } else {
                                            if ($criterio == 'c.nombre' && $buscar != '') {
                                                $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                                                    ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                                    ->select(
                                                        'contratos.id as folio',
                                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                        'creditos.fraccionamiento as proyecto',
                                                        'creditos.etapa',
                                                        'creditos.manzana',
                                                        'creditos.num_lote',
                                                        'contratos.avance_lote',
                                                        'contratos.fecha_status',
                                                        'i.tipo_credito',
                                                        'i.institucion',
                                                        'contratos.avaluo_preventivo',
                                                        'contratos.aviso_prev',
                                                        'contratos.aviso_prev_venc',
                                                        'lotes.regimen_condom',
                                                        DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                                        DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                                        'clientes.coacreditado',
                                                        'contratos.integracion',
                                                        'lotes.fraccionamiento_id'
                                                    )
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 0)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                                    ->orWhere('c.apellidos', 'like', '%' . $buscar . '%')
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 0)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->get();
                                            } else {
                                                if ($criterio == 'v.nombre' && $buscar != '') {
                                                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                                                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                                        ->select(
                                                            'contratos.id as folio',
                                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                            'creditos.fraccionamiento as proyecto',
                                                            'creditos.etapa',
                                                            'creditos.manzana',
                                                            'creditos.num_lote',
                                                            'contratos.avance_lote',
                                                            'contratos.fecha_status',
                                                            'i.tipo_credito',
                                                            'i.institucion',
                                                            'contratos.avaluo_preventivo',
                                                            'contratos.aviso_prev',
                                                            'contratos.aviso_prev_venc',
                                                            'lotes.regimen_condom',
                                                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                                            'clientes.coacreditado',
                                                            'contratos.integracion',
                                                            'lotes.fraccionamiento_id'
                                                        )
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->where($criterio, 'like', '%' . $buscar . '%')
                                                        ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->get();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        
        return Excel::create('expediente', function($excel) use ($contratos){
            $excel->sheet('contratos', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Folio', 'Cliente' ,'Asesor', 'Proyecto', 'Etapa', 'Manzana', 'Lote',
                    '% Avance', 'Firma', 'Tipo de credito','Institucion Financiera','Solicitud de avaluo','Aviso preventivo',
                    'Regimen en condominio','Conyuge'
                ]);


                $sheet->cells('A1:O1', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(13);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                
                $cont=1;
                

                foreach($contratos as $index => $contrato) {

                    $cont++; 

                    if($contrato->regimen_condom == 1){
                        $regimen = 'Si';
                    }else{
                        $regimen = 'No';
                    }

                    $avaluo_prev = '';
                    if($contrato->avaluo_preventivo == "0000-01-01"){
                        $avaluo_prev = 'No aplica';
                    }
                    else{
                        $avaluo_prev = $contrato->avaluo_preventivo;
                    }

                    $aviso_prev = '';
                    if($contrato->aviso_prev == "0000-01-01"){
                        $aviso_prev = 'No aplica';
                    }
                    elseif($contrato->aviso_prev){
                        if($contrato->aviso_prev_venc)
                            $aviso_prev = 'Vencimiento: '.$contrato->aviso_prev;
                        else{
                            $aviso_prev = 'Solicitud: '.$contrato->aviso_prev;
                        }
                    }
                    
                    $sheet->row($index+2, [
                        $contrato->folio, 
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->avance_lote.'%',
                        $contrato->fecha_status,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $avaluo_prev,
                        $aviso_prev,
                        $regimen,
                        $contrato->nombre_conyuge
                   
                    ]);	
                }


                $num='A1:O' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    public function store(Request $request){
        if(!$request->ajax())return redirect('/');
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $expediente = new Expediente();
        $expediente->id = $request->folio;
        $expediente->fecha_integracion = $hoy;
        $expediente->save();

        $contrato = Contrato::findOrFail($request->folio);
        $contrato->integracion = 1;
        $contrato->save();
    }

    public function indexAsignarGestor(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;


        if ($buscar == '') {
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->join('expedientes','contratos.id','=','expedientes.id')
                ->join('personal as g','expedientes.gestor_id','=','g.id')
                ->select(
                    'contratos.id as folio',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'contratos.fecha_status',
                    'i.tipo_credito',
                    'i.institucion', 
                    'expedientes.gestor_id',                
                    'contratos.integracion',
                    'lotes.fraccionamiento_id',
                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                )
                ->where('i.elegido', '=', 1)
                ->where('contratos.integracion', '=', 1)
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->paginate(8);
        } else {
            if ($criterio != 'lotes.fraccionamiento_id' && $criterio != 'c.nombre' && $criterio != 'v.nombre') {
                $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id') 
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                    ->join('expedientes','contratos.id','=','expedientes.id')
                    ->join('personal as g','expedientes.gestor_id','=','g.id')
                    ->select(
                        'contratos.id as folio',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'contratos.fecha_status',
                        'i.tipo_credito',
                        'i.institucion',
                        'expedientes.gestor_id',
                        'contratos.integracion',
                        'lotes.fraccionamiento_id',
                        DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                    )
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.integracion', '=', 1)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where($criterio, 'like', '%' . $buscar . '%')
                    ->paginate(8);
            } else {

                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote == '') {
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id') 
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->join('expedientes','contratos.id','=','expedientes.id')
                        ->join('personal as g','expedientes.gestor_id','=','g.id')
                        ->select(
                            'contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'contratos.fecha_status',
                            'i.tipo_credito',
                            'i.institucion',
                            'expedientes.gestor_id',
                            'contratos.integracion',
                            'lotes.fraccionamiento_id',
                            DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                        )
                        ->where('i.elegido', '=', 1)
                        ->where('contratos.integracion', '=', 1)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where($criterio, '=', $buscar)
                        ->paginate(8);
                } else {
                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote == '') {
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('personal as g','expedientes.gestor_id','=','g.id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"), 
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'expedientes.gestor_id',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.integracion', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->paginate(8);
                    } else {
                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana != '' && $b_lote == '') {
                            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('expedientes','contratos.id','=','expedientes.id')
                                ->join('personal as g','expedientes.gestor_id','=','g.id')
                                ->select(
                                    'contratos.id as folio',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                    'creditos.fraccionamiento as proyecto',
                                    'creditos.etapa',
                                    'creditos.manzana',
                                    'creditos.num_lote',
                                    'contratos.fecha_status',
                                    'i.tipo_credito',
                                    'i.institucion',
                                    'expedientes.gestor_id',
                                    'contratos.integracion',
                                    'lotes.fraccionamiento_id',
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                                )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.integracion', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                ->paginate(8);
                        } else {
                            if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote != '') {
                                $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')  
                                    ->join('personal as c', 'clientes.id', '=', 'c.id') 
                                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                    ->join('expedientes','contratos.id','=','expedientes.id')
                                    ->join('personal as g','expedientes.gestor_id','=','g.id')
                                    ->select(
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"), 
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote',  
                                        'contratos.fecha_status',
                                        'i.tipo_credito',
                                        'i.institucion',
                                        'expedientes.gestor_id',
                                        'contratos.integracion',
                                        'lotes.fraccionamiento_id',
                                        DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                                    )
                                    ->where('i.elegido', '=', 1)
                                    ->where('contratos.integracion', '=', 1)
                                    ->where('contratos.status', '!=', 0)
                                    ->where('contratos.status', '!=', 2)
                                    ->where($criterio, '=', $buscar)
                                    ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                    ->paginate(8);
                            } else {
                                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote == '') {
                                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                        ->join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('personal as g','expedientes.gestor_id','=','g.id')
                                        ->select(
                                            'contratos.id as folio',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'creditos.fraccionamiento as proyecto',
                                            'creditos.etapa',
                                            'creditos.manzana',
                                            'creditos.num_lote',
                                            'contratos.fecha_status',
                                            'i.tipo_credito',
                                            'i.institucion',
                                            'expedientes.gestor_id',
                                            'contratos.integracion',
                                            'lotes.fraccionamiento_id',
                                            DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                                        )
                                        ->where('i.elegido', '=', 1)
                                        ->where('contratos.integracion', '=', 1)
                                        ->where('contratos.status', '!=', 0)
                                        ->where('contratos.status', '!=', 2)
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                        ->paginate(8);
                                } else {
                                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote != '') {
                                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                            ->join('expedientes','contratos.id','=','expedientes.id')
                                            ->join('personal as g','expedientes.gestor_id','=','g.id')
                                            ->select(
                                                'contratos.id as folio',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'creditos.fraccionamiento as proyecto',
                                                'creditos.etapa',
                                                'creditos.manzana',
                                                'creditos.num_lote',
                                                'contratos.fecha_status',
                                                'i.tipo_credito',
                                                'i.institucion',
                                                'expedientes.gestor_id',
                                                'contratos.integracion',
                                                'lotes.fraccionamiento_id',
                                                DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                                            )
                                            ->where('i.elegido', '=', 1)
                                            ->where('contratos.integracion', '=', 1)
                                            ->where('contratos.status', '!=', 0)
                                            ->where('contratos.status', '!=', 2)
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                            ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                            ->paginate(8);
                                    } else {
                                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote != '') {
                                            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                                ->join('expedientes','contratos.id','=','expedientes.id')
                                                ->join('personal as g','expedientes.gestor_id','=','g.id')
                                                ->select(
                                                    'contratos.id as folio',
                                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                    'creditos.fraccionamiento as proyecto',
                                                    'creditos.etapa',
                                                    'creditos.manzana',
                                                    'creditos.num_lote',
                                                    'contratos.fecha_status',
                                                    'i.tipo_credito',
                                                    'i.institucion',
                                                    'expedientes.gestor_id',
                                                    'contratos.integracion',
                                                    'lotes.fraccionamiento_id',
                                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                                                )
                                                ->where('i.elegido', '=', 1)
                                                ->where('contratos.integracion', '=', 1)
                                                ->where('contratos.status', '!=', 0)
                                                ->where('contratos.status', '!=', 2)
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                                ->paginate(8);
                                        } else {
                                            if ($criterio == 'c.nombre' && $buscar != '') {
                                                $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                                                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                                    ->join('expedientes','contratos.id','=','expedientes.id')
                                                    ->join('personal as g','expedientes.gestor_id','=','g.id')
                                                    ->select(
                                                        'contratos.id as folio',
                                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                        'creditos.fraccionamiento as proyecto',
                                                        'creditos.etapa',
                                                        'creditos.manzana',
                                                        'creditos.num_lote',
                                                        'contratos.fecha_status',
                                                        'i.tipo_credito',
                                                        'i.institucion',
                                                        'expedientes.gestor_id',
                                                        'contratos.integracion',
                                                        'lotes.fraccionamiento_id',
                                                        DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                                                    )
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 1)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                                    ->orWhere('c.apellidos', 'like', '%' . $buscar . '%')
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 0)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->paginate(8);
                                            } else {
                                                if ($criterio == 'g.nombre' && $buscar != '') {
                                                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                                                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                                        ->join('expedientes','contratos.id','=','expedientes.id')
                                                        ->join('personal as g','expedientes.gestor_id','=','g.id')
                                                        ->select(
                                                            'contratos.id as folio',
                                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                            'creditos.fraccionamiento as proyecto',
                                                            'creditos.etapa',
                                                            'creditos.manzana',
                                                            'creditos.num_lote',
                                                            'contratos.fecha_status',
                                                            'i.tipo_credito',
                                                            'i.institucion',
                                                            'expedientes.gestor_id',
                                                            'contratos.integracion',
                                                            'lotes.fraccionamiento_id',
                                                            DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
                                                        )
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 1)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->where($criterio, 'like', '%' . $buscar . '%')
                                                        ->orWhere('g.apellidos', 'like', '%' . $buscar . '%')
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->paginate(8);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
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

    public function asignarGestor(Request $request){
        $asignar = Expediente::findOrFail($request->folio);
        $asignar->gestor_id =  $request->gestor_id;
        $asignar->save();
    }

    public function indexIngresarExp(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;


        if ($buscar == ''){
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                ->join('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->select(
                    'contratos.id as folio',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.precio_venta',
                    'licencias.avance as avance_lote',
                    'contratos.fecha_status',
                    'i.tipo_credito',
                    'i.institucion',
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'lotes.regimen_condom',
                    'lotes.credito_puente',
                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                    'clientes.coacreditado',
                    'contratos.integracion',
                    'lotes.fraccionamiento_id',
                    'expedientes.valor_escrituras',
                    'expedientes.fecha_ingreso',
                    'expedientes.fecha_integracion',
                    'lotes.calle','lotes.numero','lotes.interior',
                    'avaluos.resultado','avaluos.fecha_recibido'
                )
                ->where('i.elegido', '=', 1)
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->where('expedientes.fecha_ingreso','=',NULL)
                ->orderBy('contratos.id','asc')
                ->get();
        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                        ->join('expedientes','contratos.id','=','expedientes.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select(
                            'contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.precio_venta',
                            'licencias.avance as avance_lote',
                            'contratos.fecha_status',
                            'i.tipo_credito',
                            'i.institucion',
                            'contratos.avaluo_preventivo',
                            'contratos.aviso_prev',
                            'contratos.aviso_prev_venc',
                            'lotes.regimen_condom',
                            'lotes.credito_puente',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                            'clientes.coacreditado',
                            'contratos.integracion',
                            'lotes.fraccionamiento_id',
                            'expedientes.valor_escrituras',
                            'expedientes.fecha_ingreso',
                            'expedientes.fecha_integracion',
                            'lotes.calle','lotes.numero','lotes.interior',
                            'avaluos.resultado','avaluos.fecha_recibido'
                        )
                        ->where('i.elegido', '=', 1)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('expedientes.fecha_ingreso','=',NULL)
                        ->where('c.nombre','like','%'. $buscar . '%')
                        ->orWhere('i.elegido', '=', 1)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('expedientes.fecha_ingreso','=',NULL)
                        ->where('c.apellidos','like','%'. $buscar . '%')
                        ->orderBy('contratos.id','asc')
                        ->get();
                    break;
                }
                case 'contratos.id':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                        ->join('expedientes','contratos.id','=','expedientes.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select(
                            'contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.precio_venta',
                            'licencias.avance as avance_lote',
                            'contratos.fecha_status',
                            'i.tipo_credito',
                            'i.institucion',
                            'contratos.avaluo_preventivo',
                            'contratos.aviso_prev',
                            'contratos.aviso_prev_venc',
                            'lotes.regimen_condom',
                            'lotes.credito_puente',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                            'clientes.coacreditado',
                            'contratos.integracion',
                            'lotes.fraccionamiento_id',
                            'expedientes.valor_escrituras',
                            'expedientes.fecha_ingreso',
                            'expedientes.fecha_integracion',
                            'lotes.calle','lotes.numero','lotes.interior',
                            'avaluos.resultado','avaluos.fecha_recibido'
                        )
                        ->where('i.elegido', '=', 1)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('expedientes.fecha_ingreso','=',NULL)
                        ->where($criterio,'=',$buscar)
                        ->orderBy('contratos.id','asc')
                        ->get();
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                }
            }

        }

        $contador = $contratos->count();

        return [
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    public function ingresarExp(Request $request){
        $asignar = Expediente::findOrFail($request->folio);
        $asignar->fecha_ingreso =  $request->fecha_ingreso;
        $asignar->valor_escrituras =  $request->valor_escrituras;
        $asignar->save();
    }

    public function inscribirInfonavit(Request $request){
        $expediente = Expediente::findOrFail($request->folio);
        $expediente->fecha_infonavit =  $request->fecha_infonavit;
        $expediente->save();
    }

    public function indexAutorizados(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;


        if ($buscar == ''){
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                ->join('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->select(
                    'contratos.id as folio',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.precio_venta',
                    'licencias.avance as avance_lote',
                    'contratos.fecha_status',
                    'i.tipo_credito',
                    'i.institucion',
                    'i.fecha_vigencia',
                    'creditos.credito_solic',
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'lotes.regimen_condom',
                    'lotes.credito_puente',
                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                    DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                    'clientes.coacreditado',
                    'contratos.integracion',
                    'lotes.fraccionamiento_id',
                    'expedientes.valor_escrituras',
                    'expedientes.fecha_ingreso',
                    'expedientes.fecha_integracion',
                    'expedientes.fecha_infonavit',
                    'lotes.calle','lotes.numero','lotes.interior',
                    'avaluos.resultado','avaluos.fecha_recibido'
                )
                ->where('i.elegido', '=', 1)
                ->where('i.status','=',2)
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->where('expedientes.fecha_ingreso','!=',NULL)
                ->where('expedientes.valor_escrituras','!=',0)
                ->where('expedientes.fecha_infonavit','=',NULL)
                
                
                ->orderBy('contratos.id','asc')
                ->get();
        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                        ->join('expedientes','contratos.id','=','expedientes.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select(
                            'contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.precio_venta',
                            'creditos.credito_solic',
                            'licencias.avance as avance_lote',
                            'contratos.fecha_status',
                            'i.tipo_credito',
                            'i.institucion',
                            'i.fecha_vigencia',
                            'contratos.avaluo_preventivo',
                            'contratos.aviso_prev',
                            'contratos.aviso_prev_venc',
                            'lotes.regimen_condom',
                            'lotes.credito_puente',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                            DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                            'clientes.coacreditado',
                            'contratos.integracion',
                            'lotes.fraccionamiento_id',
                            'expedientes.valor_escrituras',
                            'expedientes.fecha_ingreso',
                            'expedientes.fecha_integracion',
                            'expedientes.fecha_infonavit',
                            'lotes.calle','lotes.numero','lotes.interior',
                            'avaluos.resultado','avaluos.fecha_recibido'
                        )
                        ->where('i.elegido', '=', 1)
                        ->where('i.status','=',2)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('expedientes.fecha_ingreso','!=',NULL)
                        ->where('expedientes.valor_escrituras','!=',0)
                        ->where('expedientes.fecha_infonavit','=',NULL)
                        
                        
                        ->where('c.nombre','like','%'. $buscar . '%')
                        ->orWhere('i.elegido', '=', 1)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('expedientes.fecha_ingreso','!=',NULL)
                        ->where('expedientes.valor_escrituras','!=',0)
                        ->where('expedientes.fecha_infonavit','=',NULL)
                        
                        
                        ->where('c.apellidos','like','%'. $buscar . '%')
                        ->orderBy('contratos.id','asc')
                        ->get();
                    break;
                }
                case 'contratos.id':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                        ->join('expedientes','contratos.id','=','expedientes.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select(
                            'contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.precio_venta',
                            'creditos.credito_solic',
                            'licencias.avance as avance_lote',
                            'contratos.fecha_status',
                            'i.tipo_credito',
                            'i.institucion',
                            'i.fecha_vigencia',
                            'contratos.avaluo_preventivo',
                            'contratos.aviso_prev',
                            'contratos.aviso_prev_venc',
                            'lotes.regimen_condom',
                            'lotes.credito_puente',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                            DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                            'clientes.coacreditado',
                            'contratos.integracion',
                            'lotes.fraccionamiento_id',
                            'expedientes.valor_escrituras',
                            'expedientes.fecha_ingreso',
                            'expedientes.fecha_integracion',
                            'expedientes.fecha_infonavit',
                            'lotes.calle','lotes.numero','lotes.interior',
                            'avaluos.resultado','avaluos.fecha_recibido'
                        )
                        ->where('i.elegido', '=', 1)
                        ->where('i.status','=',2)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('expedientes.fecha_ingreso','!=',NULL)
                        ->where('expedientes.valor_escrituras','!=',0)
                        ->where('expedientes.fecha_infonavit','=',NULL)
                        
                        
                        ->where($criterio,'=',$buscar)
                        ->orderBy('contratos.id','asc')
                        ->get();
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio, '=', $buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                }
            }

        }

        $contador = $contratos->count();

        return [
            
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    public function noAplicaInfonavit(Request $request){
        if(!$request->ajax())return redirect('/');
        $expediente = Expediente::findOrFail($request->folio);
        $expediente->fecha_infonavit = "0000-01-01";
        $expediente->save();
    }

    public function indexLiquidacion(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;


        if ($buscar == ''){
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                ->join('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->select(
                    'contratos.id as folio',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.precio_venta',
                    'licencias.avance as avance_lote',
                    'contratos.fecha_status',
                    'i.tipo_credito',
                    'i.institucion',
                    'i.fecha_vigencia',
                    'creditos.credito_solic',
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'contratos.infonavit',
                    'contratos.fovisste',
                    'lotes.regimen_condom',
                    'lotes.credito_puente',
                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                    DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                    'clientes.coacreditado',
                    'contratos.integracion',
                    'lotes.fraccionamiento_id',
                    'expedientes.valor_escrituras',
                    'expedientes.fecha_ingreso',
                    'expedientes.fecha_integracion',
                    'expedientes.fecha_liquidacion',
                    'expedientes.liquidado',
                    'expedientes.infonavit',
                    'expedientes.fovissste',
                    'expedientes.total_liquidar',
                    'expedientes.fecha_infonavit',
                    'lotes.calle','lotes.numero','lotes.interior',
                    'avaluos.resultado','avaluos.fecha_recibido'
                )
                ->where('i.elegido', '=', 1)
                ->where('i.status','=',2)
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->where('expedientes.fecha_ingreso','!=',NULL)
                ->where('expedientes.valor_escrituras','!=',0)
                ->where('expedientes.fecha_infonavit','!=',NULL)
                ->where('expedientes.liquidado','=',0)
                
                
                ->orderBy('contratos.id','asc')
                ->get();
        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                        ->join('expedientes','contratos.id','=','expedientes.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select(
                            'contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.precio_venta',
                            'creditos.credito_solic',
                            'licencias.avance as avance_lote',
                            'contratos.fecha_status',
                            'i.tipo_credito',
                            'i.institucion',
                            'i.fecha_vigencia',
                            'contratos.avaluo_preventivo',
                            'contratos.aviso_prev',
                            'contratos.aviso_prev_venc',
                            'lotes.regimen_condom',
                            'lotes.credito_puente',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                            DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                            'clientes.coacreditado',
                            'contratos.integracion',
                            'lotes.fraccionamiento_id',
                            'expedientes.valor_escrituras',
                            'expedientes.fecha_ingreso',
                            'expedientes.fecha_integracion',
                            'expedientes.fecha_liquidacion',
                            'expedientes.liquidado',
                            'expedientes.infonavit',
                            'expedientes.fovissste',
                            'expedientes.total_liquidar',
                            'expedientes.fecha_infonavit',
                            'lotes.calle','lotes.numero','lotes.interior',
                            'avaluos.resultado','avaluos.fecha_recibido'
                        )
                        ->where('i.elegido', '=', 1)
                        ->where('i.status','=',2)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('expedientes.fecha_ingreso','!=',NULL)
                        ->where('expedientes.valor_escrituras','!=',0)
                        ->where('expedientes.fecha_infonavit','!=',NULL)
                        ->where('expedientes.liquidado','=',0)
                        ->where('c.nombre','like','%'. $buscar . '%')

                        ->orWhere('i.elegido', '=', 1)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('expedientes.fecha_ingreso','!=',NULL)
                        ->where('expedientes.valor_escrituras','!=',0)
                        ->where('expedientes.fecha_infonavit','!=',NULL)
                        ->where('expedientes.liquidado','=',0)
                        ->where('c.apellidos','like','%'. $buscar . '%')
                        ->orderBy('contratos.id','asc')
                        ->get();
                    break;
                }
                case 'contratos.id':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                        ->join('expedientes','contratos.id','=','expedientes.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->join('personal as v', 'vendedores.id', '=', 'v.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select(
                            'contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.precio_venta',
                            'creditos.credito_solic',
                            'licencias.avance as avance_lote',
                            'contratos.fecha_status',
                            'i.tipo_credito',
                            'i.institucion',
                            'i.fecha_vigencia',
                            'contratos.avaluo_preventivo',
                            'contratos.aviso_prev',
                            'contratos.aviso_prev_venc',
                            'lotes.regimen_condom',
                            'lotes.credito_puente',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                            DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                            DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                            'clientes.coacreditado',
                            'contratos.integracion',
                            'lotes.fraccionamiento_id',
                            'expedientes.valor_escrituras',
                            'expedientes.fecha_ingreso',
                            'expedientes.fecha_integracion',
                            'expedientes.fecha_liquidacion',
                            'expedientes.liquidado',
                            'expedientes.infonavit',
                            'expedientes.fovissste',
                            'expedientes.total_liquidar',
                            'expedientes.fecha_infonavit',
                            'lotes.calle','lotes.numero','lotes.interior',
                            'avaluos.resultado','avaluos.fecha_recibido'
                        )
                        ->where('i.elegido', '=', 1)
                        ->where('i.status','=',2)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('expedientes.fecha_ingreso','!=',NULL)
                        ->where('expedientes.valor_escrituras','!=',0)
                        ->where('expedientes.fecha_infonavit','!=',NULL)
                        ->where('expedientes.liquidado','=',0)
                        
                        ->where($criterio,'=',$buscar)
                        ->orderBy('contratos.id','asc')
                        ->get();
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_liquidacion',
                                'expedientes.liquidado',
                                'expedientes.infonavit',
                                'expedientes.fovissste',
                                'expedientes.total_liquidar',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio, '=', $buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_liquidacion',
                                'expedientes.liquidado',
                                'expedientes.infonavit',
                                'expedientes.fovissste',
                                'expedientes.total_liquidar',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_liquidacion',
                                'expedientes.liquidado',
                                'expedientes.infonavit',
                                'expedientes.fovissste',
                                'expedientes.total_liquidar',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_liquidacion',
                                'expedientes.liquidado',
                                'expedientes.infonavit',
                                'expedientes.fovissste',
                                'expedientes.total_liquidar',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_liquidacion',
                                'expedientes.liquidado',
                                'expedientes.infonavit',
                                'expedientes.fovissste',
                                'expedientes.total_liquidar',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_liquidacion',
                                'expedientes.liquidado',
                                'expedientes.infonavit',
                                'expedientes.fovissste',
                                'expedientes.total_liquidar',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_liquidacion',
                                'expedientes.liquidado',
                                'expedientes.infonavit',
                                'expedientes.fovissste',
                                'expedientes.total_liquidar',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                            ->join('expedientes','contratos.id','=','expedientes.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->select(
                                'contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.num_lote',
                                'creditos.precio_venta',
                                'creditos.credito_solic',
                                'licencias.avance as avance_lote',
                                'contratos.fecha_status',
                                'i.tipo_credito',
                                'i.institucion',
                                'i.fecha_vigencia',
                                'contratos.avaluo_preventivo',
                                'contratos.aviso_prev',
                                'contratos.aviso_prev_venc',
                                'lotes.regimen_condom',
                                'lotes.credito_puente',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                                'clientes.coacreditado',
                                'contratos.integracion',
                                'lotes.fraccionamiento_id',
                                'expedientes.valor_escrituras',
                                'expedientes.fecha_ingreso',
                                'expedientes.fecha_integracion',
                                'expedientes.fecha_liquidacion',
                                'expedientes.liquidado',
                                'expedientes.infonavit',
                                'expedientes.fovissste',
                                'expedientes.total_liquidar',
                                'expedientes.fecha_infonavit',
                                'lotes.calle','lotes.numero','lotes.interior',
                                'avaluos.resultado','avaluos.fecha_recibido'
                            )
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->orderBy('contratos.id','asc')
                            ->get();
                    }
                }
            }

        }

        $contador = $contratos->count();

        return [
            
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    public function pagaresExpediente(Request $request)
    {
        $folio = $request->folio;
        $pagares = Pago_contrato::select('contrato_id','num_pago','fecha_pago','monto_pago','restante','pagado')
                    ->where('contrato_id','=',$folio)
                    ->where('pagado','!=',2)
                    ->orderBy('contrato_id','asc')
                    ->orderBy('num_pago','asc')->get();

        $calculos = Pago_contrato::select(DB::raw("SUM(monto_pago) as enganche"),
                        DB::raw("SUM(restante) as total_restante"))
                    ->groupBy('contrato_id')
                    ->where('contrato_id','=',$folio)
                    ->get();

        return ['pagares' => $pagares,
                'calculos' => $calculos];
    }

    public function setLiquidacion(Request $request){


        $expediente = Expediente::findOrFail($request->folio);
        $expediente->fecha_liquidacion = $request->fecha_liquidacion;
        $expediente->valor_escrituras = $request->valor_escrituras;
        $expediente->descuento = $request->descuento;

        if($request->total_liquidar == 0){
            $expediente->total_liquidar = $request->total_liquidar;
            $expediente->liquidado = 2;
        }else{
            $expediente->total_liquidar = $request->total_liquidar;
            $expediente->liquidado = 0;
        }
        
        $expediente->infonavit = $request->infonavit;
        $expediente->fovissste = $request->fovissste;
        $expediente->save();
    }

    public function generarPagares(Request $request){
        $liquidacion = new Liquidacion();
        $liquidacion->id = $request->folio;
        $liquidacion->interes_ord = $request->intereses_ordinarios;
        $liquidacion->interes_mor = $request->intereses_moratorios;
        $liquidacion->fecha_ini_interes = $request->fecha_ini_interes;
        $liquidacion->nombre_aval = $request->nombre_aval;
        $liquidacion->direccion =  $request->direccion_aval;
        $liquidacion->telefono = $request->telefono_aval;
        $liquidacion->save();

        $expediente = Expediente::findOrFail($request->folio);
        $expediente->liquidado = 1;
        $expediente->save();

        $pagares = $request->pagares;

        $pagaresContrato = Pago_contrato::select('id','pagado','contrato_id')
                                        ->where('contrato_id','=',$request->folio)
                                        ->where('pagado','=',1)
                                        ->orWhere('pagado','=',0)
                                        ->where('contrato_id','=',$request->folio)
                                        ->get();

        foreach ($pagaresContrato as $pagaresAnteriores){
            $pagaresCambio = Pago_contrato::findOrFail($pagaresAnteriores->id);
            $pagaresCambio->pagado = 3;
            $pagaresCambio->save();
        }


        foreach($pagares as $ep=>$det)
            {
                $pagos = new Pago_contrato();
                $pagos->contrato_id = $request->folio;
                $pagos->num_pago = $ep;
                $pagos->monto_pago = $det['monto_pago'];
                $pagos->fecha_pago = $det['fecha_pago'];
                $pagos->restante = $det['monto_pago'];
                $pagos->pagado = 0;
                $pagos->tipo_pagare = 1;
                $pagos->save();

            }
    }

}
