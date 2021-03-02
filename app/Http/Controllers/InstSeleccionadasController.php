<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\inst_seleccionada;
use DB;
use App\Gasto_admin;
use Carbon\Carbon;
use App\Dep_credito;
use Excel;
use App\Contrato;
use App\Credito;
use App\Dev_credito;
use Auth;
use App\Pago_contrato;
use App\Expediente;
use App\User;
use App\Notifications\NotifyAdmin;
use App\Personal;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationReceived;

class InstSeleccionadasController extends Controller
{
    public function indexCreditoSel(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $buscar4 = $request->buscar4;
        $b_cobrados = $request->b_cobrados;
        $criterio = $request->criterio;
        $empresa = $request->empresa;

        $query = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('contratos','contratos.id','=','creditos.id')
            ->join('lotes','lotes.id','=','creditos.lote_id')
            ->join('personal','personal.id','=','creditos.prospecto_id')
            ->select('contratos.id as folio', 'lotes.credito_puente',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                    'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                    'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                    'lotes.fecha_termino_ventas', 'lotes.emp_constructora', 'lotes.emp_terreno',
                    'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
            ->where('lotes.firmado','=',$request->firmado);

        
            if($buscar == '' && $criterio != 'personal.nombre'){
                $creditos = $query;
                if($b_cobrados == 0)
                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                else
                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                    
                    $creditos = $creditos->where('lotes.emp_constructora','like','%'.$empresa.'%')
                    ->where('inst_seleccionadas.elegido', '=', 1)
                    ->where('contratos.status', '=', 3)
                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                    ->orWhere('inst_seleccionadas.tipo', '=', 1);

                    if($b_cobrados == 0)
                        $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                    else
                        $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                    
                    $creditos = $creditos->where('lotes.emp_constructora','like','%'.$empresa.'%')
                    ->where('contratos.status', '=', 3)
                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo');
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                            $creditos = $query
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, 'like', '%' . $buscar . '%');
                                if($buscar2 != '')
                                    $creditos = $creditos->where('personal.apellidos', 'like', '%' . $buscar2 . '%');

                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');

                                $creditos = $creditos->where('lotes.emp_constructora','like','%'.$empresa.'%')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('lotes.emp_constructora','like','%'.$empresa.'%');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, 'like', '%' . $buscar . '%');
                                if($buscar2 != '')
                                    $creditos = $creditos->where('personal.apellidos', 'like', '%' . $buscar2 . '%');
                                
                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                                
                        
                        
                        break;
    
                    }
                    case 'creditos.fraccionamiento': {
                            $creditos = $query
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('lotes.emp_constructora','like','%'.$empresa.'%');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, '=', $buscar);
                                if($buscar2 != '')
                                    $creditos = $creditos->where('creditos.etapa', '=', $buscar2);
                                if($buscar3 != '')
                                    $creditos = $creditos->where('creditos.manzana', '=', $buscar3);
                                if($buscar4 != '')
                                    $creditos = $creditos->where('creditos.num_lote', '=', $buscar4);

                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                                
                                $creditos = $creditos->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('lotes.emp_constructora','like','%'.$empresa.'%');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, '=', $buscar);
                                if($buscar2 != '')
                                    $creditos = $creditos->where('creditos.etapa', '=', $buscar2);
                                if($buscar3 != '')
                                    $creditos = $creditos->where('creditos.manzana', '=', $buscar3);
                                if($buscar4 != '')
                                    $creditos = $creditos->where('creditos.num_lote', '=', $buscar4);

                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                                
                        
                        
                        break;
                    }
    
                    case 'contratos.id': {
                        $creditos = $query
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, '=', $buscar);

                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');

                                $creditos = $creditos->where('lotes.emp_constructora','like','%'.$empresa.'%')
                                    ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                    ->where('contratos.status', '=', 3)
                                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                    ->where('lotes.emp_constructora','like','%'.$empresa.'%');
                                
                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, '=', $buscar);

                                 if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                                
                    }
    
                }
            }
        
       

        $creditos = $creditos->orderBy('inst_seleccionadas.cobrado','asc')
                            ->orderBy('inst_seleccionadas.monto_credito','desc')
                            ->paginate(10);  


        if(sizeof($creditos)){
            foreach($creditos as $et=>$contrato){
                $pagos = Pago_contrato::select('num_pago','fecha_pago')
                    ->where('tipo_pagare','=',0)
                    ->where('contrato_id','=',$contrato->folio)
                    ->orderBy('fecha_pago','desc')
                    ->get();

                $contrato->pagare=$pagos[0]->fecha_pago;

                if($request->firmado == 1){
                    $expediente = Expediente::findOrFail($contrato->folio);

                    $contrato->fecha_firma_esc = $expediente->fecha_firma_esc;
                }
            }
        }
        return[
                'pagination' => [
                'total'         => $creditos->total(),
                'current_page'  => $creditos->currentPage(),
                'per_page'      => $creditos->perPage(),
                'last_page'     => $creditos->lastPage(),
                'from'          => $creditos->firstItem(),
                'to'            => $creditos->lastItem(),
            ],
            'creditos' => $creditos, 'contador' => $creditos->total(), 'firmado' => $request->firmado
        ];
    }

    public function indexDepCredito(Request $request){
        if(!$request->ajax())return redirect('/');

        $depositos = Dep_credito::join('inst_seleccionadas','inst_seleccionadas.id','=','dep_creditos.inst_sel_id')
                            ->select('dep_creditos.id','dep_creditos.cant_depo', 'dep_creditos.banco', 'dep_creditos.fecha_deposito',
                                    'dep_creditos.inst_sel_id', 'inst_seleccionadas.institucion','inst_seleccionadas.monto_credito', 
                                    'inst_seleccionadas.cobrado')
                            ->where('inst_seleccionadas.id', '=', $request->id)
                            ->get();
                            
        return ['depositos' => $depositos];
    }

    public function historialDepositos(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $banco = $request->banco;

        $query = Dep_credito::join('inst_seleccionadas','inst_seleccionadas.id','=','dep_creditos.inst_sel_id')
            ->join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('contratos','contratos.id','=','creditos.id')
            ->join('lotes','lotes.id','=','creditos.lote_id')
            ->join('personal','personal.id','=','creditos.prospecto_id')
            ->select('contratos.id as folio',
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                'personal.nombre','personal.apellidos', 
                'inst_seleccionadas.id as inst_sel_id',
                'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                'dep_creditos.cant_depo', 'dep_creditos.banco', 'dep_creditos.fecha_deposito',
                'lotes.credito_puente'
            );

        
        if($fecha1 != '' && $fecha2 != ''){
            if($banco == ''){
                $depositos = $query
                        ->whereBetween('dep_creditos.fecha_deposito', [$fecha1, $fecha2]);
            }
            else{
                $depositos = $query
                        ->whereBetween('dep_creditos.fecha_deposito', [$fecha1, $fecha2])
                        ->where('dep_creditos.banco','=',$banco);
            }
        }
        else{
            if($banco == ''){
                $depositos = $query;
            }
            else{
                $depositos = $query
                        ->where('dep_creditos.banco','=',$banco);
            }
            
        }

        if($request->bMonto != "") $depositos = $depositos->where('dep_creditos.cant_depo','=',$request->bMonto);

        $depositos = $depositos->orderBy('dep_creditos.fecha_deposito','desc')->paginate(10);
        

        return [
            'pagination' => [
                'total'         => $depositos->total(),
                'current_page'  => $depositos->currentPage(),
                'per_page'      => $depositos->perPage(),
                'last_page'     => $depositos->lastPage(),
                'from'          => $depositos->firstItem(),
                'to'            => $depositos->lastItem(),
            ],
            'depositos' => $depositos,'fecha1'=>$fecha1
        ];

    }

    public function excelHistorialDep(Request $request){

        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $banco = $request->banco;

        $query =  Dep_credito::join('inst_seleccionadas','inst_seleccionadas.id','=','dep_creditos.inst_sel_id')
            ->join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('contratos','contratos.id','=','creditos.id')
            ->join('lotes','lotes.id','=','creditos.lote_id')
            ->join('personal','personal.id','=','creditos.prospecto_id')
            ->select('contratos.id as folio',
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                'personal.nombre','personal.apellidos', 
                'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                'dep_creditos.cant_depo', 'dep_creditos.banco', 'dep_creditos.fecha_deposito'
            );

        if($fecha1 != '' && $fecha2 != ''){
            if($banco == ''){
                $depositos = $query
                        ->whereBetween('dep_creditos.fecha_deposito', [$fecha1, $fecha2])
                        ->orderBy('dep_creditos.fecha_deposito','desc')->get();
            }
            else{
                $depositos = $query
                        ->whereBetween('dep_creditos.fecha_deposito', [$fecha1, $fecha2])
                        ->where('dep_creditos.banco','=',$banco)
                        ->orderBy('dep_creditos.fecha_deposito','desc')->get();
            }
        }
        else{
            if($banco == ''){
                $depositos = $query->orderBy('dep_creditos.fecha_deposito','desc')->get();
            }
            else{
                $depositos = $query
                        ->where('dep_creditos.banco','=',$banco)
                        ->orderBy('dep_creditos.fecha_deposito','desc')->get();
            }
            
        }

        return Excel::create('Depositos', function($excel) use ($depositos){
                $excel->sheet('Depositos', function($sheet) use ($depositos){
                    
                    $sheet->row(1, [
                        '# Contrato', 'Cliente', 'Proyecto', 'Etapa', 'Manzana',
                        '# Lote', 'Institución', 'Crédito',
                        'Cuenta','# Fecha de deposito', 'Monto'
                    ]);

                    $sheet->setColumnFormat(array(
                        'K' => '$#,##0.00',
                    ));

                    $sheet->cells('A1:K1', function ($cells) {
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
                        $fecha1 = new Carbon($deposito->fecha_deposito);
                        $deposito->fecha_deposito = $fecha1->formatLocalized('%d de %B de %Y');

                        $cliente = $deposito->nombre.' '.$deposito->apellidos;
                        $sheet->row($index+2, [
                            $deposito->folio, 
                            $cliente,
                            $deposito->proyecto,
                            $deposito->etapa,
                            $deposito->manzana,
                            $deposito->num_lote,
                            $deposito->institucion,
                            $deposito->tipo_credito,
                            $deposito->banco,
                            $deposito->fecha_deposito,
                            $deposito->cant_depo

                        ]);	
                    }
                    $num='A1:K' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
                }
        )->download('xls');
    }

    public function storeDepositoCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            DB::beginTransaction();
            $deposito = new Dep_credito();
            $deposito->inst_sel_id = $request->inst_sel_id;
            $deposito->cant_depo = $request->cant_depo;
            $deposito->banco = $request->banco;
            $deposito->fecha_deposito = $request->fecha_deposito;
            
            $credito = inst_seleccionada::findOrFail($request->inst_sel_id);
            $credito->cobrado = $credito->cobrado + $request->cant_depo;

            $contrato = Contrato::findOrFail($credito->credito_id);
            $contrato->saldo = round($contrato->saldo - $deposito->cant_depo,2);
            $contrato->save();
            
            $credit = Credito::findOrFail($credito->credito_id);

            if($credit->porcentaje_terreno > 0){
                $saldo = $credit->monto_terreno - $credit->saldo_terreno;

                $porcentaje = $credit->porcentaje_terreno/100;
                $monto_terreno = $deposito->cant_depo*$porcentaje;
                
                if($deposito->monto_terreno > $saldo)
                    $deposito->monto_terreno = $saldo;
                else
                    $deposito->monto_terreno =  $monto_terreno;

            }

            $credito->save();

            $deposito->save();

            $toAlert = [24706];
            $msj = 'Se ha realizado un nuevo abono a crédito';

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

                ///Mail::to($persona->email)->send(new NotificationReceived($msj));
            }
            

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function updateDepositoCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try {
            DB::beginTransaction();
            $deposito = Dep_credito::findOrFail($request->dep_id);
            $credito = inst_seleccionada::findOrFail($request->inst_sel_id);
            $credito->cobrado = $credito->cobrado - $deposito->cant_depo + $request->cant_depo;

            $contrato = Contrato::findOrFail($credito->credito_id);
            $contrato->saldo = round($contrato->saldo + $deposito->cant_depo - $request->cant_depo,2);
            $contrato->save();

            $credit = Credito::findOrFail($credito->credito_id);

            if($credit->porcentaje_terreno > 0){
                $saldo = $credit->monto_terreno - $credit->saldo_terreno;

                $porcentaje = $credit->porcentaje_terreno/100;
                $monto_terreno = $deposito->cant_depo*$porcentaje;
                
                if($deposito->monto_terreno > $saldo)
                    $deposito->monto_terreno = $saldo;
                else
                    $deposito->monto_terreno =  $monto_terreno;
            }

            $credito->save();
            $deposito->inst_sel_id = $request->inst_sel_id;
            $deposito->cant_depo = $request->cant_depo;
            $deposito->banco = $request->banco;
            $deposito->fecha_deposito = $request->fecha_deposito;

            $deposito->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function excelCobroCredito (Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $buscar4 = $request->buscar4;
        $b_cobrados = $request->b_cobrados;
        $criterio = $request->criterio;
        $empresa = $request->empresa;

        $query = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('contratos','contratos.id','=','creditos.id')
                    ->join('lotes','lotes.id','=','creditos.lote_id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('contratos.id as folio', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'lotes.fecha_termino_ventas', 'lotes.emp_constructora', 'lotes.emp_terreno',
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                            ->where('lotes.firmado','=',$request->firmado);

            if($buscar == '' && $criterio != 'personal.nombre'){
                $creditos = $query;
                if($b_cobrados == 0)
                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                else
                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                    
                    $creditos = $creditos->where('lotes.emp_constructora','like','%'.$empresa.'%')
                    ->where('inst_seleccionadas.elegido', '=', 1)
                    ->where('contratos.status', '=', 3)
                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                    ->orWhere('inst_seleccionadas.tipo', '=', 1);

                    if($b_cobrados == 0)
                        $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                    else
                        $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                    
                    $creditos = $creditos->where('lotes.emp_constructora','like','%'.$empresa.'%')
                    ->where('contratos.status', '=', 3)
                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo');
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                            $creditos = $query
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, 'like', '%' . $buscar . '%');
                                if($buscar2 != '')
                                    $creditos = $creditos->where('personal.apellidos', 'like', '%' . $buscar2 . '%');

                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');

                                $creditos = $creditos->where('lotes.emp_constructora','like','%'.$empresa.'%')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('lotes.emp_constructora','like','%'.$empresa.'%');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, 'like', '%' . $buscar . '%');
                                if($buscar2 != '')
                                    $creditos = $creditos->where('personal.apellidos', 'like', '%' . $buscar2 . '%');
                                
                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                                
                        
                        
                        break;

                    }
                    case 'creditos.fraccionamiento': {
                            $creditos = $query
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('lotes.emp_constructora','like','%'.$empresa.'%');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, '=', $buscar);
                                if($buscar2 != '')
                                    $creditos = $creditos->where('creditos.etapa', '=', $buscar2);
                                if($buscar3 != '')
                                    $creditos = $creditos->where('creditos.manzana', '=', $buscar3);
                                if($buscar4 != '')
                                    $creditos = $creditos->where('creditos.num_lote', '=', $buscar4);

                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                                
                                $creditos = $creditos->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('lotes.emp_constructora','like','%'.$empresa.'%');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, '=', $buscar);
                                if($buscar2 != '')
                                    $creditos = $creditos->where('creditos.etapa', '=', $buscar2);
                                if($buscar3 != '')
                                    $creditos = $creditos->where('creditos.manzana', '=', $buscar3);
                                if($buscar4 != '')
                                    $creditos = $creditos->where('creditos.num_lote', '=', $buscar4);

                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                                
                        
                        
                        break;
                    }

                    case 'contratos.id': {
                        $creditos = $query
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo');

                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, '=', $buscar);

                                if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');

                                $creditos = $creditos->where('lotes.emp_constructora','like','%'.$empresa.'%')
                                    ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                    ->where('contratos.status', '=', 3)
                                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                    ->where('lotes.emp_constructora','like','%'.$empresa.'%');
                                
                                if($buscar != '')
                                    $creditos = $creditos->where($criterio, '=', $buscar);

                                    if($b_cobrados == 0)
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
                                else
                                    $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');
                                
                    }

                }
            }

        $creditos = $creditos->orderBy('inst_seleccionadas.cobrado','asc')
                            ->orderBy('inst_seleccionadas.monto_credito','desc')
                            ->get();

        if(sizeof($creditos)){
            foreach($creditos as $et=>$contrato){
                $pagos = Pago_contrato::select('num_pago','fecha_pago')
                    ->where('tipo_pagare','=',0)
                    ->where('contrato_id','=',$contrato->folio)
                    ->orderBy('fecha_pago','desc')
                    ->get();

                $contrato->pagare=$pagos[0]->fecha_pago;
            }
        }

        return Excel::create('creditos', function($excel) use ($creditos){
            $excel->sheet('creditos', function($sheet) use ($creditos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto','Etapa', 'Manzana',
                    '# Lote','Credito puente', 'Institución', 'Crédito', 'Cobrado',
                    'Pendiente', 'Fecha de termino', 'Fecha pagare'
                ]);


                $sheet->cells('A1:M1', function ($cells) {
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

                foreach($creditos as $index => $credito) {
                    $cont++;

                    $montoCredito = number_format((float)$credito->monto_credito, 2, '.', ',');
                    $cobrado = number_format((float)$credito->cobrado, 2, '.', ',');
                    $pend = $credito->monto_credito - $credito->cobrado;
                    $pendiente = number_format((float)$pend, 2, '.', ',');

                    $sheet->row($index+2, [
                        $credito->folio, 
                        $credito->nombre. ' ' . $credito->apellidos,
                        $credito->proyecto,
                        $credito->etapa,
                        $credito->manzana,
                        $credito->num_lote,
                        $credito->credito_puente,
                        $credito->institucion,
                        '$ '.$montoCredito,
                        '$ '.$cobrado,
                        '$ '.$pendiente,
                        $credito->fecha_termino_ventas,
                        $credito->pagare,
                        

                    ]);	
                }
                $num='A1:M' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    public function indexDevolucion (Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        $query = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('contratos','contratos.id','=','creditos.id')
            ->join('lotes','lotes.id','=','creditos.lote_id')
            ->leftJoin('expedientes','expedientes.id','=','contratos.id')
            ->join('personal','personal.id','=','creditos.prospecto_id')
            ->select('contratos.id', 'lotes.credito_puente',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                    'personal.nombre','personal.apellidos', 
                    'expedientes.fecha_firma_esc',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                    'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                    'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                    'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
        )
        ->where('contratos.saldo','<',0)
        ->where('inst_seleccionadas.elegido', '=', 1);
        
            switch($criterio){
                case 'creditos.id':{
                    $creditos = $query;
                    if($buscar != '')
                        $creditos = $creditos->where($criterio,'=',$buscar);
                    break;
                }
                case 'personal.nombre':{
                    $creditos = $query;
                    if($buscar != '')
                        $creditos = $creditos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                        $creditos = $query;
                        if($buscar != '')
                            $creditos = $creditos->where($criterio,'=',$buscar);
                        if($b_etapa != '')
                            $creditos = $creditos->where('lotes.etapa_id','=',$b_etapa);
                        if($b_manzana != '')
                            $creditos = $creditos->where('lotes.manzana','=',$b_manzana);
                        if($b_lote != '')
                            $creditos = $creditos->where('lotes.num_lote','=',$b_lote);
                    break;
                }
            }
        

        if($request->b_empresa != ''){
            $creditos= $creditos->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $creditos = $creditos->where('contratos.status','!=',0)
                            ->orderBy('expedientes.fecha_firma_esc','asc')
                            ->orderBy('inst_seleccionadas.cobrado','asc')
                            ->orderBy('inst_seleccionadas.monto_credito','desc')
                            ->paginate(10);
        

        return[
                'pagination' => [
                'total'         => $creditos->total(),
                'current_page'  => $creditos->currentPage(),
                'per_page'      => $creditos->perPage(),
                'last_page'     => $creditos->lastPage(),
                'from'          => $creditos->firstItem(),
                'to'            => $creditos->lastItem(),
            ],
            'creditos' => $creditos
        ];
        
    }

    public function excelDevolucion (Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        $query = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('contratos','contratos.id','=','creditos.id')
            ->join('lotes','lotes.id','=','creditos.lote_id')
            ->join('personal','personal.id','=','creditos.prospecto_id')
            ->select('contratos.id', 'lotes.credito_puente',
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                'personal.nombre','personal.apellidos', 
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
        )
        ->where('contratos.saldo','<',0)
        ->where('inst_seleccionadas.elegido', '=', 1);

        if($request->b_empresa != ''){
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        switch($criterio){
            case 'creditos.id':{
                $creditos = $query;
                if($buscar != '')
                    $creditos = $creditos->where($criterio,'=',$buscar);
                break;
            }
            case 'personal.nombre':{
                $creditos = $query;
                if($buscar != '')
                    $creditos = $creditos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }
            case 'lotes.fraccionamiento_id':{
                    $creditos = $query;
                    if($buscar != '')
                        $creditos = $creditos->where($criterio,'=',$buscar);
                    if($b_etapa != '')
                        $creditos = $creditos->where('lotes.etapa_id','=',$b_etapa);
                    if($b_manzana != '')
                        $creditos = $creditos->where('lotes.manzana','=',$b_manzana);
                    if($b_lote != '')
                        $creditos = $creditos->where('lotes.num_lote','=',$b_lote);
                break;
            }
        }

        $creditos = $creditos->where('contratos.status','!=',0)
                            ->orderBy('inst_seleccionadas.cobrado','asc')
                            ->orderBy('inst_seleccionadas.monto_credito','desc')
                            ->paginate(10);
        

        return Excel::create('Pendientes por excedente', function($excel) use ($creditos){
            $excel->sheet('pendientes', function($sheet) use ($creditos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto','Etapa', 'Manzana',
                    '# Lote', 'Pendiente a devolver'
                ]);


                $sheet->cells('A1:G1', function ($cells) {
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

                $sheet->setColumnFormat(array(
                    'G' => '$#,##0.00',
                ));

                
                $cont=1;

                foreach($creditos as $index => $credito) {
                    $cont++;

                    $credito->saldo = $credito->saldo*-1;

                    $sheet->row($index+2, [
                        $credito->id, 
                        $credito->nombre_cliente,
                        $credito->proyecto,
                        $credito->etapa,
                        $credito->manzana,
                        $credito->num_lote,
                        $credito->saldo,
                        

                    ]);	
                }
                $num='A1:G' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
        
    }

    public function storeDevolucion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();

            $devolucion = new Dev_credito();
            $devolucion->contrato_id = $request->id;
            $devolucion->devolver = $request->cant_dev;
            $devolucion->fecha = $request->fecha;
            $devolucion->cheque = $request->cheque;
            $devolucion->cuenta = $request->cuenta;
            $devolucion->observaciones = $request->observaciones;
            $devolucion->save();

            $contrato = Contrato::findOrFail($request->id);
            $contrato->saldo += $request->cant_dev;
            $contrato->saldo = round($contrato->saldo,2);
            if($contrato->saldo < 0 && $contrato->saldo > -0.001)
                $contrato->saldo = 0;
            $contrato->save();

            $gastos = new Gasto_admin();
            $gastos->contrato_id = $request->id;
            $gastos->concepto = "Devolución por excedente";
            $gastos->costo = $request->cant_dev;
            $gastos->observacion = $request->observaciones;
            $gastos->fecha = $request->fecha;
            $gastos->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function finalizar(Request $request){
        if(!$request->ajax())return redirect('/');

        $credito = inst_seleccionada::findOrFail($request->id);
        $credito->monto_credito = $credito->cobrado;
        $credito->save();
    }

    public function eliminar(Request $request){
        if(!$request->ajax())return redirect('/');

        $credito = inst_seleccionada::findOrFail($request->id);
        $credito->delete();
    }

    public function indexHistorialDev(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.precio_base',
                'creditos.precio_venta',
                'creditos.fraccionamiento as proyecto',
                'creditos.lote_id',

                'personal.nombre',
                'personal.apellidos',
                'personal.telefono',
                'personal.celular',
                'personal.email',
                'personal.direccion',
                'personal.cp',
                'personal.colonia',
                'personal.f_nacimiento',
                'personal.rfc',
                'personal.homoclave',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                
                'v.nombre as vendedor_nombre',
                'v.apellidos as vendedor_apellidos',
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                

                'contratos.status',
                'contratos.fecha_status',
                'contratos.total_pagar',
                'contratos.monto_total_credito',
                'contratos.enganche_total',
                'contratos.avance_lote',
                'contratos.observacion',

                'dev_creditos.fecha',
                'dev_creditos.cheque',
                'dev_creditos.cuenta',
                'dev_creditos.observaciones',
                'dev_creditos.devolver',


                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                            WHERE pagos_contratos.contrato_id = contratos.id
                            GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                
                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                            WHERE pagos_contratos.contrato_id = contratos.id
                            GROUP BY pagos_contratos.contrato_id) as sumaRestante")
        );

        if($request->b_empresa != ''){
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);
        }
       
        
        switch ($criterio){
            case 'lotes.fraccionamiento_id':{
                $devoluciones = $query;
                if($buscar != '')
                    $devoluciones = $devoluciones->where($criterio, '=', $buscar);
                if($b_etapa != '')
                    $devoluciones = $devoluciones->where('lotes.etapa_id', '=', $b_etapa);
                if($b_manzana != '')
                    $devoluciones = $devoluciones->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $devoluciones = $devoluciones->where('lotes.num_lote', '=', $b_lote);

                break;
            }
            case 'creditos.id':{
                $devoluciones = $query;
                if($buscar != '')
                    $devoluciones = $devoluciones->where($criterio, '=', $buscar);
                break;
            }
            case 'personal.nombre':{
                $devoluciones = $query;
                if($buscar != '')
                    $devoluciones = $devoluciones->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }
        }
        

        $devoluciones = $devoluciones->orderBy('id', 'desc')->paginate(8);
        
        return [
            'pagination' => [
                'total'         => $devoluciones->total(),
                'current_page'  => $devoluciones->currentPage(),
                'per_page'      => $devoluciones->perPage(),
                'last_page'     => $devoluciones->lastPage(),
                'from'          => $devoluciones->firstItem(),
                'to'            => $devoluciones->lastItem(),
            ], 'devoluciones' => $devoluciones//, 'contadorContrato' => $contadorContratos
        ];
    }

    public function excelHistDev(Request $request){
        //if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.precio_base',
                'creditos.precio_venta',
                'creditos.fraccionamiento as proyecto',
                'creditos.lote_id',

                'personal.nombre',
                'personal.apellidos',
                'personal.telefono',
                'personal.celular',
                'personal.email',
                'personal.direccion',
                'personal.cp',
                'personal.colonia',
                'personal.f_nacimiento',
                'personal.rfc',
                'personal.homoclave',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                
                'v.nombre as vendedor_nombre',
                'v.apellidos as vendedor_apellidos',
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                

                'contratos.status',
                'contratos.fecha_status',
                'contratos.total_pagar',
                'contratos.monto_total_credito',
                'contratos.enganche_total',
                'contratos.avance_lote',
                'contratos.observacion',

                'dev_creditos.fecha',
                'dev_creditos.cheque',
                'dev_creditos.cuenta',
                'dev_creditos.observaciones',
                'dev_creditos.devolver',


                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                            WHERE pagos_contratos.contrato_id = contratos.id
                            GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                
                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                            WHERE pagos_contratos.contrato_id = contratos.id
                            GROUP BY pagos_contratos.contrato_id) as sumaRestante")
        );

        if($request->b_empresa != ''){
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        switch ($criterio){
            case 'lotes.fraccionamiento_id':{
                $devoluciones = $query;
                if($buscar != '')
                    $devoluciones = $devoluciones->where($criterio, '=', $buscar);
                if($b_etapa != '')
                    $devoluciones = $devoluciones->where('lotes.etapa_id', '=', $b_etapa);
                if($b_manzana != '')
                    $devoluciones = $devoluciones->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $devoluciones = $devoluciones->where('lotes.num_lote', '=', $b_lote);

                break;
            }
            case 'creditos.id':{
                $devoluciones = $query;
                if($buscar != '')
                    $devoluciones = $devoluciones->where($criterio, '=', $buscar);
                break;
            }
            case 'personal.nombre':{
                $devoluciones = $query;
                if($buscar != '')
                    $devoluciones = $devoluciones->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }
        }
        

        $devoluciones = $devoluciones->orderBy('id', 'desc')->paginate(8);
       
        
        
        return Excel::create('devoluciones', function($excel) use ($devoluciones){
            $excel->sheet('devoluciones', function($sheet) use ($devoluciones){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto', 'Etapa', 'Manzana',
                    '# Lote','Devuelto', 'Fecha cancelación', 'Fecha devolución',
                    '# Cheque', 'Cuenta'
                ]);

                $sheet->setColumnFormat(array(
                    'G' => '$#,##0.00',
                ));


                $sheet->cells('A1:K1', function ($cells) {
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

                foreach($devoluciones as $index => $devolucion) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($devolucion->fecha);
                    $fecha2 = new Carbon($devolucion->fecha_status);
                    $devolucion->fecha = $fecha1->formatLocalized('%d de %B de %Y');
                    $devolucion->fecha_status = $fecha2->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $devolucion->id, 
                        $devolucion->nombre. ' ' . $devolucion->apellidos,
                        $devolucion->proyecto,
                        $devolucion->etapa,
                        $devolucion->manzana,
                        $devolucion->num_lote,
                        $devolucion->devolver,
                        $devolucion->fecha_status,
                        $devolucion->fecha,
                        $devolucion->cheque,
                        $devolucion->cuenta,

                    ]);	
                }
                $num='A1:K' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }
}