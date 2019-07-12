<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposito;
Use App\Contrato;
Use App\Pago_contrato;
use Carbon\Carbon;
use DB;
use NumerosEnLetras;
use App\Gasto_admin;

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
                    ->where('pagos_contratos.pagado','!=',3)
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
                            ->where('pagos_contratos.pagado','!=',3)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where('personal.nombre','like', '%'. $buscar . '%')
                            ->orWhere('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.pagado','!=',3)
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
                            ->where('pagos_contratos.pagado','!=',3)
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
                            ->where('pagos_contratos.pagado','!=',3)
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
                            ->where('pagos_contratos.pagado','!=',3)
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
                            ->where('pagos_contratos.pagado','!=',3)
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
                            ->where('pagos_contratos.pagado','!=',3)
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

        $pago = $request->cant_depo - $request->interes_mor - $request->interes_ord;

        $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
        $pago_contrato->restante =  $pago_contrato->restante - $pago;
        if($pago_contrato->restante == 0)
            $pago_contrato->pagado = 2;
        else{
            $pago_contrato->pagado = 1;
        }

        if($request->interes_ord != 0){
            $gasto = new Gasto_admin();
            $gasto->contrato_id = $pago_contrato->contrato_id;
            $gasto->concepto = 'Interes Ordinario';
            $gasto->costo = $request->interes_ord;
            $gasto->fecha = $request->fecha;
            $gasto->observacion = '';
            $gasto->save();
        }

        $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
        $contrato->saldo = $contrato->saldo + $request->interes_ord - $pago;
        $contrato->save(); 

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
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('depositos.id', 'depositos.pago_id', 'depositos.cant_depo','depositos.interes_mor','depositos.interes_ord',
                                     'depositos.obs_mor','depositos.obs_ord','depositos.num_recibo','depositos.banco','depositos.concepto','depositos.fecha_pago'
                                     ,'creditos.manzana', 'creditos.num_lote','personal.nombre','personal.apellidos','creditos.fraccionamiento')
                                    ->where('depositos.id','=',$id)
                                    ->get();
        $depositos[0]->cantdepLetra = NumerosEnLetras::convertir($depositos[0]->cant_depo,'Pesos',true,'Centavos');
        $fechaDeposito = new Carbon($depositos[0]->fecha_pago);
        $depositos[0]->fecha_pago = $fechaDeposito->formatLocalized('%d días de %B de %Y');
        $pdf = \PDF::loadview('pdf.reciboDePagos',['depositos' => $depositos]);
        return $pdf->stream('recibo_de_pago.pdf');
    }

    public function indexEstadoCuenta(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $criterio = $request->criterio;

        if($buscar == ''){
            $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->get();

        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    if($buscar2 == ''){
                        $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->get();
                    }
                    else{
                        $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->where('c.apellidos','like','%'. $buscar2 . '%')
                            ->get();
                    }
                    break;
                }

                case 'contratos.id':{
                    $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.id','=', $buscar )
                            ->get();

                            break;
                }

                case 'lotes.fraccionamiento_id':{
                    if($buscar != '' && $buscar2 == '' && $b_manzana == '' && $b_lote == ''){

                        $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    'lotes.etapa_id',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where($criterio, '=', $buscar)
                            //->where('lotes.etapa_id', '=', $buscar2)
                            //->where('lotes.manzana', '=', $b_manzana)
                            //->where('lotes.num_lote', '=', $b_lote)
                            ->get();
                    }
                    elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote == ''){

                        $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    'lotes.etapa_id',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $buscar2)
                            //->where('lotes.manzana', '=', $b_manzana)
                            //->where('lotes.num_lote', '=', $b_lote)
                            ->get();
                    }

                    elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote == ''){

                        $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    'lotes.etapa_id',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $buscar2)
                            ->where('lotes.manzana', '=', $b_manzana)
                            //->where('lotes.num_lote', '=', $b_lote)
                            ->get();
                    }

                    elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote != ''){

                        $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    'lotes.etapa_id',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $buscar2)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->get();
                    }

                    elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote != ''){

                        $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    'lotes.etapa_id',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where($criterio, '=', $buscar)
                            //->where('lotes.etapa_id', '=', $buscar2)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->get();
                    }
                    
                    elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote == ''){

                        $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    'lotes.etapa_id',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where($criterio, '=', $buscar)
                            //->where('lotes.etapa_id', '=', $buscar2)
                            ->where('lotes.manzana', '=', $b_manzana)
                            //->where('lotes.num_lote', '=', $b_lote)
                            ->get();
                    }

                    elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote != ''){

                        $prueba = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftJoin('creditos','contratos.id','=','creditos.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            
                            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                    'creditos.manzana','creditos.num_lote',
                                    'creditos.precio_venta',
                                    'expedientes.valor_escrituras', 
                                    'expedientes.descuento', 
                                    'contratos.enganche_total',
                                    'contratos.saldo',
                                    'i.monto_credito as credito_solic',
                                    'i.cobrado',
                                    'i.segundo_credito',
                                    'lotes.etapa_id',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                    
                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id 
                                        GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id AND 
                                        (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                        GROUP BY pagos_contratos.contrato_id) as pagares"),

                                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                        WHERE gastos_admin.contrato_id = contratos.id 
                                        GROUP BY gastos_admin.contrato_id) as gastos")

                                    )
                            ->where('i.elegido', '=', 1)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $buscar2)
                            //->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->get();
                    }

                    break;
                }
            }
            
        
        }
        return ['prueba' => $prueba];
    }
}
