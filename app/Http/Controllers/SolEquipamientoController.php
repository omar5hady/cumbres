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

        if($buscar == ''){
            $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                    ->join('proveedores','equipamientos.proveedor_id','=','proveedores.id')
                    ->join('contratos','solic_equipamientos.contrato_id','=','contratos.id')
                    ->join('lotes','solic_equipamientos.lote_id','=','lotes.id')
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
                        'solic_equipamientos.num_factura',
                        'proveedores.proveedor',
                        'equipamientos.equipamiento',
                        'contratos.id as folio',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote','licencias.avance')
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
                                    'solic_equipamientos.num_factura',
                                    'proveedores.proveedor',
                                    'equipamientos.equipamiento',
                                    'contratos.id as folio',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                    'creditos.fraccionamiento as proyecto',
                                    'creditos.etapa',
                                    'creditos.manzana',
                                    'creditos.num_lote','licencias.avance')
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
                                    'solic_equipamientos.num_factura',
                                    'proveedores.proveedor',
                                    'equipamientos.equipamiento',
                                    'contratos.id as folio',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                    'creditos.fraccionamiento as proyecto',
                                    'creditos.etapa',
                                    'creditos.manzana',
                                    'creditos.num_lote','licencias.avance')
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
                                    'solic_equipamientos.num_factura',
                                    'proveedores.proveedor',
                                    'equipamientos.equipamiento',
                                    'contratos.id as folio',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                    'creditos.fraccionamiento as proyecto',
                                    'creditos.etapa',
                                    'creditos.manzana',
                                    'creditos.num_lote','licencias.avance')
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
                                        'solic_equipamientos.num_factura',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote','licencias.avance')
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
                                        'solic_equipamientos.num_factura',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote','licencias.avance')
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
                                        'solic_equipamientos.num_factura',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote','licencias.avance')
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
                                        'solic_equipamientos.num_factura',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote','licencias.avance')
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
                                        'solic_equipamientos.num_factura',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote','licencias.avance')
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
                                        'solic_equipamientos.num_factura',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote','licencias.avance')
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
                                        'solic_equipamientos.num_factura',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote','licencias.avance')
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
                                        'solic_equipamientos.num_factura',
                                        'proveedores.proveedor',
                                        'equipamientos.equipamiento',
                                        'contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote','licencias.avance')
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
        if(!$request->ajax())return redirect('/'); 
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->costo = $request->costo;
        $solicitud->save();
    }

    public function actAnticipo(Request $request){
        if(!$request->ajax())return redirect('/'); 
        $solicitud = Solic_equipamiento::findOrFail($request->id);
        $solicitud->fecha_anticipo = $request->fecha_anticipo;
        $solicitud->anticipo = $request->anticipo;
        $solicitud->save();
    }

    public function actColocacion(Request $request){
        if(!$request->ajax())return redirect('/'); 
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
        if(!$request->ajax())return redirect('/'); 
        
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
}
