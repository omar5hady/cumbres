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
use App\Det_com_cambio;

class ComisionesVentaController extends Controller
{

    public function getComision(Request $request){

        $vendedor = Vendedor::findOrFail($request->vendedor);
        $tipo = $vendedor->tipo;
        $esquema = $vendedor->esquema;
        $isr = $vendedor->isr;
        $retencion = $vendedor->retencion;

        $numVentas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','lotes.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id')
                    ->where('contratos.status','!=',2)
                    ->where('inst_seleccionadas.elegido', '=', '1')
                    ->where('creditos.vendedor_id','=',$request->vendedor)
                    ->whereMonth('contratos.fecha',$request->mes)
                    ->whereYear('contratos.fecha',$request->anio)
                    ->count();

        $sueldo = !Carbon::parse($vendedor->fecha_sueldo)->gt(Carbon::now());
        
        $ventas = $this->getVentas($request->vendedor, $request->mes, $request->anio);
        $acum = $this->getAcumuladoAnt($request->vendedor, $request->mes, $request->anio);
        $cancelaciones = $this->getCanceladas($request->vendedor, $request->mes, $request->anio);
        $individualizadas = $this->getIndividualizadas($request->vendedor, $request->mes, $request->anio);
        $pendientes = $this->getPendientes($request->vendedor);

        $cambios = $this->getCambios($request->vendedor);

        //$numVentas = $ventas->count();
        $numIndividualizadas = $individualizadas->count();
        $numCancelaciones = $cancelaciones->count();
        $numPendientes = $pendientes->count();
        $numCambios = $cambios->count();

        return [
            'ventas' => $ventas, 
            'numVentas' => $numVentas, 
            'numIndividualizadas' => $numIndividualizadas,
            'numCancelaciones' => $numCancelaciones,
            'numPendientes' => $numPendientes,
            'numCambios' => $numCambios,
            'retencion'=>$retencion,
            'isr'=>$isr,
            'acumuladoAnt'=>$acum,
            'esquema'=>$esquema,
            'tipo'=>$tipo,
            'sueldo'=>$sueldo,
            'canceladas'=>$cancelaciones,
            'individualizadas'=>$individualizadas,
            'pendientes'=>$pendientes,
            'cambios'=>$cambios
            
        ];
    }

    public function indexComisiones(Request $request){
        $mes = $request->b_mes;
        $anio = $request->b_anio;
        $asesor = $request->b_asesor_id;

        setlocale(LC_TIME, 'es_MX.utf8');
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $query = Comision_venta::join('vendedores','comisiones_ventas.asesor_id', '=','vendedores.id')
            ->join('personal','vendedores.id','=','personal.id')
            ->select('comisiones_ventas.mes','comisiones_ventas.anio',
                        'comisiones_ventas.total_pagado',
                        'comisiones_ventas.total_comision',
                        'comisiones_ventas.apoyo_mes',
                        'comisiones_ventas.asesor_id',
                        'comisiones_ventas.apoyo_mes',
                        'comisiones_ventas.total_porPagar',
                        'comisiones_ventas.restanteAnt',
                        'comisiones_ventas.id',
                        'comisiones_ventas.total_bono', 
                        'comisiones_ventas.total_anticipo', 
                        'comisiones_ventas.tipo_vendedor',
                        'comisiones_ventas.mes',
                        'comisiones_ventas.anio',
                        
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS asesor"),
                        'vendedores.tipo'
            );

        if($asesor == ''){
            if($mes == '' && $anio == ''){
                $comisiones = $query;
            }
            elseif($mes != '' && $anio == ''){
                $comisiones = $query
                        ->where('comisiones_ventas.mes','=',$mes);
            }
            elseif($mes == '' && $anio != ''){
                $comisiones = $query
                        ->where('comisiones_ventas.anio','=',$anio);
            }
            else{
                $comisiones = $query
                        ->where('comisiones_ventas.mes','=',$mes)
                        ->where('comisiones_ventas.anio','=',$anio);
            }

        }
        else{
            if($mes == '' && $anio == ''){
                $comisiones = $query
                        ->where('comisiones_ventas.asesor_id','=',$asesor);
            }
            elseif($mes != '' && $anio == ''){
                $comisiones = $query
                        ->where('comisiones_ventas.asesor_id','=',$asesor)
                        ->where('comisiones_ventas.mes','=',$mes);
            }
            elseif($mes == '' && $anio != ''){
                $comisiones = $query
                        ->where('comisiones_ventas.asesor_id','=',$asesor)
                        ->where('comisiones_ventas.anio','=',$anio);
            }
            else{
                $comisiones = $query
                        ->where('comisiones_ventas.asesor_id','=',$asesor)
                        ->where('comisiones_ventas.mes','=',$mes)
                        ->where('comisiones_ventas.anio','=',$anio);
            }

        }

        $comisiones = $comisiones->orderBy('comisiones_ventas.anio','desc')
                    ->orderBy('comisiones_ventas.mes','desc')
                    ->orderBy('asesor','asc')->paginate();
        

        return[
            'pagination' => [
                'total'         => $comisiones->total(),
                'current_page'  => $comisiones->currentPage(),
                'per_page'      => $comisiones->perPage(),
                'last_page'     => $comisiones->lastPage(),
                'from'          => $comisiones->firstItem(),
                'to'            => $comisiones->lastItem(),
            ],
            'comisiones' => $comisiones, 'month' => $month, 'year' => $year
        ];
    }

    public function desartarCambio(Request $request){
        $contrato = Contrato::FindOrFail($request->id);
        $contrato->comision = 1;
        $contrato->save();
    }

    public function getDetalle(Request $request){
        $ventas = $this->getVentasDetalle($request->comision_id);

        $individualizadas = $this->getIndividualizadasDetalle($request->comision_id);

        $canceladas = $this->getCanceladasDetalle($request->comision_id);

        $pendientes = $this->getPendienteDetalle($request->comision_id);

        $cambios = $this->getCambioDetalle($request->comision_id);

        return [
            'ventas' => $ventas,
            'numVentas' => $ventas->count(),
            'individualizadas' => $individualizadas,
            'numIndividualizadas' => $individualizadas->count(),
            'canceladas' => $canceladas,
            'numCanceladas' => $canceladas->count(),
            'pendientes' => $pendientes,
            'numPendientes' => $pendientes->count(),
            'cambios' => $cambios,
            'numCambios' => $cambios->count()
        ];
    }

    public function excelDetalle(Request $request){
        
        
    }

    public function storeComision(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        // try {
        //     DB::beginTransaction();
            $comision = new Comision_venta();
                $comision->mes = $request->mes;
                $comision->anio = $request->anio;
                $comision->total_pagado = $request->total_pagado;
                $comision->total_comision = $request->total_comision;
                $comision->apoyo_mes = $request->apoyo_mes;
                $comision->asesor_id = $request->asesor_id;
                $comision->total_porPagar = $request->total_porPagar;
                $comision->restanteAnt = $request->restanteAnt;

                $comision->total_bono = $request->total_bono;
                $comision->total_anticipo = $request->total_anticipo;
                $comision->tipo_vendedor = $request->tipo_vendedor;
                    
            $comision->save();

            $comision_id = $comision->id;

            $detalle = $request->dataVentas;//Array de detalles de ventas en el mes
            $individualizadas = $request->dataIndividualizadas;//Array de detalles de ventas en el mes
            $canceladas = $request->dataCanceladas;//Array de detalles de ventas en el mes
            $pendientes = $request->dataPendientes;//Array de detalles de ventas en el mes
            $cambios = $request->dataCambios;
            //Recorro todos los elementos

            if(sizeof($detalle))
                foreach($detalle as $ep=>$det)
                {
                    $det_comision = new Det_com_venta();
                    $det_comision->contrato_id = $det['id'];
                    $det_comision->porcentaje_comision = $det['porcentaje_comision'];
                    $det_comision->comision_pagar = $det['comision_pagar'];
                    $det_comision->iva = $det['iva'];
                    $det_comision->retencion = $det['retencion'];
                    $det_comision->isr = $det['isr'];
                    $det_comision->este_pago = $det['este_pago'];
                    $det_comision->por_pagar = $det['por_pagar'];
                    $det_comision->comision_id = $comision_id;

                    $det_comision->proyecto = $det['proyecto'];
                    $det_comision->etapa = $det['etapa'];
                    $det_comision->manzana = $det['manzana'];
                    $det_comision->lote = $det['num_lote'];
                    $det_comision->valor_venta = $det['precio_venta'];

                    if($det_comision->este_pago == 0){
                        $det_comision->pendiente = 1;
                    }

                    $contrato = Contrato::findOrFail($det['id']);
                    if($det_comision->por_pagar == 0)
                        $contrato->comision = 2;
                    else
                        $contrato->comision = 1;
                    $contrato->save();

                    $det_comision->save();

                }

            if(sizeof($cambios))
                foreach($cambios as $ep=>$det)
                {
                    $det_comision = new Det_com_venta();
                    $det_comision->contrato_id = $det['id'];
                    $det_comision->porcentaje_comision = $det['porcentaje_comision'];
                    $det_comision->comision_pagar = $det['comision_pagar'];
                    $det_comision->iva = $det['iva'];
                    $det_comision->retencion = $det['retencion'];
                    $det_comision->isr = $det['isr'];
                    $det_comision->este_pago = $det['este_pago'];
                    $det_comision->por_pagar = $det['por_pagar'];
                    $det_comision->comision_id = $comision_id;

                    $det_comision->proyecto = $det['proyecto'];
                    $det_comision->etapa = $det['etapa'];
                    $det_comision->manzana = $det['manzana'];
                    $det_comision->lote = $det['num_lote'];
                    $det_comision->valor_venta = $det['precio_venta'];

                    if($det_comision->este_pago == 0){
                        $det_comision->pendiente = 1;
                    }

                    $anterior = Det_com_venta::findOrFail($det['detalle_id']);
                    $anterior->pendiente = 2;
                    $anterior->save();

                    $cambio = new Det_com_cambio();
                    $cambio->monto = $det['pago_ant'];
                    $cambio->descripcion = 'Reubicacion del Fracc. '.$det['proyecto_ant'].' Etapa '.$det['etapa_ant'].' Mz. '.$det['manzana_ant'].' Lt. '.$det['lote_ant'];
                    $cambio->comision_id = $comision_id;
                    $cambio->save();

                    $contrato = Contrato::findOrFail($det['id']);
                    if($det_comision->por_pagar == 0)
                        $contrato->comision = 2;
                    else
                        $contrato->comision = 1;
                    $contrato->save();

                    $det_comision->save();

                }

            if(sizeof($individualizadas))
                foreach($individualizadas as $ep=>$det)
                {
                    $det_comision = new Det_com_individualizada();
                    $det_comision->detalle_id = $det['detalle_id'];
                    $det_comision->pago = $det['por_pagar'];
                    $det_comision->comision_id = $comision_id;
                    

                    $contrato = Contrato::findOrFail($det['id']);
                    $contrato->comision = 2;
                    $contrato->save();

                    $det_comision->save();

                }

            if(sizeof($canceladas))
                foreach($canceladas as $ep=>$det)
                {
                    $det_comision = new Det_com_cancelada();
                    $det_comision->detalle_id = $det['detalle_id'];
                    $det_comision->monto = $det['este_pago'];
                    $det_comision->bono_cancel = $det['bono'];
                    $det_comision->comision_id = $comision_id;
                    

                    $contrato = Contrato::findOrFail($det['id']);
                    $contrato->comision = 2;
                    $contrato->save();

                    $det_comision->save();

                }

            if(sizeof($pendientes))
                foreach($pendientes as $ep=>$det)
                {
                    $det_comision = new Det_com_pendiente();
                    $det_comision->detalle_id = $det['detalle_id'];
                    $det_comision->monto_pagado = $det['este_pago'];
                    $det_comision->por_pagar = $det['por_pagar'];
                    $det_comision->comision_id = $comision_id;

                    $det_pendiente = Det_com_venta::findOrFail($det['detalle_id']);
                    $det_pendiente->pendiente = 0;
                    $det_pendiente->save();
                    

                    $contrato = Contrato::findOrFail($det['id']);
                    if($det_comision->por_pagar == 0)
                        $contrato->comision = 2;
                    else
                        $contrato->comision = 1;
                    $contrato->save();

                    $det_comision->save();

                }

        // } catch (Exception $e) {
        //     DB::rollBack();
        // }
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
                $venta->isr = 0;
                $venta->retencion = 0;
                $venta->iva = 0;

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
                $acum =$restante[0]->total_pagado * (-1);
            }
        }

        return $acum;
    }

    private function getCanceladas($vendedor, $mes, $anio){

        $cancelaciones = Det_com_venta::join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id',
                                'creditos.fraccionamiento as proyecto',
                                'inst_seleccionadas.tipo_credito',
                                'inst_seleccionadas.institucion',
                                'creditos.etapa','creditos.manzana',
                                'creditos.num_lote',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                'contratos.fecha_status',
                                'det_com_ventas.id as detalle_id',
                                'det_com_ventas.porcentaje_comision',
                                'det_com_ventas.comision_pagar',
                                'det_com_ventas.iva',
                                'det_com_ventas.isr',
                                'det_com_ventas.retencion',
                                'det_com_ventas.este_pago',
                                'det_com_ventas.por_pagar'
                        )
                                            ->where('det_com_ventas.pendiente','=',0)
                                            ->where('contratos.status','=',0)
                                            ->where('creditos.vendedor_id','=',$vendedor)
                                            ->whereMonth('contratos.fecha_status',$mes)
                                            ->whereYear('contratos.fecha_status',$anio)
                                            ->get();

        if(sizeOf($cancelaciones)){
            foreach($cancelaciones as $index => $cancelada){
                $bonos = Bono_venta::select( DB::raw("SUM(monto) as bono") )->where('contrato_id','=',$cancelada->id)->get();
                $cancelada->bono = $bonos[0]->bono;
                if($cancelada->bono == null)
                    $cancelada->bono = 0;

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
                        ->select('contratos.id',
                                'contratos.avance_lote',
                                'creditos.num_lote',
                                'creditos.fraccionamiento as proyecto',
                                'creditos.etapa',
                                'creditos.manzana',
                                'creditos.precio_venta',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                'inst_seleccionadas.tipo_credito',
                                'inst_seleccionadas.institucion',
                                'det_com_ventas.id as detalle_id',
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
                                            ->where('det_com_ventas.pendiente','=',0)
                                            ->where('creditos.vendedor_id','=',$vendedor)
                                            ->whereMonth('expedientes.fecha_liquidacion',$mes)
                                            ->whereYear('expedientes.fecha_liquidacion',$anio)
                                            ->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('contratos.status','=',3)
                                            ->where('det_com_ventas.pendiente','=',0)
                                            ->where('contratos.comision','=',1)
                                            ->where('creditos.vendedor_id','=',$vendedor)
                                            ->whereMonth('expedientes.fecha_firma_esc',$mes)
                                            ->whereYear('expedientes.fecha_firma_esc',$anio)
                                            ->get();

        if(sizeOf($individualizadas)){
            foreach($individualizadas as $index => $indiv)
            $pendiente = Det_com_pendiente::select('monto_pagado','por_pagar')->where('detalle_id','=',$indiv->detalle_id)->get();
            if(sizeof($pendiente)){
                $indiv->este_pago = $pendiente[0]->monto_pago;
                $indiv->por_pagar = $pendiente[0]->por_pagar;
            }
        }
        
        return $individualizadas;
    }

    private function getPendientes($vendedor){
        $pendientes = Det_com_venta::join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('pagos_contratos','contratos.id','=','pagos_contratos.contrato_id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id',
                                    'contratos.avance_lote',
                                    'creditos.num_lote',
                                    'creditos.fraccionamiento as proyecto',
                                    'creditos.etapa',
                                    'creditos.manzana',
                                    'creditos.precio_venta',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                    'inst_seleccionadas.tipo_credito',
                                    'inst_seleccionadas.institucion',
                                    'det_com_ventas.id as detalle_id',
                                    'det_com_ventas.porcentaje_comision',
                                    'det_com_ventas.comision_pagar',
                                    'det_com_ventas.iva',
                                    'det_com_ventas.isr',
                                    'det_com_ventas.retencion',
                                    'det_com_ventas.este_pago',
                                    'det_com_ventas.por_pagar')
                                        ->where('contratos.comision','=',1)
                                        ->where('det_com_ventas.este_pago','=',0)
                                        ->where('det_com_ventas.pendiente','=',1)
                                        ->where('pagos_contratos.num_pago','=',0)
                                        ->where('pagos_contratos.pagado','>',1)
                                        ->where('pagos_contratos.tipo_pagare','=',0)
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

    private function getCambios($vendedor){
        $cambios = Det_com_venta::join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('pagos_contratos','contratos.id','=','pagos_contratos.contrato_id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id',
                                    'contratos.avance_lote',
                                    'contratos.fecha',
                                    'creditos.num_lote',
                                    'creditos.fraccionamiento as proyecto',
                                    'creditos.etapa',
                                    'creditos.manzana',
                                    'creditos.precio_venta',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                    'inst_seleccionadas.tipo_credito',
                                    'inst_seleccionadas.institucion',
                                    'det_com_ventas.id as detalle_id',
                                    'det_com_ventas.porcentaje_comision',
                                    'det_com_ventas.comision_pagar',
                                    'det_com_ventas.iva',
                                    'det_com_ventas.isr',
                                    'det_com_ventas.valor_venta as precio_venta_ant',
                                    'det_com_ventas.proyecto as proyecto_ant',
                                    'det_com_ventas.etapa as etapa_ant',
                                    'det_com_ventas.manzana as manzana_ant',
                                    'det_com_ventas.lote as lote_ant',
                                    'det_com_ventas.retencion',
                                    'det_com_ventas.este_pago as pago_ant')
                        ->where('contratos.comision','=',3)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('creditos.vendedor_id','=',$vendedor)
                        ->get();

        if(sizeOf($cambios)){
            foreach($cambios as $index => $cambio){
                
                $cambio->indiv = 0;

                if($cambio->tipo_credito == 'Crédito Directo'){
                    $expedientes = Expediente::select('liquidado')->where('id','=',$cambio->id)->where('liquidado','=',1)->get();
                    if(sizeof($expedientes)){
                        $cambio->indiv = 1;
                    }
                }
                else{
                    $expedientes = Expediente::select('liquidado')->where('id','=',$cambio->id)->where('fecha_firma_esc','!=',NULL)->get();
                    if(sizeof($expedientes)){
                        $cambio->indiv = 1;
                    }
                }

            }
        }

        return $cambios;

    }

    private function getVentasDetalle($id){
        $ventas = Det_com_venta::join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','creditos.id','=','contratos.id')
                        ->join('lotes','creditos.lote_id','lotes.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                        ->select(
                            'contratos.id as folio',
                            'det_com_ventas.id',
                            'det_com_ventas.porcentaje_comision',
                            'det_com_ventas.comision_pagar',
                            'det_com_ventas.iva',
                            'det_com_ventas.isr',
                            'det_com_ventas.retencion',
                            'det_com_ventas.este_pago',
                            'det_com_ventas.por_pagar',
                            'det_com_ventas.valor_venta as precio_venta','contratos.avance_lote',
                            'det_com_ventas.proyecto',
                            'inst_seleccionadas.tipo_credito',
                            'inst_seleccionadas.institucion',
                            'det_com_ventas.etapa','det_com_ventas.manzana',
                            'det_com_ventas.lote as num_lote',
                            'contratos.porcentaje_exp',
                            'contratos.fecha_exp',
                            'contratos.fecha',
                            'lotes.extra',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'lotes.extra_ext')
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('det_com_ventas.comision_id', '=', $id)
                        ->get();

        return $ventas;
    }

    private function getIndividualizadasDetalle($id){
        $individualizadas = Det_com_individualizada::join('det_com_ventas','det_com_ventas.id','=','det_com_individualizadas.detalle_id')
                        ->join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','creditos.id','=','contratos.id')
                        ->join('lotes','creditos.lote_id','lotes.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                        ->select(
                            'contratos.id as folio',
                            'det_com_ventas.id',
                            'det_com_ventas.porcentaje_comision',
                            'det_com_ventas.comision_pagar',
                            'det_com_ventas.iva',
                            'det_com_ventas.isr',
                            'det_com_ventas.retencion',
                            'det_com_ventas.este_pago',
                            'det_com_ventas.por_pagar',
                            'creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'inst_seleccionadas.tipo_credito',
                            'inst_seleccionadas.institucion',
                            'creditos.etapa','creditos.manzana',
                            'creditos.num_lote',
                            'contratos.porcentaje_exp',
                            'contratos.fecha_exp',
                            'contratos.fecha',
                            'lotes.extra',
                            //'det_com_individualizadas.pago',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'lotes.extra_ext')
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('det_com_individualizadas.comision_id', '=', $id)
                        ->get();

        return $individualizadas;
    }

    private function getCanceladasDetalle($id){
        $canceladas = Det_com_cancelada::join('det_com_ventas','det_com_ventas.id','=','det_com_canceladas.detalle_id')
                        ->join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','creditos.id','=','contratos.id')
                        ->join('lotes','creditos.lote_id','lotes.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                        ->select(
                            'contratos.id as folio',
                            'det_com_ventas.id',
                            'det_com_ventas.porcentaje_comision',
                            'det_com_ventas.comision_pagar',
                            'det_com_ventas.iva',
                            'det_com_ventas.isr',
                            'det_com_ventas.retencion',
                            'creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'inst_seleccionadas.tipo_credito',
                            'inst_seleccionadas.institucion',
                            'creditos.etapa','creditos.manzana',
                            'creditos.num_lote',
                            'contratos.porcentaje_exp',
                            'contratos.fecha_exp',
                            'contratos.fecha',
                            'contratos.fecha_status',
                            'lotes.extra',
                            'det_com_canceladas.monto as este_pago',
                            'det_com_canceladas.bono_cancel as bono',
                            //'det_com_individualizadas.pago',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'lotes.extra_ext')
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('det_com_canceladas.comision_id', '=', $id)
                        ->get();

        return $canceladas;
    }

    private function getPendienteDetalle($id){
        $pendientes = Det_com_pendiente::join('det_com_ventas','det_com_ventas.id','=','det_com_pendientes.detalle_id')
                        ->join('contratos','contratos.id','=','det_com_ventas.contrato_id')
                        ->join('creditos','creditos.id','=','contratos.id')
                        ->join('lotes','creditos.lote_id','lotes.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')

                        ->select(
                            'contratos.id as folio',
                            'det_com_ventas.id',
                            'det_com_ventas.porcentaje_comision',
                            'det_com_ventas.comision_pagar',
                            'det_com_ventas.iva',
                            'det_com_ventas.isr',
                            'det_com_ventas.retencion',
                            'creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'inst_seleccionadas.tipo_credito',
                            'inst_seleccionadas.institucion',
                            'creditos.etapa','creditos.manzana',
                            'creditos.num_lote',
                            'contratos.porcentaje_exp',
                            'contratos.fecha_exp',
                            'contratos.fecha',
                            'lotes.extra',
                            //'det_com_individualizadas.pago',
                            'det_com_pendientes.monto_pagado as este_pago',
                            'det_com_pendientes.por_pagar',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'lotes.extra_ext')
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('det_com_pendientes.comision_id', '=', $id)
                        ->get();

        return $pendientes;
    }

    private function getCambioDetalle($id){
        $cambios = Det_com_cambio::select('monto as pago_ant','descripcion')->where('comision_id','=',$id)->get();

        return $cambios;
    }


}
