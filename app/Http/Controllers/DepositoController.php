<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposito;
Use App\Contrato;
Use App\Pago_contrato;
use Carbon\Carbon;
use DB;

class DepositoController extends Controller
{
    public function indexPagares(Request $request){
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $vencido = $request->b_vencidos;
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio =  $request->criterio;

        if($vencido == 1){
            if($buscar == ''){
                $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('creditos','creditos.id','=','contratos.id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                            'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                            'personal.nombre','personal.apellidos',
                            DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                    ->where('pagos_contratos.pagado','!=',2)
                    ->where('pagos_contratos.fecha_pago','<',$hoy)
                    ->orderBy('pagos_contratos.pagado', 'asc')
                    ->orderBy('pagos_contratos.fecha_pago', 'asc')
                    ->orderBy('pagos_contratos.pagado', 'asc')
                    ->paginate(10);
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where('personal.nombre','like', '%'. $buscar . '%')
                            ->orWhere('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where('personal.apellidos','like', '%'. $buscar . '%')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->whereBetween($criterio, [$buscar,$buscar2])
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                        if($buscar2=='' && $buscar3==''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where($criterio,'=',$buscar)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 == ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 != ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->where('creditos.manzana','=',$buscar3)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        break;
                    }
                    default:{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where($criterio,'=',$buscar)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                }
            }
            
        }
        else{
            if($buscar==''){
                $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('creditos','creditos.id','=','contratos.id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                            'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                            'personal.nombre','personal.apellidos',
                            DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                    ->paginate(10);
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where('personal.nombre','like', '%'. $buscar . '%')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->whereBetween($criterio, [$buscar,$buscar2])
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                        if($buscar2=='' && $buscar3==''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where($criterio,'=',$buscar)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 == ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 != ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->where('creditos.manzana','=',$buscar3)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        break;
                    }
                    default:{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'))
                            ->where($criterio,'=',$buscar)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                }
            }
        }
        
        
        return[
            'pagination' => [
            'total'         => $pagares->total(),
            'current_page'  => $pagares->currentPage(),
            'per_page'      => $pagares->perPage(),
            'last_page'     => $pagares->lastPage(),
            'from'          => $pagares->firstItem(),
            'to'            => $pagares->lastItem(),
        ],
            'pagares' => $pagares, $hoy
        ];
    }

    public function indexDepositos(Request $request){
        $depositos = Deposito::select('id', 'pago_id', 'cant_depo','interes_mor','interes_ord',
                                'obs_mor','obs_ord','num_recibo','banco','concepto','fecha_pago')
                            ->where('pago_id','=',$request->buscar)
                            ->get();
        
        $pagares = Pago_contrato::select('restante')
        ->where('id','=',$request->buscar)->get();
            return ['depositos' => $depositos, 'restante' => $pagares[0]->restante];
    }

    public function store(Request $request){
        if(!$request->ajax())return redirect('/');
        $deposito = new Deposito();
        $deposito->pago_id = $request->pago_id;
        $deposito->cant_depo = $request->cant_depo;
        $deposito->interes_mor = $request->interes_mor;
        $deposito->interes_ord = $request->interes_ord;
        $deposito->obs_mor = $request->obs_mor;
        $deposito->obs_ord = $request->obs_ord;
        $deposito->num_recibo = $request->num_recibo;
        $deposito->banco = $request->banco;
        $deposito->concepto = $request->concepto;
        $deposito->fecha_pago = $request->fecha_pago;

        $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
        $pago_contrato->restante = $request->restante;
        if($pago_contrato->restante == 0)
            $pago_contrato->pagado = 2;
        else{
            $pago_contrato->pagado = 1;
        }
        $pago_contrato->save();

        $deposito->save();
    }

    public function update(Request $request){
        if(!$request->ajax())return redirect('/');
        $deposito = Deposito::findOrFail($request->id);

        if($deposito->cant_depo != $request->cant_depo){
            $diferencia = $deposito->cant_depo - $request->cant_depo;
        }

        $deposito->cant_depo = $request->cant_depo;
        $deposito->interes_mor = $request->interes_mor;
        $deposito->interes_ord = $request->interes_ord;
        $deposito->obs_mor = $request->obs_mor;
        $deposito->obs_ord = $request->obs_ord;
        $deposito->num_recibo = $request->num_recibo;
        $deposito->banco = $request->banco;
        $deposito->concepto = $request->concepto;
        $deposito->fecha_pago = $request->fecha_pago;

        $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
        $pago_contrato->restante = $pago_contrato->restante + $diferencia;
        if($pago_contrato->restante == 0)
            $pago_contrato->pagado = 2;
        else{
            $pago_contrato->pagado = 1;
        }
        $pago_contrato->save();

        $deposito->save();
    }

    public function reciboPDF($id){
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                            ->join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->select('depositos.id', 'depositos.pago_id', 'depositos.cant_depo','depositos.interes_mor','depositos.interes_ord',
                                     'depositos.obs_mor','depositos.obs_ord','depositos.num_recibo','depositos.banco','depositos.concepto','depositos.fecha_pago')
                                    ->where('depositos.id','=',$id)
                                    ->get();

        $pdf = \PDF::loadview('pdf.reciboDePagos',['depositos' => $depositos]);
        return $pdf->stream('recibo_de_pago.pdf');
    }
}
