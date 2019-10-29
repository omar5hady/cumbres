<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipamiento;
use App\Contrato;
use DB;
use Carbon\Carbon;
use Auth;
use App\Solic_equipamiento;
use App\Obs_solic_equipamiento;

class SolEquipamientoController extends Controller
{
    public function indexHistorial(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $userID = Auth::user()->id;
        $rolID = Auth::user()->rol_id;
        $status = $request->status;


        if($rolID != 10){
            if($status==''){
                if($buscar == ''){
                    $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                            ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                        ->select('solic_equipamientos.fecha_solicitud',
                                'solic_equipamientos.id',
                                'solic_equipamientos.lote_id',
                                'solic_equipamientos.costo',
                                'solic_equipamientos.fecha_solicitud',
                                'solic_equipamientos.fecha_colocacion',
                                'solic_equipamientos.anticipo',
                                'solic_equipamientos.fecha_anticipo',
                                'solic_equipamientos.liquidacion',
                                'solic_equipamientos.fecha_liquidacion',
                                'solic_equipamientos.fin_instalacion',
                                'solic_equipamientos.status',
                                'solic_equipamientos.recepcion',
                                'solic_equipamientos.num_factura',
                                'solic_equipamientos.control',
                                'proveedores.proveedor',
                                'equipamientos.equipamiento',
                                'equipamientos.tipoRecepcion',
                                'contratos.id as folio',
                                'creditos.modelo',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                'fraccionamientos.nombre as proyecto',
                                'etapas.num_etapa as etapa',
                                'lotes.manzana',
                                'lotes.num_lote','licencias.avance',
                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                            ->orderBy('contratos.id','desc')
                            ->orderBy('proveedores.proveedor','asc')
                            ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                        ->paginate(8);

                }
                else{
                    switch($criterio){
                        case 'c.nombre':{
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%')
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                            break;
                        }
                        case 'contratos.id':{
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                            break;
                        }
                        case 'proveedores.proveedor':{
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, 'like', '%'. $buscar . '%')
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                            break;
                        }
                        case 'lotes.fraccionamiento_id':{
                            if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                                
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                                
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('lotes.num_lote', '=', $b_lote)
                                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                                
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('lotes.num_lote', '=', $b_lote)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.num_lote', '=', $b_lote)
                                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.num_lote', '=', $b_lote)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            break;
                        }
                    }
                }
            }
            else{
                if($buscar == ''){
                    $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                            ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                        ->select('solic_equipamientos.fecha_solicitud',
                                'solic_equipamientos.id',
                                'solic_equipamientos.lote_id',
                                'solic_equipamientos.costo',
                                'solic_equipamientos.fecha_solicitud',
                                'solic_equipamientos.fecha_colocacion',
                                'solic_equipamientos.anticipo',
                                'solic_equipamientos.fecha_anticipo',
                                'solic_equipamientos.liquidacion',
                                'solic_equipamientos.fecha_liquidacion',
                                'solic_equipamientos.fin_instalacion',
                                'solic_equipamientos.status',
                                'solic_equipamientos.recepcion',
                                'solic_equipamientos.num_factura',
                                'solic_equipamientos.control',
                                'proveedores.proveedor',
                                'equipamientos.equipamiento',
                                'equipamientos.tipoRecepcion',
                                'contratos.id as folio',
                                'creditos.modelo',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                'fraccionamientos.nombre as proyecto',
                                'etapas.num_etapa as etapa',
                                'lotes.manzana',
                                'lotes.num_lote','licencias.avance',
                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                            ->where('solic_equipamientos.status','=',$status)
                            ->orderBy('contratos.id','desc')
                            ->orderBy('proveedores.proveedor','asc')
                            ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                        ->paginate(8);

                }
                else{
                    switch($criterio){
                        case 'c.nombre':{
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%')
                                    ->where('solic_equipamientos.status','=',$status)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                            break;
                        }
                        case 'contratos.id':{
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->where('solic_equipamientos.status','=',$status)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                            break;
                        }
                        case 'proveedores.proveedor':{
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, 'like', '%'. $buscar . '%')
                                    ->where('solic_equipamientos.status','=',$status)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                            break;
                        }
                        case 'lotes.fraccionamiento_id':{
                            if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('solic_equipamientos.status','=',$status)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('solic_equipamientos.status','=',$status)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                                
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                        ->where('solic_equipamientos.status','=',$status)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                                
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('lotes.num_lote', '=', $b_lote)
                                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                        ->where('solic_equipamientos.status','=',$status)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                                
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('lotes.num_lote', '=', $b_lote)
                                        ->where('solic_equipamientos.status','=',$status)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.num_lote', '=', $b_lote)
                                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                        ->where('solic_equipamientos.status','=',$status)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.num_lote', '=', $b_lote)
                                        ->where('solic_equipamientos.status','=',$status)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                            ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                            ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                            ->join('creditos','contratos.id','=','creditos.id')
                                            ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                            ->join('personal as c', 'clientes.id', '=', 'c.id')

                                        ->select('solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.id',
                                                'solic_equipamientos.lote_id',
                                                'solic_equipamientos.costo',
                                                'solic_equipamientos.fecha_solicitud',
                                                'solic_equipamientos.fecha_colocacion',
                                                'solic_equipamientos.anticipo',
                                                'solic_equipamientos.fecha_anticipo',
                                                'solic_equipamientos.liquidacion',
                                                'solic_equipamientos.fecha_liquidacion',
                                                'solic_equipamientos.fin_instalacion',
                                                'solic_equipamientos.status',
                                                'solic_equipamientos.recepcion',
                                                'solic_equipamientos.num_factura',
                                                'solic_equipamientos.control',
                                                'proveedores.proveedor',
                                                'equipamientos.equipamiento',
                                                'equipamientos.tipoRecepcion',
                                                'contratos.id as folio',
                                                'creditos.modelo',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'fraccionamientos.nombre as proyecto',
                                                'etapas.num_etapa as etapa',
                                                'lotes.manzana',
                                                'lotes.num_lote','licencias.avance',
                                                DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                                DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                        ->where('solic_equipamientos.status','=',$status)
                                        ->orderBy('contratos.id','desc')
                                        ->orderBy('proveedores.proveedor','asc')
                                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                    ->paginate(8);
                            }
                            break;
                        }
                    }
                }
            }
        }else{
            if($buscar == ''){
                $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                        ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                    ->select('solic_equipamientos.fecha_solicitud',
                            'solic_equipamientos.id',
                            'solic_equipamientos.lote_id',
                            'solic_equipamientos.costo',
                            'solic_equipamientos.fecha_solicitud',
                            'solic_equipamientos.fecha_colocacion',
                            'solic_equipamientos.anticipo',
                            'solic_equipamientos.fecha_anticipo',
                            'solic_equipamientos.liquidacion',
                            'solic_equipamientos.fecha_liquidacion',
                            'solic_equipamientos.fin_instalacion',
                            'solic_equipamientos.status',
                            'solic_equipamientos.recepcion',
                            'solic_equipamientos.num_factura',
                            'solic_equipamientos.control',
                            'proveedores.proveedor',
                            'equipamientos.equipamiento',
                            'equipamientos.tipoRecepcion',
                            'contratos.id as folio',
                            'creditos.modelo',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'fraccionamientos.nombre as proyecto',
                            'etapas.num_etapa as etapa',
                            'lotes.manzana',
                            'lotes.num_lote','licencias.avance',
                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                        ->where('proveedores.id','=',$userID)
                        ->where('contratos.entregado','=',0)
                        ->orderBy('contratos.id','desc')
                        ->orderBy('proveedores.proveedor','asc')
                        ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                    ->paginate(8);

            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                    ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                    ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                    ->join('creditos','contratos.id','=','creditos.id')
                                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                    ->join('personal as c', 'clientes.id', '=', 'c.id')

                                ->select('solic_equipamientos.fecha_solicitud',
                                        'solic_equipamientos.id',
                                        'solic_equipamientos.lote_id',
                                        'solic_equipamientos.costo',
                                        'solic_equipamientos.fecha_solicitud',
                                        'solic_equipamientos.fecha_colocacion',
                                        'solic_equipamientos.anticipo',
                                        'solic_equipamientos.fecha_anticipo',
                                        'solic_equipamientos.liquidacion',
                                        'solic_equipamientos.fecha_liquidacion',
                                        'solic_equipamientos.fin_instalacion',
                                        'solic_equipamientos.status',
                                        'solic_equipamientos.recepcion',
                                        'solic_equipamientos.num_factura',
                                        'solic_equipamientos.control',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'equipamientos.tipoRecepcion',
                                        'contratos.id as folio',
                                        'creditos.modelo',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'fraccionamientos.nombre as proyecto',
                                        'etapas.num_etapa as etapa',
                                        'lotes.manzana',
                                        'lotes.num_lote','licencias.avance',
                                        DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                        DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%')
                                ->where('proveedores.id','=',$userID)
                                ->where('contratos.entregado','=',0)
                                ->orderBy('contratos.id','desc')
                                ->orderBy('proveedores.proveedor','asc')
                                ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                            ->paginate(8);
                        break;
                    }
                    case 'contratos.id':{
                        $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                    ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                    ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                    ->join('creditos','contratos.id','=','creditos.id')
                                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                    ->join('personal as c', 'clientes.id', '=', 'c.id')

                                ->select('solic_equipamientos.fecha_solicitud',
                                        'solic_equipamientos.id',
                                        'solic_equipamientos.lote_id',
                                        'solic_equipamientos.costo',
                                        'solic_equipamientos.fecha_solicitud',
                                        'solic_equipamientos.fecha_colocacion',
                                        'solic_equipamientos.anticipo',
                                        'solic_equipamientos.fecha_anticipo',
                                        'solic_equipamientos.liquidacion',
                                        'solic_equipamientos.fecha_liquidacion',
                                        'solic_equipamientos.fin_instalacion',
                                        'solic_equipamientos.status',
                                        'solic_equipamientos.recepcion',
                                        'solic_equipamientos.num_factura',
                                        'solic_equipamientos.control',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'equipamientos.tipoRecepcion',
                                        'contratos.id as folio',
                                        'creditos.modelo',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'fraccionamientos.nombre as proyecto',
                                        'etapas.num_etapa as etapa',
                                        'lotes.manzana',
                                        'lotes.num_lote','licencias.avance',
                                        DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                        DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                ->where($criterio, '=', $buscar)
                                ->where('proveedores.id','=',$userID)
                                ->where('contratos.entregado','=',0)
                                ->orderBy('contratos.id','desc')
                                ->orderBy('proveedores.proveedor','asc')
                                ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                            ->paginate(8);
                        break;
                    }
                    case 'proveedores.proveedor':{
                        $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                    ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                    ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                    ->join('creditos','contratos.id','=','creditos.id')
                                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                    ->join('personal as c', 'clientes.id', '=', 'c.id')

                                ->select('solic_equipamientos.fecha_solicitud',
                                        'solic_equipamientos.id',
                                        'solic_equipamientos.lote_id',
                                        'solic_equipamientos.costo',
                                        'solic_equipamientos.fecha_solicitud',
                                        'solic_equipamientos.fecha_colocacion',
                                        'solic_equipamientos.anticipo',
                                        'solic_equipamientos.fecha_anticipo',
                                        'solic_equipamientos.liquidacion',
                                        'solic_equipamientos.fecha_liquidacion',
                                        'solic_equipamientos.fin_instalacion',
                                        'solic_equipamientos.status',
                                        'solic_equipamientos.recepcion',
                                        'solic_equipamientos.num_factura',
                                        'solic_equipamientos.control',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'equipamientos.tipoRecepcion',
                                        'contratos.id as folio',
                                        'creditos.modelo',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'fraccionamientos.nombre as proyecto',
                                        'etapas.num_etapa as etapa',
                                        'lotes.manzana',
                                        'lotes.num_lote','licencias.avance',
                                        DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                        DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                ->where($criterio, 'like', '%'. $buscar . '%')
                                ->where('proveedores.id','=',$userID)
                                ->where('contratos.entregado','=',0)
                                ->orderBy('contratos.id','desc')
                                ->orderBy('proveedores.proveedor','asc')
                                ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                            ->paginate(8);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->where('proveedores.id','=',$userID)
                                    ->where('contratos.entregado','=',0)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                        }
                        elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                    ->where('proveedores.id','=',$userID)
                                    ->where('contratos.entregado','=',0)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                            
                        }
                        elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                    ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                    ->where('proveedores.id','=',$userID)
                                    ->where('contratos.entregado','=',0)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                            
                        }
                        elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                    ->where('lotes.num_lote', '=', $b_lote)
                                    ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                    ->where('proveedores.id','=',$userID)
                                    ->where('contratos.entregado','=',0)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                            
                        }
                        elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                    ->where('lotes.num_lote', '=', $b_lote)
                                    ->where('proveedores.id','=',$userID)
                                    ->where('contratos.entregado','=',0)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                        }
                        elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->where('lotes.num_lote', '=', $b_lote)
                                    ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                    ->where('proveedores.id','=',$userID)
                                    ->where('contratos.entregado','=',0)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                        }
                        elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->where('lotes.num_lote', '=', $b_lote)
                                    ->where('proveedores.id','=',$userID)
                                    ->where('contratos.entregado','=',0)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                        }
                        elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                                        ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                                        ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                                    ->select('solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.id',
                                            'solic_equipamientos.lote_id',
                                            'solic_equipamientos.costo',
                                            'solic_equipamientos.fecha_solicitud',
                                            'solic_equipamientos.fecha_colocacion',
                                            'solic_equipamientos.anticipo',
                                            'solic_equipamientos.fecha_anticipo',
                                            'solic_equipamientos.liquidacion',
                                            'solic_equipamientos.fecha_liquidacion',
                                            'solic_equipamientos.fin_instalacion',
                                            'solic_equipamientos.status',
                                            'solic_equipamientos.recepcion',
                                            'solic_equipamientos.num_factura',
                                            'solic_equipamientos.control',
                                            'proveedores.proveedor',
                                            'equipamientos.equipamiento',
                                            'equipamientos.tipoRecepcion',
                                            'contratos.id as folio',
                                            'creditos.modelo',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            'fraccionamientos.nombre as proyecto',
                                            'etapas.num_etapa as etapa',
                                            'lotes.manzana',
                                            'lotes.num_lote','licencias.avance',
                                            DB::raw('DATEDIFF(current_date,solic_equipamientos.fecha_anticipo) as diferenciaIni'),
                                            DB::raw('DATEDIFF(solic_equipamientos.fin_instalacion,solic_equipamientos.fecha_anticipo) as diferenciaFin'))
                                    ->where($criterio, '=', $buscar)
                                    ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                                    ->where('proveedores.id','=',$userID)
                                    ->where('contratos.entregado','=',0)
                                    ->orderBy('contratos.id','desc')
                                    ->orderBy('proveedores.proveedor','asc')
                                    ->orderBy('solic_equipamientos.fecha_colocacion','asc')
                                ->paginate(8);
                        }
                        break;
                    }
                }
            }
        }
  

        
        return [
            'pagination' => [
                'total'        => $equipamientos->total(),
                'current_page' => $equipamientos->currentPage(),
                'per_page'     => $equipamientos->perPage(),
                'last_page'    => $equipamientos->lastPage(),
                'from'         => $equipamientos->firstItem(),
                'to'           => $equipamientos->lastItem(),
            ],
            'equipamientos' => $equipamientos,
        ];
    }

    public function actCosto(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->costo = $request->costo;
        $solicitud->save();
    }

    public function actAnticipo(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->fecha_anticipo = $request->fecha_anticipo;
        $solicitud->anticipo = $request->anticipo;
        $solicitud->save();
    }

    public function actLiquidacion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->fecha_liquidacion = $request->fecha_liquidacion;
        $solicitud->liquidacion = $request->liquidacion;
        $solicitud->save();
    }

    public function actColocacion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->fecha_colocacion = $request->fecha_colocacion;
        $solicitud->status = 2;
        $solicitud->save();

        $observacion = new Obs_solic_equipamiento();
        $observacion->solic_id = $request->id;
        $observacion->comentario ='Fecha programada de instalación: '.$request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function setInstalacion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->fin_instalacion = $request->fin_instalacion;
        $solicitud->status = 3;
        $solicitud->save();

        $observacion = new Obs_solic_equipamiento();
        $observacion->solic_id = $request->id;
        $observacion->comentario ='Fecha de instalación: '.$request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function indexRea(Request $request){
        $proyecto = $request->proyecto;
        $etapa = $request->etapa;
        $manzana = $request->manzana;

        if($proyecto==''){
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->select(
                        'creditos.id',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.modelo',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.promocion',
                        'creditos.descripcion_promocion',
                        'creditos.descuento_promocion',
                        'creditos.paquete',
                        'creditos.descripcion_paquete',
                        'creditos.lote_id'
                    )
                    ->where('contratos.status', '=', '3')
                    ->where('contratos.entregado', '=', '0')
                    ->orderBy('id', 'desc')->get();
        }
        elseif($etapa =='' && $manzana == ''){
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->select(
                        'creditos.id',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.modelo',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.promocion',
                        'creditos.descripcion_promocion',
                        'creditos.descuento_promocion',
                        'creditos.paquete',
                        'creditos.descripcion_paquete',
                        'creditos.lote_id'
                    )
                    ->where('contratos.status', '=', '3')
                    ->where('contratos.entregado', '=', '0')
                    ->where('lotes.fraccionamiento_id', '=', $proyecto)
                    ->orderBy('id', 'desc')->get();
        }
        elseif($etapa !='' && $manzana == ''){
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->select(
                        'creditos.id',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.modelo',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.promocion',
                        'creditos.descripcion_promocion',
                        'creditos.descuento_promocion',
                        'creditos.paquete',
                        'creditos.descripcion_paquete',
                        'creditos.lote_id'
                    )
                    ->where('contratos.status', '=', '3')
                    ->where('contratos.entregado', '=', '0')
                    ->where('lotes.fraccionamiento_id', '=', $proyecto)
                    ->where('lotes.etapa_id', '=', $etapa)
                    ->orderBy('id', 'desc')->get();
        }
        elseif($etapa !='' && $manzana != ''){
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->select(
                        'creditos.id',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.modelo',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.promocion',
                        'creditos.descripcion_promocion',
                        'creditos.descuento_promocion',
                        'creditos.paquete',
                        'creditos.descripcion_paquete',
                        'creditos.lote_id'
                    )
                    ->where('contratos.status', '=', '3')
                    ->where('contratos.entregado', '=', '0')
                    ->where('lotes.fraccionamiento_id', '=', $proyecto)
                    ->where('lotes.etapa_id', '=', $etapa)
                    ->where('lotes.manzan', 'like', '%'. $manzana . '%')
                    ->orderBy('id', 'desc')->get();
        }

        return [
            'contratos' => $contratos,
        ];


    }

    public function reubicar(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->lote_id = $request->lote_id;
        $solicitud->contrato_id = $request->contrato_id;
        $solicitud->control = 0;
        $solicitud->save();

        $observacion = new Obs_solic_equipamiento();
        $observacion->solic_id = $request->id;
        $observacion->comentario ='Equipamiento reubicado';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }
}
