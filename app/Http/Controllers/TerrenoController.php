<?php

namespace App\Http\Controllers;
use App\Contrato;
use App\Pago_contrato;
use App\Pagos_lotes;
use Carbon\Carbon;
use DB;

use Illuminate\Http\Request;

class TerrenoController extends Controller
{
    public function getTerrenosAdeudo(Request $request){
        $contrato = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('personal as cliente','creditos.prospecto_id','=','cliente.id')
                ->join('cotizacion_lotes','contratos.id','=','cotizacion_lotes.num_contrato')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->leftJoin('expedientes','contratos.id','=','expedientes.id')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->select(
                    'contratos.id as folio',
                    'cliente.nombre', 'cliente.apellidos',
                    'contratos.saldo',
                    'modelos.nombre as modelo',
                    'etapas.num_etapa as etapa',
                    'fraccionamientos.nombre as proyecto',
                    'lotes.num_lote',
                    'lotes.manzana',
                    'creditos.precio_venta',
                    'contratos.status',
                    'cotizacion_lotes.id as cotizacionId',
                    'expedientes.liquidado'
                )
                ->where('modelos.nombre','=','Terreno')
                ->where('contratos.saldo','>',0)
                ->where('contratos.status','=',3);

                if($request->proyecto != '')
                    $contrato = $contrato->where('lotes.fraccionamiento_id','=',$request->proyecto);
                if($request->etapa != '')
                    $contrato = $contrato->where('lotes.etapa_id','=',$request->etapa);
                if($request->manzana != '')
                    $contrato = $contrato->where('lotes.manzana','like','%'.$request->manzana.'%');
                if($request->lote != '')
                    $contrato = $contrato->where('lotes.num_lote','=',$request->lote);
                if($request->cliente != '')
                    $contrato = $contrato->where(DB::raw("CONCAT(cliente.nombre,' ',cliente.apellidos)"), 'like', '%'. $request->cliente . '%');

                $contrato = $contrato->orderBy('contratos.fecha','desc')->paginate(30);

                foreach ($contrato as $key => $c) {
                    $pagos = Pagos_lotes::select(DB::raw("SUM(interes_monto) as interes"))
                    ->where('pagos_lotes.cotizacion_lotes_id','=',$c->cotizacionId)
                    ->first();
    
                    $c->interes = round($pagos->interes,2);
                }

            return $contrato;
    }


    public function getPagosPendientes(Request $request){
        $current = Carbon::today()->format('ymd');
        $pagos = Pago_contrato::join('pagos_lotes','pagos_contratos.id','=','pagos_lotes.pagare_id')
                ->select(
                    'pagos_contratos.id','pagos_contratos.monto_pago','pagos_lotes.folio',
                    'pagos_contratos.fecha_pago', 'pagos_contratos.monto_pago', 'pagos_contratos.pagado',
                    'pagos_contratos.restante', 
                    'pagos_lotes.interes_monto', 'pagos_lotes.cotizacion_lotes_id',
                    DB::raw('(CASE WHEN pagos_contratos.fecha_pago < ' . $current . ' THEN 1 ELSE 0 END) AS atrasado')
                )
                ->where('pagos_contratos.contrato_id','=',$request->id)
                ->where('pagado','<',2)
                ->orderBy('pagos_contratos.fecha_pago','asc')
                ->get();
        
        $now = Carbon::today();

        foreach ($pagos as $key => $pago) {
            $pago->diferencia = 0;
            $pago->interes_mor = 0;
            if($pago->atrasado == 1){
                $fecha_pago = Carbon::parse($pago->fecha_pago);
                $pago->diferencia = $fecha_pago->diffInDays($now);

                $restante = $pago->restante - $pago->interes_monto;

                //Calcular interes moratorio.
                $interes_mor = (($restante * .05)/30) * $pago->diferencia;
                $pago->interes_mor = round($interes_mor,2);
            }
            $pago->pago_total = $pago->restante + $pago->interes_mor;
        }

        return $pagos;
    }

}
