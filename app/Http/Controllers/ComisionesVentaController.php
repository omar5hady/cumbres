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

class ComisionesVentaController extends Controller
{

    public function getComision(Request $request){
        
    }

    private function getVentas($vendedor, $mes, $anio){

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','lotes.id')
                    ->join('pagos_contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('depositos','pagos_contratos.id','=','depositos.pago_id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp',
                            'contratos.fecha_exp','contratos.fecha','lotes.extra','lotes.extra_ext')
                    ->where('contratos.status','=',3)
                    ->where('contratos.comision','=',0)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('depositos.fecha_pago',$request->mes)
                    ->whereYear('depositos.fecha_pago',$request->anio)
                    ->where('pagos_contratos.num_pago','=',0)
                    ->where('pagos_contratos.pagado','=',2)
                    ->groupBy('contratos.id')
                    ->orderBy('contratos.id','asc')
                    ->get();

        if(sizeOf($ventas)){
            foreach($ventas as $index => $venta){
                // $expediente = Expediente::select('id')->where('id','=',$venta->id)
            }
        }

        return $ventas;
    }
    public function ventasAsesor(Request $request){
        $fechaIni = $request->anio.'-'.$request->mes.'-01';
        $fechaFin = $request->anio.'-'.$request->mes.'-15';

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','lotes.id')
                    ->join('pagos_contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('depositos','pagos_contratos.id','=','depositos.pago_id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp',
                            'contratos.fecha_exp','contratos.fecha','lotes.extra','lotes.extra_ext')
                    ->where('contratos.status','=',3)
                    ->where('contratos.comision','=',0)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('depositos.fecha_pago',$request->mes)
                    ->whereYear('depositos.fecha_pago',$request->anio)
                    ->where('pagos_contratos.num_pago','=',0)
                    ->where('pagos_contratos.pagado','=',2)
                    ->groupBy('contratos.id')
                    ->orderBy('contratos.id','asc')
                    ->get();

        $ventasMes = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','lotes.id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp',
                            'contratos.fecha_exp','contratos.fecha','lotes.extra','lotes.extra_ext')
                    ->where('contratos.status','=',3)
                    ->where('contratos.comision','=',0)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('contratos.fecha',$request->mes)
                    ->whereYear('contratos.fecha',$request->anio)
                    ->orderBy('contratos.id','asc')
                    ->get();

        // $ventasQuincena = Contrato::join('creditos','contratos.id','=','creditos.id')
        //             ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
        //                     'creditos.fraccionamiento as proyecto',
        //                     'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp','contratos.fecha_exp')
        //             ->where('contratos.status','=',3)
        //             ->where('vendedor_id','=',$request->vendedor)
        //             ->whereBetween('contratos.fecha', [$fechaIni, $fechaFin])
        //             ->count();

        $numVentas = $ventasMes->count();

        if($request->mes == '01'){
            $mesAnt = 12;
            $anioAnt = $request->anio - 1;
        }
        else{
            $mesAnt = $request->mes - 1;
            $anioAnt = $request->anio;
        }

        $restante = Comision::where('asesor_id','=',$request->vendedor)->where('mes','=',$mesAnt)->where('anio','=',$anioAnt)->get();
        $acum = 0;
        if(sizeof($restante)){
            $acum = $restante[0]->acumulado;
        }

        $ventaPasada = Contrato::join('creditos','contratos.id','=','creditos.id')
            ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp','contratos.fecha_exp')
            ->where('contratos.status','=',3)
            ->where('vendedor_id','=',$request->vendedor)
            ->whereMonth('contratos.fecha',$mesAnt)
            ->whereYear('contratos.fecha',$anioAnt)
            ->count();

        $vendedor = Vendedor::findOrFail($request->vendedor);
        $tipo = $vendedor->tipo;
        $esquema = $vendedor->esquema;

        $sueldo = !Carbon::parse($vendedor->fecha_sueldo)->gt(Carbon::now());

        $anticiposCancelados = Contrato::join('creditos','contratos.id','=','creditos.id')
            ->join('det_comisiones','contratos.id','=','det_comisiones.id')
            ->join('comisiones','det_comisiones.idComision','comisiones.id')
            ->join('vendedores','comisiones.asesor_id', '=','vendedores.id')
            ->join('personal','vendedores.id','=','personal.id')
            ->select('contratos.id as folio','creditos.fraccionamiento','creditos.etapa',
                    'creditos.manzana','creditos.num_lote','det_comisiones.bono','det_comisiones.fecha_bono',
                    'det_comisiones.anticipo','det_comisiones.fecha_anticipo','contratos.fecha_status'
                    )
            ->where('contratos.comision','=','1')
            ->where('contratos.fecha_exp','!=',NULL)
            ->where('det_comisiones.anticipo','!=',NULL)
            ->where('contratos.status','=',0)
            ->where('vendedor_id','=',$request->vendedor)
            ->whereMonth('contratos.fecha_status',$request->mes)
            ->whereYear('contratos.fecha_status',$request->anio)
            ->orderBy('contratos.id','asc')
        ->get();

        $numAnticipos = $anticiposCancelados->count();

        $totalAnticipo = 0;
        $totalBono = 0;

        if(sizeof($anticiposCancelados)){
            foreach($anticiposCancelados as $index => $anticipo){
                $totalAnticipo += $anticipo->anticipo;
                if($anticipo->fecha_bono != NULL){
                    $totalBono += $anticipo->bono;
                }
            }
        }

        $cancelaciones = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('det_comisiones','contratos.id','det_comisiones.id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote')
                    ->where('contratos.status','=',0)
                    ->where('contratos.comision','=',1)
                    ->where('det_comisiones.anticipo','!=', 0)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('contratos.fecha',$mesAnt)
                    ->whereYear('contratos.fecha',$anioAnt)
                    ->get();


        $individualizadas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('expedientes','contratos.id','=','expedientes.id')
                    ->join('det_comisiones','contratos.id','=','det_comisiones.id')
                    ->join('comisiones','det_comisiones.idComision','comisiones.id')
                    ->join('vendedores','comisiones.asesor_id', '=','vendedores.id')
                    ->join('personal','vendedores.id','=','personal.id')
                    ->select('contratos.id as folio','creditos.fraccionamiento','creditos.etapa',
                            'creditos.manzana','creditos.num_lote',
                            'det_comisiones.anticipo','det_comisiones.fecha_anticipo','contratos.fecha_status',
                            'expedientes.fecha_firma_esc'                            
                            )
                    ->where('contratos.comision','=','1')
                    ->where('contratos.fecha_exp','!=',NULL)
                    ->where('det_comisiones.anticipo','!=',NULL)
                    ->where('contratos.status','=',3)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('expedientes.fecha_firma_esc',$request->mes)
                    ->whereYear('expedientes.fecha_firma_esc',$request->anio)
                    ->orderBy('contratos.id','asc')
                ->get();
        
        $totalIndividualizadas = 0;

        $numIndivi = $individualizadas->count();
        
        if(sizeof($individualizadas)){
            foreach($individualizadas as $index => $indiv){
                $totalIndividualizadas += $indiv->anticipo;
            }
        }

        return['ventas'=>$ventas, 
                'anticiposCan'=>$anticiposCancelados,
                'cancelaciones'=>$cancelaciones, 
                'individualizadas'=>$individualizadas,
                'numVentas' => $numVentas,
                'numIndivi' => $numIndivi,
                'numAnticipos' => $numAnticipos,
                'pasada' => $ventaPasada,
                'quincena' => $ventasQuincena,
                'tipo'=>$tipo,
                'totalAnticipo' => $totalAnticipo,
                'totalIndividualizadas' => $totalIndividualizadas,
                'totalBono' => $totalBono,
                'esquema'=>$esquema,
                'restante'=>$acum,
                'sueldo'=>$sueldo
            ];
    }
}
