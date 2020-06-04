<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use DB;
use Auth;
use App\Vendedor;
use App\Expediente;
use Excel;
use Carbon\Carbon;
use App\Pago_contrato;
use NumerosEnLetras;
use App\Lote;
use App\Credito;
use App\Det_com_cancelada;
use App\Det_com_individualizada;
use App\Det_com_pendiente;
use App\Det_com_venta;
use App\Comision_venta;
use App\Bono_venta;

class ComisionesVentaController extends Controller
{

    public function getComision(Request $request){

        $vendedor = Vendedor::findOrFail($request->vendedor);
        $tipo = $vendedor->tipo;
        $esquema = $vendedor->esquema;
        $isr = $vendedor->isr;
        $retencion = $vendedor->retencion;

        $sueldo = !Carbon::parse($vendedor->fecha_sueldo)->gt(Carbon::now());
        
        $ventas = $this->getVentas($request->vendedor, $request->mes, $request->anio);
        $acum = $this->getAcumuladoAnt($request->vendedor, $request->mes, $request->anio);
        $cancelaciones = $this->getCanceladas($request->vendedor, $request->mes, $request->anio);
        $individualizadas = $this->getIndividualizadas($request->vendedor, $request->mes, $request->anio);
        $pendientes = $this->getPendientes($request->vendedor);

        $numVentas = $ventas->count();
        $numIndividualizadas = $individualizadas->count();
        $numCancelaciones = $cancelaciones->count();

        return [
            'ventas' => $ventas, 
            'numVentas' => $numVentas, 
            'numIndividualizadas' => $numIndividualizadas,
            'numCancelaciones' => $numCancelaciones,
            'retencion'=>$retencion,
            'isr'=>$isr,
            'acumuladoAnt'=>$acum,
            'esquema'=>$esquema,
            'tipo'=>$tipo,
            'sueldo'=>$sueldo,
            'canceladas'=>$cancelaciones,
            'individualizadas'=>$individualizadas,
            'pendientes'=>$pendientes
            
        ];
    }

    private function getVentas($vendedor, $mes, $anio){

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','lotes.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')

                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'vendedores.tipo',
                            'vendedores.esquema',
                            'creditos.fraccionamiento as proyecto',
                            'inst_seleccionadas.tipo_credito',
                            'inst_seleccionadas.institucion',
                            'creditos.etapa','creditos.manzana',
                            'creditos.num_lote',
                            'contratos.porcentaje_exp',
                            'contratos.fecha_exp',
                            'contratos.fecha',
                            'lotes.extra',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'lotes.extra_ext')
                            
                    ->where('contratos.status','=',3)
                    ->where('contratos.comision','=',0)
                    ->where('inst_seleccionadas.elegido', '=', '1')
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->whereMonth('contratos.fecha',$mes)
                    ->whereYear('contratos.fecha',$anio)
                    ->orderBy('contratos.id','asc')
                    ->get();

        if(sizeOf($ventas)){
            foreach($ventas as $index => $venta){
                $pagos = Pago_contrato::select('pagado')
                                        ->where('contrato_id','=',$venta->id)
                                        ->where('tipo_pagare','=',0)
                                        ->orderBy('fecha_pago','asc')
                                        ->get();
                
                if(sizeof($pagos)){
                    $venta->pagado = $pagos[0]->pagado;
                }

                $venta->indiv = 0;

                if($venta->tipo_credito == 'Crédito Directo'){
                    $expedientes = Expediente::select('liquidado')->where('id','=',$venta->id)->where('liquidado','=',1)->get();
                    if(sizeof($expedientes)){
                        $venta->indiv = 1;
                    }
                }
                else{
                    $expedientes = Expediente::select('liquidado')->where('id','=',$venta->id)->where('fecha_firma_esc','!=',NULL)->get();
                    if(sizeof($expedientes)){
                        $venta->indiv = 1;
                    }
                }
            }
        }

        return $ventas;
    }

    private function getAcumuladoAnt($vendedor, $mes, $anio){
        if($mes == '01'){
            $mesAnt = 12;
            $anioAnt = $anio - 1;
        }
        else{
            $mesAnt = $mes - 1;
            $anioAnt = $anio;
        }

        $restante = Comision_venta::where('asesor_id','=',$vendedor)->where('mes','=',$mesAnt)->where('anio','=',$anioAnt)->get();
        $acum = 0;

        if(sizeof($restante)){
            if($restante[0]->total_pagado <= 0){
                $acum =$restante[0]->total_pagado;
            }
        }

        return $acum;
    }

    private function getCanceladas($vendedor, $mes, $anio){

        $cancelaciones = Det_com_venta::join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->select('este_pago','contratos.id',
                                'det_com_ventas.porcentaje_comision',
                                'det_com_ventas.comision_pagar',
                                'det_com_ventas.iva',
                                'det_com_ventas.isr',
                                'det_com_ventas.retencion',
                                'det_com_ventas.este_pago',
                                'det_com_ventas.por_pagar'
                        )
                                            ->where('contratos.status','=',0)
                                            ->where('creditos.vendedor_id','=',$vendedor)
                                            ->whereMonth('contratos.fecha_status',$mes)
                                            ->whereYear('contratos.fecha_status',$anio)
                                            ->get();

        if(sizeOf($cancelaciones)){
            foreach($cancelaciones as $index => $cancelada){
                $bonos = Bono_venta::select( DB::raw("SUM(monto) as bono") )->where('contrato_id','=',$cancelada->id)->get();
                $cancelada->bono = $bonos[0]->bono;
            }
        }
        
        
        
        return $cancelaciones;
    }

    private function getIndividualizadas($vendedor, $mes, $anio){
        $individualizadas = Det_com_venta::join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('expedientes','expedientes.id','=','contratos.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('por_pagar','contratos.id',
                                'creditos.num_lote',
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'inst_seleccionadas.tipo_credito',
                                'inst_seleccionadas.institucion',
                                'det_com_ventas.porcentaje_comision',
                                'det_com_ventas.comision_pagar',
                                'det_com_ventas.iva',
                                'det_com_ventas.isr',
                                'det_com_ventas.retencion',
                                'det_com_ventas.este_pago',
                                'det_com_ventas.por_pagar')
                                            ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('contratos.status','=',3)
                                            ->where('contratos.comision','=',1)
                                            ->where('expedientes.liquidado','=',1)
                                            ->where('creditos.vendedor_id','=',$vendedor)
                                            ->whereMonth('expedientes.fecha_liquidacion',$mes)
                                            ->whereYear('expedientes.fecha_liquidacion',$anio)
                                            ->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('contratos.status','=',3)
                                            ->where('contratos.comision','=',1)
                                            ->where('creditos.vendedor_id','=',$vendedor)
                                            ->whereMonth('expedientes.fecha_firma_esc',$mes)
                                            ->whereYear('expedientes.fecha_firma_esc',$anio)
                                            ->get();
        
        return $individualizadas;
    }

    private function getPendientes($vendedor){
        $pendientes = Det_com_venta::join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('pagos_contratos','contratos.id','=','pagos_contratos.contrato_id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->select('este_pago','contratos.id',
                                        'det_com_ventas.porcentaje_comision',
                                        'det_com_ventas.comision_pagar',
                                        'det_com_ventas.iva',
                                        'det_com_ventas.isr',
                                        'det_com_ventas.retencion',
                                        'det_com_ventas.este_pago',
                                        'det_com_ventas.por_pagar')
                                            ->where('contratos.status','=',0)
                                            ->where('contratos.comision','=',1)
                                            ->where('det_com_ventas.este_pago','=',0)
                                            ->where('pagos_contratos.num_pago','=',0)
                                            ->where('pagos_contratos.pagado','=',2)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('creditos.vendedor_id','=',$vendedor)
                                            ->get();

        if(sizeOf($pendientes)){
            foreach($pendientes as $index => $pendiente){
                
                $pendiente->indiv = 0;

                if($pendiente->tipo_credito == 'Crédito Directo'){
                    $expedientes = Expediente::select('liquidado')->where('id','=',$pendiente->id)->where('liquidado','=',1)->get();
                    if(sizeof($expedientes)){
                        $pendiente->indiv = 1;
                    }
                }
                else{
                    $expedientes = Expediente::select('liquidado')->where('id','=',$pendiente->id)->where('fecha_firma_esc','!=',NULL)->get();
                    if(sizeof($expedientes)){
                        $pendiente->indiv = 1;
                    }
                }

            }
        }
        
        return $pendientes;
    }


}
