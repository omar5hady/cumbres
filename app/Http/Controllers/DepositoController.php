<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposito;
use App\Contrato;
use App\Credito;
use App\Pago_contrato;
use Carbon\Carbon;
use DB;
use NumerosEnLetras;
use App\Gasto_admin;
use App\Dep_credito;
use Excel;
use Auth;
use File;
use App\Notifications\NotifyAdmin;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationReceived;
use App\Personal;
use App\inst_seleccionada;

class DepositoController extends Controller
{
    public function indexPagares(Request $request){
        if(!$request->ajax())return redirect('/');
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $vencido = $request->b_vencidos;
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio =  $request->criterio;

        $query = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
            ->join('creditos','creditos.id','=','contratos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                    'modelos.nombre as modelo',
                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                    'contratos.ext_empresa','contratos.colonia_empresa',
                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
        DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"));

        if($vencido == 1){ //VENCIDOS
            if($buscar == ''){
                $pagares = $query;
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = $query
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = $query
                            ->whereBetween($criterio, [$buscar,$buscar2]);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                        
                        $pagares = $query;
                            if($buscar!='')
                                $pagares = $pagares->where($criterio,'=',$buscar);
                            if($buscar2!='')
                                $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                            if($buscar3!='')
                                $pagares = $pagares->where('creditos.manzana','=',$buscar3);
                        break;
                    }
                    default:{
                        $pagares = $query
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }

            $pagares = $pagares->where('pagos_contratos.pagado','!=',2)
                ->where('pagos_contratos.pagado','!=',3)
                ->where('contratos.status','!=',0)
                ->where('contratos.status','!=',2) 
                ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%')
                ->where('pagos_contratos.fecha_pago','<',$hoy);
            
        } 
        elseif($vencido == 2){ //CANCELADOS
            if($buscar == ''){
                $pagares = $query
                    //
                
                    ->where('contratos.status','=',0)
                    ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%')
                    ->orWhere('contratos.status','=',2)
                    ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%');
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = $query
                            //
                        
                            ->where('contratos.status','=',0)
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                            ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%')

                            ->orWhere('contratos.status','=',2)
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                            ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%');
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = $query
                            
                            ->where('contratos.status','=',0)
                            ->whereBetween($criterio, [$buscar,$buscar2])
                            ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%')

                            ->orWhere('contratos.status','=',2)
                            ->whereBetween($criterio, [$buscar,$buscar2])
                            ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%');
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                    
                        $pagares = $query;
                        
                        $pagares = $pagares->where('contratos.status','=',0)
                            ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%');

                            if($buscar!='')
                                $pagares = $pagares->where($criterio,'=',$buscar);
                            if($buscar2 !='')
                                $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                            if($buscar3 != '')
                                $pagares = $pagares->where('creditos.manzana','=',$buscar3);

                            
                        $pagares = $pagares->orWhere('contratos.status','=',2)
                                ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%');
                            
                            if($buscar!='')
                                $pagares = $pagares->where($criterio,'=',$buscar);
                            if($buscar2 !='')
                                $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                            if($buscar3 != '')
                                $pagares = $pagares->where('creditos.manzana','=',$buscar3);
                        break;
                    }
                    default:{
                        $pagares = $query
                            
                            ->where('contratos.status','=',0)
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%')
                            ->orWhere('contratos.status','=',2)
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%');
                        break;
                    }
                }
            }
            
        }
        else{ //TODOS
            if($buscar==''){
                $pagares = $query;
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = $query
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = $query
                            ->whereBetween($criterio, [$buscar,$buscar2]);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                        $pagares = $query;
                        if($buscar != '')
                            $pagares = $pagares->where($criterio,'=',$buscar);
                        if($buscar2 != '')
                            $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                        if($buscar3 != '')
                            $pagares = $pagares->where('creditos.manzana','=',$buscar3);
                        
                        break;
                    }
                    default:{
                        $pagares = $query
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }

            $pagares = $pagares->where('contratos.status','!=',0)
                                ->where('contratos.status','!=',2)
                                ->where('lotes.emp_constructora','like','%'.$request->b_empresa.'%');
        }

        $pagares = $pagares->orderBy('pagos_contratos.pagado', 'asc')
                        ->orderBy('pagos_contratos.fecha_pago', 'asc')
                        ->orderBy('pagos_contratos.pagado', 'asc')
                        ->paginate(10);

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

    public function excelPagares(Request $request){
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $vencido = $request->b_vencidos;
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio =  $request->criterio;
        
        $query = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
            ->join('creditos','creditos.id','=','contratos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                    'contratos.ext_empresa','contratos.colonia_empresa',
                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"));

        if($vencido == 1){ //VENCIDOS
            if($buscar == ''){
                $pagares = $query;
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = $query
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = $query
                            ->whereBetween($criterio, [$buscar,$buscar2]);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                        
                        $pagares = $query;
                            if($buscar!='')
                                $pagares = $pagares->where($criterio,'=',$buscar);
                            if($buscar2!='')
                                $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                            if($buscar3!='')
                                $pagares = $pagares->where('creditos.manzana','=',$buscar3);
                        break;
                    }
                    default:{
                        $pagares = $query
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }

            $pagares = $pagares->where('pagos_contratos.pagado','!=',2)
                ->where('pagos_contratos.pagado','!=',3)
                ->where('contratos.status','!=',0)
                ->where('contratos.status','!=',2) 
                ->where('pagos_contratos.fecha_pago','<',$hoy);
            
        } 
        elseif($vencido == 2){ //CANCELADOS
            if($buscar == ''){
                $pagares = $query
                    //
                
                    ->where('contratos.status','=',0)
                    ->orWhere('contratos.status','=',2);
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = $query
                            //
                        
                            ->where('contratos.status','=',0)
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')

                            ->orWhere('contratos.status','=',2)
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = $query
                            
                            ->where('contratos.status','=',0)
                            ->whereBetween($criterio, [$buscar,$buscar2])

                            ->orWhere('contratos.status','=',2)
                            ->whereBetween($criterio, [$buscar,$buscar2]);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                    
                        $pagares = $query;
                        
                        $pagares = $pagares->where('contratos.status','=',0);

                            if($buscar!='')
                                $pagares = $pagares->where($criterio,'=',$buscar);
                            if($buscar2 !='')
                                $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                            if($buscar3 != '')
                                $pagares = $pagares->where('creditos.manzana','=',$buscar3);

                            
                        $pagares = $pagares->orWhere('contratos.status','=',2);
                            
                            if($buscar!='')
                                $pagares = $pagares->where($criterio,'=',$buscar);
                            if($buscar2 !='')
                                $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                            if($buscar3 != '')
                                $pagares = $pagares->where('creditos.manzana','=',$buscar3);
                        break;
                    }
                    default:{
                        $pagares = $query
                            
                            ->where('contratos.status','=',0)
                            ->where($criterio,'=',$buscar)
                            ->orWhere('contratos.status','=',2)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }
            
        }
        else{ //TODOS
            if($buscar==''){
                $pagares = $query;
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = $query
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = $query
                            ->whereBetween($criterio, [$buscar,$buscar2]);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                        $pagares = $query;
                        if($buscar != '')
                            $pagares = $pagares->where($criterio,'=',$buscar);
                        if($buscar2 != '')
                            $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                        if($buscar3 != '')
                            $pagares = $pagares->where('creditos.manzana','=',$buscar3);
                        
                        break;
                    }
                    default:{
                        $pagares = $query
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }

            $pagares = $pagares->where('contratos.status','!=',0)
                                ->where('contratos.status','!=',2);
        }

        if($request->b_empresa != ''){
            $pagares= $pagares->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $pagares = $pagares->orderBy('pagos_contratos.pagado', 'asc')
                        ->orderBy('pagos_contratos.fecha_pago', 'asc')
                        ->orderBy('pagos_contratos.pagado', 'asc')
                        ->get();
        
        return Excel::create('Pagares', function($excel) use ($pagares){
            $excel->sheet('pagares', function($sheet) use ($pagares){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente','Gestor','Proyecto', 'Etapa', 'Manzana',
                    '# Lote','# Pagare', 'Saldo', 'Total Depositado', 'Fecha limite', 'Status'
                ]);

                $sheet->setColumnFormat(array(
                    'I' => '$#,##0.00',
                    'J' => '$#,##0.00',
                ));


                $sheet->cells('A1:L1', function ($cells) {
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

                foreach($pagares as $index => $pagare) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($pagare->fecha_pago);
                    $pagare->fecha_pago = $fecha1->formatLocalized('%d de %B de %Y');
                    $pagare->num_pago = $pagare->num_pago + 1;
                    $pagado = $pagare->monto_pago - $pagare->restante;

                    if($pagare->diferencia > 0 && $pagare->pagado < 2) $status = 'Vencido';
                    if($pagare->diferencia < 0 && $pagare->pagado < 2) $status = 'Pendiente';
                    if($pagare->pagado == 2) $status = 'Pagado';
                    if($pagare->pagado == 3) $status = 'Liquidado';

                    $sheet->row($index+2, [
                        $pagare->folio, 
                        $pagare->nombre. ' ' . $pagare->apellidos,
                        $pagare->gestor,
                        $pagare->fraccionamiento,
                        $pagare->etapa,
                        $pagare->manzana,
                        $pagare->num_lote,
                        $pagare->num_pago,
                        $pagare->restante,
                        $pagado,
                        $pagare->fecha_pago,
                        $status

                    ]);	
                }
                $num='A1:J' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

    public function indexDepositos(Request $request){
        if(!$request->ajax())return redirect('/');
        $depositos = Deposito::select('id', 'pago_id', 'cant_depo','interes_mor','interes_ord',
                                'obs_mor','obs_ord','num_recibo','banco','concepto','fecha_pago',
                                'interes_pago', 'pago_capital','desc_interes'
                                )
                            ->where('pago_id','=',$request->buscar)
                            ->get();
        
        $pagares = Pago_contrato::select('restante')
        ->where('id','=',$request->buscar)->get();
            return ['depositos' => $depositos, 'restante' => $pagares[0]->restante];
    }

    public function historialDepositos(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $banco = $request->banco;
        $monto = $request->monto;
        $nombre = $request->nombre;

        $query = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
            ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
            ->join('creditos','creditos.id','=','contratos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select('contratos.id',
                    'depositos.id as depId',
                    'pagos_contratos.fecha_pago', 'creditos.fraccionamiento',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote',
                    'personal.nombre','personal.apellidos','depositos.cant_depo','depositos.num_recibo',
        'depositos.banco','depositos.concepto','depositos.fecha_pago');

        if($request->b_empresa != ''){
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $depositos = $query;

        if($fecha1 != '' && $fecha2 != '')
            $depositos = $depositos->whereBetween('depositos.fecha_pago', [$fecha1, $fecha2]);
        if($banco != '')
            $depositos = $depositos->where('depositos.banco','=',$banco);
        if($monto != '')
            $depositos = $depositos->where('depositos.cant_depo','=',$monto);

        if($nombre != '')
            $depositos = $depositos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $nombre . '%');

        $depositos = $depositos->orderBy('depositos.fecha_pago','desc')->paginate(8);

        
        return[
            'pagination' => [
                'total'         => $depositos->total(),
                'current_page'  => $depositos->currentPage(),
                'per_page'      => $depositos->perPage(),
                'last_page'     => $depositos->lastPage(),
                'from'          => $depositos->firstItem(),
                'to'            => $depositos->lastItem(),
            ],
                'depositos' => $depositos
        ];

    }

    public function excelHistorialDepositos(Request $request){
        
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $banco = $request->banco;
        $monto = $request->monto;

        $query = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
            ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
            ->join('creditos','creditos.id','=','contratos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select('contratos.id', 
                    'pagos_contratos.fecha_pago', 'creditos.fraccionamiento',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote',
                    'personal.nombre','personal.apellidos','depositos.cant_depo','depositos.num_recibo',
        'depositos.banco','depositos.concepto','depositos.fecha_pago');
        
        if($request->b_empresa != ''){
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $depositos = $query;

        if($fecha1 != '' && $fecha2 != '')
            $depositos = $depositos->whereBetween('depositos.fecha_pago', [$fecha1, $fecha2]);
        if($banco != '')
            $depositos = $depositos->where('depositos.banco','=',$banco);
        if($monto != '')
            $depositos = $depositos->where('depositos.cant_depo','=',$monto);

        $depositos = $depositos->orderBy('depositos.fecha_pago','desc')->get();

        return Excel::create('Depositos', function($excel) use ($depositos){
            $excel->sheet('Depositos', function($sheet) use ($depositos){
                
                $sheet->row(1, [
                    '# Contrato', 'Cliente', 'Proyecto', 'Etapa', 'Manzana',
                    '# Lote','# Fecha de deposito', 'Cuenta', '# Recibo', 'Monto'
                ]);

                $sheet->setColumnFormat(array(
                    'J' => '$#,##0.00',
                ));

                $sheet->cells('A1:J1', function ($cells) {
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
                foreach($depositos as $index => $deposito) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($deposito->fecha_pago);
                    $deposito->fecha_pago = $fecha1->formatLocalized('%d de %B de %Y');

                    $cliente = $deposito->nombre.' '.$deposito->apellidos;
                    $sheet->row($index+2, [
                        $deposito->id, 
                        $cliente,
                        $deposito->fraccionamiento,
                        $deposito->etapa,
                        $deposito->manzana,
                        $deposito->num_lote,
                        $deposito->fecha_pago,
                        $deposito->banco,
                        $deposito->num_recibo,
                        $deposito->cant_depo

                    ]);	
                }
                $num='A1:J' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');

    }

    public function excelDepositos(Request $request){

        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
            ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->select('pagos_contratos.contrato_id','depositos.cant_depo','depositos.num_recibo',
                    'depositos.banco', 'depositos.fecha_pago', 'depositos.concepto',
                    'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote');


        if($banco != '')
            $depositos = $depositos->where('depositos.banco','=',$request->banco);
        if($desde != '')
            $depositos = $depositos->whereBetween('depositos.fecha_pago', [$request->desde, $request->hasta]);

        $depositos = $depositos->get();

        return Excel::create('Depositos', function($excel) use ($depositos){
                $excel->sheet('Depositos', function($sheet) use ($depositos){
                    
                    $sheet->row(1, [
                        '# Contrato','Proyecto', 'Etapa', 'Manzana',
                        '# Lote','# Fecha de pago', '# Recibo', 'Cuenta', 'Concepto', 'Monto'
                    ]);

                    $sheet->setColumnFormat(array(
                        'J' => '$#,##0.00',
                    ));


                    $sheet->cells('A1:J1', function ($cells) {
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

                    foreach($depositos as $index => $deposito) {
                        $cont++;

                        setlocale(LC_TIME, 'es_MX.utf8');
                        $fecha1 = new Carbon($deposito->fecha_pago);
                        $deposito->fecha_pago = $fecha1->formatLocalized('%d de %B de %Y');
                        
                        $sheet->row($index+2, [
                            $deposito->contrato_id, 
                            $deposito->fraccionamiento,
                            $deposito->etapa,
                            $deposito->manzana,
                            $deposito->num_lote,
                            $deposito->fecha_pago,
                            $deposito->num_recibo,
                            $deposito->banco,
                            $deposito->concepto,
                            $deposito->cant_depo

                        ]);	
                    }
                    $num='A1:J' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
                }
        )->download('xls');
    }

    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();

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

            $deposito->interes_pago = $request->pago_interes;
            $deposito->pago_capital = $request->pago_capital;
            $deposito->desc_interes = $request->descuento;

            $pago = $request->cant_depo - $request->interes_mor - $request->interes_ord;
            $descuento = $request->descuento;

            $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
            $pago_contrato->restante =  $pago_contrato->restante - $pago;
            if($pago_contrato->restante <= 0)
                $pago_contrato->pagado = 2;
            else{
                $pago_contrato->pagado = 1;
            }

            if($request->interes_ord != 0){
                $gasto = new Gasto_admin();
                $gasto->contrato_id = $pago_contrato->contrato_id;
                $gasto->concepto = 'Interes Ordinario';
                $gasto->costo = $request->interes_ord;
                $gasto->fecha = $request->fecha_pago;
                $gasto->observacion = '';
                $gasto->save();
            }

            if($request->interes_mor != 0){
                $gasto = new Gasto_admin();
                $gasto->contrato_id = $pago_contrato->contrato_id;
                $gasto->concepto = 'Interes Moratorio';
                $gasto->costo = $request->interes_mor;
                $gasto->fecha = $request->fecha_pago;
                $gasto->observacion = '';
                $gasto->save();
            }

            $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
            $contrato->saldo = round($contrato->saldo - $pago - $descuento,2);

            $credit = Credito::findOrFail($pago_contrato->contrato_id);

            if($credit->porcentaje_terreno > 0){
                $saldo = $credit->monto_terreno - $credit->saldo_terreno;

                $porcentaje = $credit->porcentaje_terreno/100;
                $monto_terreno = $pago*$porcentaje;
                
                if($deposito->monto_terreno > $saldo)
                    $deposito->monto_terreno = $saldo;
                else
                    $deposito->monto_terreno = $monto_terreno;

            }
            
            $contrato->save(); 

            $pago_contrato->save();

            

            $deposito->save();

            $tCredito = inst_seleccionada::select('tipo_credito')
                ->where([
                    ['credito_id', '=', $contrato->id],
                    ['elegido', '=', 1]
                ])
            ->first();
            
            //alerta cuando la suma de lo depositos alcanza 50mil y esta como apartado
            if($tCredito->tipo_credito == "Apartado"){
                
                $saldo = Pago_contrato::select(
                            DB::raw('SUM(monto_pago) as monto'), 
                            DB::raw('SUM(restante) as restante')
                        )->groupBy('contrato_id')
                        ->where('contrato_id', '=', $contrato->id)
                ->get();
                
                if(($saldo[0]->monto - $saldo[0]->restante) >= 50000){
                    
                    $toAlert1 = [12];
                    $msj1 = "El contrato con folio $contrato->id ha acumulado un monto de $50,000.00 es momento de cambiar el tipo de crédito";
                    foreach($toAlert1 as $index => $id){
                        $senderData = DB::table('users')->select('foto_user', 'usuario')->where('id','=',Auth::user()->id)->get();
                        
                        $dataAr = [
                            'notificacion'=>[
                                'usuario' => $senderData[0]->usuario,
                                'foto' => $senderData[0]->foto_user,
                                'fecha' => Carbon::now(),
                                'msj' => $msj1,
                                'titulo' => 'Cambio de tipo de crédito'
                            ]
                        ];
                        User::findOrFail($id)->notify(new NotifyAdmin($dataAr));
                        

                        $persona = Personal::findOrFail($id);

                        // Mail::to($persona->email)->send(new NotificationReceived($msj1));
                    }
                }
            }

            //alerta cuando se realiza un nuevo deposito
            if($request->banco != "0102030405-Scotiabank"){
                $toAlert = [24706, 24705];
                $msj = 'Se ha realizado un nuevo depósito de pagare';
                foreach($toAlert as $index => $id){
                    $senderData = DB::table('users')->select('foto_user', 'usuario')->where('id','=',Auth::user()->id)->get();

                    $dataAr = [
                        'notificacion'=>[
                            'usuario' => $senderData[0]->usuario,
                            'foto' => $senderData[0]->foto_user,
                            'fecha' => Carbon::now(),
                            'msj' => $msj,
                            'titulo' => 'Nuevo deposito'
                        ]
                    ];
                    User::findOrFail($id)->notify(new NotifyAdmin($dataAr));

                    $persona = Personal::findOrFail($id);

                    // Mail::to($persona->email)->send(new NotificationReceived($msj));
                }
            }

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $deposito = Deposito::findOrFail($request->id);
            $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
            $diferencia = 0;

            if($deposito->cant_depo != $request->cant_depo){
                $diferencia = $deposito->cant_depo - $request->cant_depo;
            }

            $pagoAnt = $deposito->cant_depo - $deposito->interes_mor - $deposito->interes_ord;
            $pago = $request->cant_depo - $request->interes_mor - $request->interes_ord;
            
            if($request->interes_ord != $deposito->interes_ord  && $request->interes_ord>0){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Ordinario')
                            ->where('costo','=',$deposito->interes_ord)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                if(sizeof($b_gasto) != 0){
                    $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                    $gasto->costo = $request->interes_ord;
                    $gasto->save();
                }
                else{
                    $gasto = new Gasto_admin();
                    $gasto->contrato_id = $pago_contrato->contrato_id;
                    $gasto->concepto = 'Interes Ordinario';
                    $gasto->costo = $request->interes_ord;
                    $gasto->fecha = $request->fecha_pago;
                    $gasto->observacion = '';
                    $gasto->save();
                }              
            }
            elseif($request->interes_ord != $deposito->interes_ord  && $request->interes_ord==0){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Ordinario')
                            ->where('costo','=',$deposito->interes_ord)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                
                    $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                    $gasto->delete();
            }

            if($request->interes_mor != $deposito->interes_mor  && $request->interes_mor>0){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Moratorio')
                            ->where('costo','=',$deposito->interes_mor)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                if(sizeof($b_gasto) != 0){
                    $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                    $gasto->costo = $request->interes_mor;
                    $gasto->save();
                }
                else{
                    $gasto = new Gasto_admin();
                    $gasto->contrato_id = $pago_contrato->contrato_id;
                    $gasto->concepto = 'Interes Moratorio';
                    $gasto->costo = $request->interes_mor;
                    $gasto->fecha = $request->fecha_pago;
                    $gasto->observacion = '';
                    $gasto->save();
                }              
            }
            elseif($request->interes_mor != $deposito->interes_mor  && $request->interes_mor==0){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Moratorio')
                            ->where('costo','=',$deposito->interes_mor)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                
                    $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                    $gasto->delete();
            }

            $deposito->cant_depo = $request->cant_depo;
            $deposito->interes_mor = $request->interes_mor;
            $interesAnt = $deposito->interes_ord;
            $deposito->interes_ord = $request->interes_ord;
            $deposito->obs_mor = $request->obs_mor;
            $deposito->obs_ord = $request->obs_ord;
            $deposito->num_recibo = $request->num_recibo;
            $deposito->banco = $request->banco;
            $deposito->concepto = $request->concepto;
            $deposito->fecha_pago = $request->fecha_pago;

            $descAnt = $deposito->desc_interes;
            $descuento = $request->descuento;

            $deposito->interes_pago = $request->pago_interes;
            $deposito->pago_capital = $request->pago_capital;
            $deposito->desc_interes = $request->descuento;
           
            $pago_contrato->restante = $pago_contrato->restante + $diferencia;
            if($pago_contrato->restante == 0)
                $pago_contrato->pagado = 2;
            else{
                $pago_contrato->pagado = 1;
            }

            $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
            $contrato->saldo = round($contrato->saldo + $pagoAnt + $descAnt - $descuento - $pago,2);
            $contrato->save(); 

            $credit = Credito::findOrFail($pago_contrato->contrato_id);

            if($credit->porcentaje_terreno > 0){
                $saldo = $credit->monto_terreno - $credit->saldo_terreno;

                $porcentaje = $credit->porcentaje_terreno/100;
                $monto_terreno = $pago*$porcentaje;
                
                if($deposito->monto_terreno > $saldo)
                    $deposito->monto_terreno = $saldo;
                else
                    $deposito->monto_terreno = $monto_terreno;
            }

            $pago_contrato->save();

            $deposito->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function delete(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try{
            DB::beginTransaction();
            $deposito = Deposito::findOrFail($request->id);

            $pagoAnt = $deposito->cant_depo - $deposito->interes_mor - $deposito->interes_ord;

            $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
            $pago = $deposito->cant_depo - $deposito->interes_mor - $deposito->interes_ord;
            $pago_contrato->restante = $pago_contrato->restante + $pago;

            $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
            $contrato->saldo = round($contrato->saldo + $pagoAnt, 2);

            if($deposito->interes_ord != 0){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Ordinario')
                            ->where('costo','=',$deposito->interes_ord)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();

                $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                $gasto->delete();
            }

            $contrato->save(); 

            $pago_contrato->save();

            $deposito->delete();
            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
        }     
    
    }

    public function reciboPDF($id){
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                            ->join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('depositos.id', 'depositos.pago_id', 'depositos.cant_depo','depositos.interes_mor','depositos.interes_ord',
                                    'lotes.emp_constructora','lotes.emp_terreno',
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
        //if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $criterio = $request->criterio;
        $b_status = $request->b_status;
        $credito = $request->credito;
        $b_direccion = $request->b_direccion;

        $query = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
            ->leftJoin('creditos','contratos.id','=','creditos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            
            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                        'licencias.avance',
                        'creditos.manzana','creditos.num_lote',
                        'creditos.precio_venta',
                        'expedientes.valor_escrituras', 
                        'expedientes.descuento', 'expedientes.liquidado',
                        'expedientes.fecha_firma_esc',
                        'contratos.enganche_total',
                        'contratos.saldo',
                        'i.monto_credito as credito_solic',
                        'i.tipo_credito',
                        'i.cobrado',
                        'i.segundo_credito',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'c.f_nacimiento','c.rfc',
                        'c.homoclave','c.direccion','c.colonia','c.cp', 'c.clv_lada',
                        'c.telefono','c.email','creditos.num_dep_economicos',
                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                        'contratos.ext_empresa','contratos.colonia_empresa',
                        'lotes.calle','lotes.numero',

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

            );

        $query = $query->where('i.elegido', '=', 1);

        if($b_status != '')
            $query = $query
                ->where('contratos.status','=',$b_status);

        if($b_direccion != '')
            $query = $query->where(DB::raw("CONCAT(lotes.calle,' ',lotes.numero)"), 'like', '%'. $b_direccion . '%');
                


        switch($criterio){
            case 'c.nombre':{
                $contratos = $query;
                if($buscar != '')
                    $contratos = $contratos->where('c.nombre','like','%'. $buscar . '%');
                if($buscar2 != '')
                    $contratos = $contratos->where('c.apellidos','like','%'. $buscar2 . '%');
                break;
            }

            case 'contratos.id':{
                $contratos = $query;
                if($buscar != '')
                    $contratos = $contratos->where('contratos.id','=', $buscar );
                break;
            }

            case 'contratos.fecha':{
                if($buscar2 == '' && $buscar != ''){
                    $contratos = $query
                        ->where('contratos.fecha','=', $buscar );
                }
                else{
                    $contratos = $query
                        ->whereBetween('contratos.fecha', [$buscar, $buscar2] );
                }
                break;
            }

            case 'lotes.fraccionamiento_id':{
                $contratos = $query;

                if($credito != '')
                    $contratos = $contratos->where('i.tipo_credito','=',$credito);
                if($buscar != '')
                    $contratos = $contratos->where($criterio, '=', $buscar);
                if($buscar2 != '')
                    $contratos = $contratos->where('lotes.etapa_id', '=', $buscar2);
                if($b_manzana != '')
                    $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
                break;
            }
        }
            
        if($request->b_empresa != ''){
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $contratos = $contratos->orderBy('contratos.saldo','desc')
                        ->orderBy('contratos.id','asc')
                        ->paginate(15);
        if(sizeOf($contratos)){
            foreach($contratos as $index => $contrato){
                $instEle = inst_seleccionada::select('monto_credito','cobrado')->where('credito_id','=',$contrato->folio)->where('elegido','=',1)->get();
                $instEle2 = inst_seleccionada::select('monto_credito','cobrado')->where('credito_id','=',$contrato->folio)->where('tipo','=',1)->get();
                $cobrado = 0;
                $monto_credito = 0;
                if(sizeOf($instEle2)){
                    $cobrado = $instEle2[0]->cobrado;
                    $monto_credito = $instEle2[0]->monto_credito;
                }
                $contrato->cobrado = $instEle[0]->cobrado + $cobrado;
                $contrato->credito_solic = $instEle[0]->monto_credito + $monto_credito;
            }
        }

        return [
            'pagination' => [
                'total'         => $contratos->total(),
                'current_page'  => $contratos->currentPage(),
                'per_page'      => $contratos->perPage(),
                'last_page'     => $contratos->lastPage(),
                'from'          => $contratos->firstItem(),
                'to'            => $contratos->lastItem(),
            ],'contratos' => $contratos,'contador' => $contratos->total()];
    }

    public function excelEstadoCuenta(Request $request){

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $criterio = $request->criterio;
        $b_status = $request->b_status;
        $credito = $request->credito;
        $b_direccion = $request->b_direccion;

        $query = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
            ->leftJoin('creditos','contratos.id','=','creditos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            
            ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                    'licencias.avance',
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
                    'c.f_nacimiento','c.rfc',
                    'c.homoclave','c.direccion','c.colonia','c.cp',
                    'c.telefono','c.email','creditos.num_dep_economicos',
                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                    'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                    'contratos.ext_empresa','contratos.colonia_empresa',
                    'lotes.calle','lotes.numero',

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

        );

        $query = $query->where('i.elegido', '=', 1);

        if($b_status != '')
            $query = $query
                ->where('contratos.status','=',$b_status);

        if($b_direccion != '')
            $query = $query->where(DB::raw("CONCAT(lotes.calle,' ',lotes.numero)"), 'like', '%'. $b_direccion . '%');
                
        switch($criterio){
            case 'c.nombre':{
                $contratos = $query;
                if($buscar != '')
                    $contratos = $contratos->where('c.nombre','like','%'. $buscar . '%');
                if($buscar2 != '')
                    $contratos = $contratos->where('c.apellidos','like','%'. $buscar2 . '%');
                break;
            }

            case 'contratos.id':{
                $contratos = $query;
                if($buscar != '')
                    $contratos = $contratos->where('contratos.id','=', $buscar );
                break;
            }

            case 'contratos.fecha':{
                if($buscar2 == '' && $buscar != ''){
                    $contratos = $query
                        ->where('contratos.fecha','=', $buscar );
                }
                else{
                    $contratos = $query
                        ->whereBetween('contratos.fecha', [$buscar, $buscar2] );
                }
                break;
            }

            case 'lotes.fraccionamiento_id':{
                $contratos = $query;

                if($credito != '')
                    $contratos = $contratos->where('i.tipo_credito','=',$credito);
                if($buscar != '')
                    $contratos = $contratos->where($criterio, '=', $buscar);
                if($buscar2 != '')
                    $contratos = $contratos->where('lotes.etapa_id', '=', $buscar2);
                if($b_manzana != '')
                    $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
                break;
            }
        }

        if($request->b_empresa != ''){
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $contratos = $contratos->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();

        if(sizeOf($contratos)){
            foreach($contratos as $index => $contrato){
                $instEle = inst_seleccionada::select('monto_credito','cobrado')->where('credito_id','=',$contrato->folio)->where('elegido','=',1)->get();
                $instEle2 = inst_seleccionada::select('monto_credito','cobrado')->where('credito_id','=',$contrato->folio)->where('tipo','=',1)->get();
                $cobrado = 0;
                $monto_credito = 0;
                if(sizeOf($instEle2)){
                    $cobrado = $instEle2[0]->cobrado;
                    $monto_credito = $instEle2[0]->monto_credito;
                }
                $contrato->cobrado = $instEle[0]->cobrado + $cobrado;
                $contrato->credito_solic = $instEle[0]->monto_credito + $monto_credito;
            }
        }

        return Excel::create('Relacion estado de cuenta', function($excel) use ($contratos){
            $excel->sheet('Contratos', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto', 'Etapa', 'Manzana', '# Lote',
                    'Avance', 'Precio de Venta', 'Valor a escriturar','Pagares pendientes','Total enganche','Depositos',
                    'Enganche pendiente','Crédito','Crédito cobrado','Gastos administrativos', 'Descuento', 'Saldo'
                ]);

                $sheet->cells('A1:R1', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $cont=1;            
                $sheet->setColumnFormat(array(
                    'H' => '$#,##0.00',
                    'I' => '$#,##0.00',
                    'J' => '$#,##0.00',
                    'K' => '$#,##0.00',
                    'L' => '$#,##0.00',
                    'M' => '$#,##0.00',
                    'N' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'P' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                ));
                
                foreach($contratos as $index => $contrato) {
                    $cont++;
                    $depositos = $contrato->totalPagares - $contrato->totalRestante;
                    $pendienteCredito = $contrato->credito_solic - $contrato->cobrado;
                   
                    $contrato->avance = $contrato->avance.'%';

                    $credito = $contrato->credito_solic + $contrato->segundo_credito;
                    $sheet->row($index+2, [
                        $contrato->folio, 
                        $contrato->nombre_cliente,
                        $contrato->fraccionamiento, 
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->avance, 
                        $contrato->precio_venta, 
                        $contrato->valor_escrituras,
                        $contrato->pagares,
                        $contrato->enganche_total,
                        $depositos,
                        $contrato->pendiente_enganche,
                        $credito,
                        $contrato->cobrado,
                        $contrato->gastos,
                        $contrato->descuento,
                        $contrato->saldo,
                       
                    ]);	
                }


                $num='A1:R' . $cont;
                $sheet->setBorder($num, 'thin');
                
            });
        }
        
        )->download('xls');
    }

    public function estadoPDF ($id){
        setlocale(LC_TIME, 'es_MX.utf8');
        $tiempo = new Carbon();
        $tiempo = $tiempo->formatLocalized('%d de %B de %Y');

        $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
        ->leftJoin('creditos','contratos.id','=','creditos.id')
        ->leftJoin('lotes','creditos.lote_id','=','lotes.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
        
        ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                'creditos.manzana','creditos.num_lote','creditos.modelo',
                'creditos.precio_venta',
                'expedientes.valor_escrituras', 
                'expedientes.descuento', 
                'expedientes.obs_descuento', 
                'expedientes.fecha_liquidacion',
                'expedientes.fecha_firma_esc',
                'lotes.credito_puente',
                'lotes.emp_constructora',
                'lotes.emp_terreno',
                'contratos.enganche_total',
                'contratos.fecha',
                'contratos.saldo',
                'contratos.status',
                'i.tipo_credito',
                'i.institucion',
                'i.monto_credito as credito_solic',
                'i.cobrado',
                'i.segundo_credito',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                'c.rfc','c.homoclave',

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
        ->where('contratos.id',$id)
        ->get();

        if($contratos[0]->descuento == NULL)
            $contratos[0]->descuento = 0;

        $contratos[0]->totalCargo = $contratos[0]->precio_venta + $contratos[0]->gastos;

        if($contratos[0]->status== 0 || $contratos[0]->status == 2){
            $contratos[0]->saldo = $contratos[0]->saldo - $contratos[0]->precio_venta;
            $contratos[0]->totalCargo = $contratos[0]->totalCargo - $contratos[0]->precio_venta;
        }

        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                             ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                             ->select('depositos.cant_depo','depositos.interes_mor','depositos.num_recibo','depositos.fecha_pago','depositos.banco')
                             ->where('contratos.id','=',$id)
                             ->get();

        $desc_interes = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                             ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                             ->select('depositos.desc_interes','depositos.num_recibo','depositos.fecha_pago','depositos.banco')
                             ->where('contratos.id','=',$id)
                             ->where('depositos.desc_interes','>',0)
                             ->get();

        for ($i = 0; $i < count($depositos); $i++) {
            $depositos[$i]->cant_depo = $depositos[$i]->cant_depo - $depositos[$i]->interes_mor;
            $depositos[$i]->cant_depo = number_format((float)$depositos[$i]->cant_depo, 2, '.', ',');
        }

        for ($i = 0; $i < count($desc_interes); $i++) {
            $desc_interes[$i]->desc_interes = number_format((float)$desc_interes[$i]->desc_interes, 2, '.', ',');
        }

        $totalDepositos =  Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
        ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
        ->select(DB::raw("SUM(depositos.cant_depo) as sumDeposito"),DB::raw("SUM(depositos.interes_mor) as sumMor"))
        ->where('contratos.id','=',$id)
        ->get();

        $totalDesc =  Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
        ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
        ->select(DB::raw("SUM(depositos.desc_interes) as sumDesc"))
        ->where('contratos.id','=',$id)
        ->get();

        $contratos[0]->sumDeposito = $totalDepositos[0]->sumDeposito - $totalDepositos[0]->sumMor;

        $contratos[0]->gastos = number_format((float)$contratos[0]->gastos, 2, '.', ',');
        $contratos[0]->precio_venta = number_format((float)$contratos[0]->precio_venta, 2, '.', ',');
        $contratos[0]->valor_escrituras = number_format((float)$contratos[0]->valor_escrituras, 2, '.', ',');

        $gastos_admin = Gasto_admin::select('concepto','costo','fecha')
        ->where('contrato_id','=',$id)
        ->get();

        for($i = 0; $i < count($gastos_admin); $i++){
            $gastos_admin[$i]->costo = number_format((float)$gastos_admin[$i]->costo, 2, '.', ',');
        }

        $depositos_credito = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                                        ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->select('dep_creditos.cant_depo','dep_creditos.banco','dep_creditos.fecha_deposito','inst_seleccionadas.institucion')
                                        ->where('creditos.id','=',$id)
                                        ->get();

        for($i = 0; $i < count($depositos_credito); $i++){
            $depositos_credito[$i]->cant_depo = number_format((float)$depositos_credito[$i]->cant_depo, 2, '.', ',');
        }

        $total_depositos_credito = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                                        ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->select(DB::raw("SUM(dep_creditos.cant_depo) as sumDepositoCredito"))
                                        ->where('creditos.id','=',$id)
                                        ->get();

        $totalDesc[0]->sumDesc += $contratos[0]->descuento;

        
        $contratos[0]->sumDepositoCredito = $total_depositos_credito[0]->sumDepositoCredito;
        $contratos[0]->totalAbono = $contratos[0]->sumDeposito + $contratos[0]->sumDepositoCredito + $totalDesc[0]->sumDesc;
        $contratos[0]->saldo = $contratos[0]->totalCargo - $contratos[0]->totalAbono;

        $contratoChange = Contrato::findOrFail($id);
        $contratoChange->saldo = $contratos[0]->totalCargo - $contratos[0]->totalAbono;
        $contratoChange->save();

        $contratos[0]->sumDepositoCredito = number_format((float)$contratos[0]->sumDepositoCredito, 2, '.', ',');
        $contratos[0]->sumDeposito = number_format((float)$contratos[0]->sumDeposito, 2, '.', ',');
        $contratos[0]->totalAbono = number_format((float)$contratos[0]->totalAbono, 2, '.', ',');
        $contratos[0]->totalCargo = number_format((float)$contratos[0]->totalCargo, 2, '.', ',');
        $contratos[0]->saldo = number_format((float)$contratos[0]->saldo, 2, '.', ',');
        $contratos[0]->descuento = number_format((float)$contratos[0]->descuento, 2, '.', ',');
        $contratos[0]->totalDesc = number_format((float)$totalDesc[0]->sumDesc, 2, '.', ',');

        if($contratos[0]->status== 0 || $contratos[0]->status == 2){
            $contratos[0]->precio_venta = ' 0.00';
        }
        
        $pdf = \PDF::loadview('pdf.contratos.estadoDeCuenta', ['contratos' => $contratos, 'depositos' => $depositos, 'descuentos' => $desc_interes, 'gastos_admin' => $gastos_admin, 'depositos_credito' => $depositos_credito, 'fecha'=> $tiempo]);
        return $pdf->stream('EstadoDeCuenta.pdf');
    }

    public function pendeintesIngresar(Request $request){
        if(!$request->ajax())return redirect('/');
        $pendientes = $this->pendientesIngresoConcretania($request->b_fecha,$request->b_fecha2);
        return['pendientes' => $pendientes];
    }

    public function pendeintesIngresarExcel(Request $request){
        //if(!$request->ajax())return redirect('/');
        $pendientes = $this->pendientesIngresoConcretania($request->b_fecha,$request->b_fecha2);
        //return['pendientes' => $pendientes];
        
        return Excel::create('Pendientes de ingresar', function($excel) use ($pendientes){
            $excel->sheet('Pendientes', function($sheet) use ($pendientes){
                
                $sheet->row(1, [
                    'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Cliente', 'Clasificación', 'Total de deposito', 'Importe a ingresar', 'Fecha'
                ]);

                $sheet->cells('A1:I1', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $cont=1;            
                $sheet->setColumnFormat(array(
                    'H' => '$#,##0.00',
                    'G' => '$#,##0.00',
                ));
                
                foreach($pendientes as $index => $pendiente) {
                    $cont++;

                    $concepto = 'Deposito de crédito';
                    if($pendiente->tipo == 0)
                        $concepto = $pendiente->concepto;
                    
                    $sheet->row($index+2, [
                        $pendiente->fraccionamiento, 
                        $pendiente->etapa, 
                        $pendiente->manzana, 
                        $pendiente->num_lote, 
                        $pendiente->nombre.' '.$pendiente->apellidos,
                        $concepto,
                        $pendiente->cant_depo, 
                        $pendiente->monto_terreno, 
                        $pendiente->fecha_dep, 
                       
                    ]);	
                }


                $num='A1:I' . $cont;
                $sheet->setBorder($num, 'thin');
                
            });
        }
        
        )->download('xls');
    }

    private function pendientesIngresoConcretania($fecha1, $fecha2){

        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
            ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
            ->join('creditos','creditos.id','=','contratos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select('contratos.id','depositos.id as depId',
                    'creditos.fraccionamiento',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote',
                    'personal.nombre','personal.apellidos','depositos.concepto',
                    'depositos.monto_terreno', 'depositos.cant_depo',
                    'depositos.fecha_pago as fecha_dep','depositos.cuenta');

        $ingresosCreditos = Dep_credito::join('inst_seleccionadas','inst_seleccionadas.id','=','dep_creditos.inst_sel_id')
            ->join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('contratos','contratos.id','=','creditos.id')
            ->join('lotes','lotes.id','=','creditos.lote_id')
            ->join('personal','personal.id','=','creditos.prospecto_id')
            ->select('contratos.id', 'dep_creditos.id as depId',
                    'creditos.fraccionamiento',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                    'personal.nombre','personal.apellidos', 'dep_creditos.concepto',
                    'dep_creditos.monto_terreno', 'dep_creditos.cant_depo',
                    'dep_creditos.fecha_deposito as fecha_dep','dep_creditos.cuenta');

            if($fecha1 != '' && $fecha2 != ''){
                $depositos = $depositos->whereBetween('depositos.fecha_pago', [$fecha1, $fecha2]);
                $ingresosCreditos = $ingresosCreditos->whereBetween('dep_creditos.fecha_deposito', [$fecha1, $fecha2]);
            }

        $depositos = $depositos
                    ->where('monto_terreno','>',0)
                    ->where('fecha_ingreso_concretania','=',NULL)
                    ->get();

        $ingresosCreditos = $ingresosCreditos
                    ->where('monto_terreno','>',0)
                    ->where('fecha_ingreso_concretania','=',NULL)
                    ->get();

        $cont =1;
        if(sizeof($depositos))
        foreach($depositos as $index => $dep){
            $dep->cont = $cont;
            $dep->tipo = 0;
            $cont++;
        }

        if(sizeof($ingresosCreditos))
        foreach($ingresosCreditos as $index => $dep){
            $dep->cont = $cont;
            $dep->tipo = 1;
            $cont++;
        }

        $pendienteIngresar = collect($depositos)->merge(collect($ingresosCreditos));


        return $pendienteIngresar;

    }

    public function guardarIngreso(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        if($request->tipo == 0){
            $deposito = Deposito::findOrFail($request->depId);
            $deposito->cuenta = $request->cuenta;
            $deposito->fecha_ingreso_concretania = $request->fecha_ingreso_concretania;
            $deposito->save();
        }
        else{
            $deposito = Dep_credito::findOrFail($request->depId);
            $deposito->cuenta = $request->cuenta;
            $deposito->fecha_ingreso_concretania = $request->fecha_ingreso_concretania;
            $deposito->save();
        }

        $credito = Credito::findOrFail($request->id);
        $credito->saldo_terreno += $request->monto_terreno;
        $credito->save();

    }

    public function historialIngresos(Request $request){
        //if(!$request->ajax())return redirect('/');
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
            ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
            ->join('creditos','creditos.id','=','contratos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select('contratos.id','depositos.id as depId',
                    'pagos_contratos.fecha_pago', 'creditos.fraccionamiento',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 'creditos.saldo_terreno',
                    'personal.nombre','personal.apellidos','depositos.concepto', 'creditos.valor_terreno',
                    'depositos.monto_terreno', 'depositos.fecha_ingreso_concretania', 'depositos.cuenta',
                    'depositos.f_carga_factura_terreno','depositos.cuenta')
            ->whereBetween('depositos.fecha_ingreso_concretania', [$request->fecha, $request->fecha2])
            ->where('monto_terreno','>',0)
            ->where('banco','!=','0102030405-Scotiabank');

        $ingresosCreditos = Dep_credito::join('inst_seleccionadas','inst_seleccionadas.id','=','dep_creditos.inst_sel_id')
            ->join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('contratos','contratos.id','=','creditos.id')
            ->join('lotes','lotes.id','=','creditos.lote_id')
            ->join('personal','personal.id','=','creditos.prospecto_id')
            ->select('contratos.id', 'dep_creditos.id as depId',
                    'dep_creditos.fecha_deposito as fecha_pago','creditos.fraccionamiento',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 'creditos.saldo_terreno',
                    'personal.nombre','personal.apellidos', 'dep_creditos.concepto', 'creditos.valor_terreno',
                    'dep_creditos.monto_terreno', 'dep_creditos.fecha_ingreso_concretania', 'dep_creditos.cuenta',
                    'dep_creditos.f_carga_factura_terreno','dep_creditos.cuenta')
            ->whereBetween('dep_creditos.fecha_ingreso_concretania', [$request->fecha, $request->fecha2])
            ->where('monto_terreno','>',0);

            if($request->cuenta != ''){
                $depositos = $depositos->where('depositos.cuenta','=',$request->cuenta);
                $ingresosCreditos = $ingresosCreditos->where('dep_creditos.cuenta','=',$request->cuenta);
            }

        

        
        if($request->buscar != '')                            
        switch($request->criterio){
            case 'cliente':{
                $depositos = $depositos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->buscar . '%');
                $ingresosCreditos = $ingresosCreditos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->buscar . '%');
                break;
            }
            case 'fraccionamiento':{
                $depositos = $depositos->where('lotes.fraccionamiento_id','=',$request->buscar);
                $ingresosCreditos = $ingresosCreditos->where('lotes.fraccionamiento_id','=',$request->buscar);
                
                if($request->etapa != '')
                    $depositos = $depositos->where('lotes.etapa_id','=',$request->etapa);
                    $ingresosCreditos = $ingresosCreditos->where('lotes.etapa_id','=',$request->etapa);
                if($request->manzana != '')
                    $depositos = $depositos->where('lotes.manzana', 'like', '%'. $request->manzana . '%');
                    $ingresosCreditos = $ingresosCreditos->where('lotes.manzana', 'like', '%'. $request->manzana . '%');
                if($request->lote != '')
                    $depositos = $depositos->where('lotes.num_lote','=',$request->lote);
                    $ingresosCreditos = $ingresosCreditos->where('lotes.num_lote','=',$request->lote);
                break;
            }
        }

            

        $depositos = $depositos
                    ->get();
        $ingresosCreditos = $ingresosCreditos
                    ->get();

        $cont =1;
        if(sizeof($depositos))
        foreach($depositos as $index => $dep){
            $dep->cont = $cont;
            $dep->tipo = 0;
            $cont++;
        }

        if(sizeof($ingresosCreditos))
        foreach($ingresosCreditos as $index => $dep){
            $dep->cont = $cont;
            $dep->tipo = 1;
            $cont++;
        }

        $ingresos = collect($depositos)->merge(collect($ingresosCreditos));


        return[//'depositos' => $depositos,
                //'ingresosCreditos' => $ingresosCreditos,
                'ingresos' => $ingresos,
                
            ];
    }

    public function getPagosVencidos(Request $request){
        $hoy = new Carbon();
        $pagos = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('creditos','creditos.id','=','contratos.id')
                    ->select('creditos.id','creditos.prospecto_id')
                    ->where('pagos_contratos.pagado','<',2)
                    ->where('fecha_pago','<=',$hoy)
                    //->where('prospecto_id','=',113)
                    //->groupBy('creditos.prospecto_id')
                    ->distinct('creditos.prospecto_id')
                    ->get();


        if(sizeof($pagos)){
            foreach ($pagos as $index => $pago) {
                $pago->cliente = Personal::select('nombre','apellidos','celular','clv_lada')
                        ->where('id','=',$pago->prospecto_id)
                        ->first();
            }
        }

        return $pagos;
    }

    

}
