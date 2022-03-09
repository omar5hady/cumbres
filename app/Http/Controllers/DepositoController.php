<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationReceived;
use App\Notifications\NotifyAdmin;
use Illuminate\Http\Request;
use App\Deposito;
use App\Deposito_conc;
use App\Deposito_gcc;
use App\Contrato;
use App\Cuenta;
use App\Credito;
use App\Pago_contrato;
use App\inst_seleccionada;
use App\Gasto_admin;
use App\Dep_credito;
use App\Personal;
use App\Devolucion;
use App\User;
use Carbon\Carbon;
use NumerosEnLetras;
use Excel;
use Auth;
use File;
use DB;

// Controlador para Depositos bancarios.
class DepositoController extends Controller
{
    // Función que retorna los pagares creados.
    public function indexPagares(Request $request){
        if(!$request->ajax())return redirect('/');
        setlocale(LC_TIME, 'es_MX.utf8');
        // Se almacena la fecha actual.
        $hoy = Carbon::today()->toDateString();
        // Llamada a la función privada que retorna los pagares
        $pagares = $this->getPagares($request, $hoy);
        // Se ordenan primero los pendientes y por fecha de pago.
        $pagares = $pagares ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
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
    // Función que retorna los pagares en excel.
    public function excelPagares(Request $request){
        setlocale(LC_TIME, 'es_MX.utf8');
        // Se almacena la fecha actual.
        $hoy = Carbon::today()->toDateString();
        // Llamada a la función privada que retorna los pagares.
        $pagares = $this->getPagares($request, $hoy);
        // Se ordenan primero los pendientes y por fecha de pago.
        $pagares = $pagares->orderBy('pagos_contratos.pagado', 'asc')
                        ->orderBy('pagos_contratos.fecha_pago', 'asc')
                        ->get();
        // Se crea el excel y retorna.
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
    // Función privada que obtiene los pagares
    private function getPagares(Request $request, $hoy){
        $vencido = $request->b_vencidos;
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio =  $request->criterio;
        // Query principal.
        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
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
                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor")
            );

        // Busqueda por empresa constructora
        if($request->b_empresa != '')
            $pagares = $pagares->where('lotes.emp_constructora','=', $request->b_empresa);

        if($vencido == 1){ //PAGARES VENCIDOS
            if($buscar != ''){
                switch($criterio){
                    case 'personal.nombre':{ // Busqueda por cliente
                        $pagares = $pagares
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{ // Busqueda por fecha de pago
                        $pagares = $pagares->whereBetween($criterio, [$buscar,$buscar2]);
                        break;
                    }
                    case 'creditos.fraccionamiento':{ // Busqueda por lote
                        $pagares = $pagares->where($criterio,'=',$buscar);
                            if($buscar2!='')
                                $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                            if($buscar3!='')
                                $pagares = $pagares->where('creditos.manzana','=',$buscar3);
                        break;
                    }
                    default:{
                        $pagares = $pagares->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }
            // Contratos activos y pagares pendientes.
            $pagares = $pagares->where('pagos_contratos.pagado','!=',2)
                ->where('pagos_contratos.pagado','!=',3)
                ->where('contratos.status','!=',0)
                ->where('contratos.status','!=',2) 
                // Fecha de pago anterior a fecha actual.
                ->where('pagos_contratos.fecha_pago','<',$hoy);
        } 
        elseif($vencido == 2){ //CANCELADOS
            //Contratos cancelados y no firmados.
            $pagares = $pagares->whereIn('contratos.status',[0,2]);
            if($buscar != ''){
                switch($criterio){
                    case 'personal.nombre':{ // Busqueda por cliente
                        $pagares = $pagares
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{ // Busqeuda por fecha de pago
                        $pagares = $pagares
                            ->whereBetween($criterio, [$buscar,$buscar2]);
                        break;
                    }
                    case 'creditos.fraccionamiento':{ // Busqueda por lote
                            if($buscar!='')
                                $pagares = $pagares->where($criterio,'=',$buscar);
                            if($buscar2 !='')
                                $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                            if($buscar3 != '')
                                $pagares = $pagares->where('creditos.manzana','=',$buscar3);
                        break;
                    }
                    default:{
                        $pagares = $pagares
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }
        }
        else{ //TODOS
            if($buscar != ''){
                switch($criterio){
                    case 'personal.nombre':{ // Busqueda por cliente
                        $pagares = $pagares
                            ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{ // Busqueda por fecha de pago
                        $pagares = $pagares
                            ->whereBetween($criterio, [$buscar,$buscar2]);
                        break;
                    }
                    case 'creditos.fraccionamiento':{ // Busqueda por lote
                        if($buscar != '')
                            $pagares = $pagares->where($criterio,'=',$buscar);
                        if($buscar2 != '')
                            $pagares = $pagares->where('creditos.etapa','=',$buscar2);
                        if($buscar3 != '')
                            $pagares = $pagares->where('creditos.manzana','=',$buscar3);
                        break;
                    }
                    default:{
                        $pagares = $pagares
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }
            // Contratos activos.
            $pagares = $pagares->where('contratos.status','!=',0)
                                ->where('contratos.status','!=',2);
        }
        return $pagares;
    }
    // Función que retorna los depositos correspondientes a un pagare.
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
    // Función que retorna todos los depositos registrados.
    public function historialDepositos(Request $request){
        if(!$request->ajax())return redirect('/');
        // Llamada a la función privada que retorna los depositos.
        $depositos = $this->getHistorialDep($request);
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
    // Función que retorna en excel todos los depositos registrados.
    public function excelHistorialDepositos(Request $request){
        // Llamada a la función privada que retorna los depositos.
        $depositos = $this->getHistorialDep($request);
        $depositos = $depositos->orderBy('depositos.fecha_pago','desc')->get();
        // Creación de excel.
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
    // Función privada que obtiene el historial de depositos.
    private function getHistorialDep(Request $request){
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $banco = $request->banco;
        $monto = $request->monto;
        $nombre = $request->nombre;
        // Query
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
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
                    'depositos.banco','depositos.concepto','depositos.fecha_pago'
                );

        if($request->b_empresa != '') // Busqueda por empresa
            $depositos= $depositos->where('lotes.emp_constructora','=',$request->b_empresa);
        if($fecha1 != '' && $fecha2 != '') // Busqueda por fecha de deposito
            $depositos = $depositos->whereBetween('depositos.fecha_pago', [$fecha1, $fecha2]);
        if($banco != '') // Busqueda por cuenta bancaria
            $depositos = $depositos->where('depositos.banco','=',$banco);
        if($monto != '') // Busqueda por monto
            $depositos = $depositos->where('depositos.cant_depo','=',$monto);
        if($nombre != '') // Busqueda por cliente.
            $depositos = $depositos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $nombre . '%');

        return $depositos;
    }
    // Funcion que retorna en excel depositos en un rango de fecha y por cuenta bancaria.
    public function excelDepositos(Request $request){
        // Query
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
            ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->select('pagos_contratos.contrato_id','depositos.cant_depo','depositos.num_recibo',
                    'depositos.banco', 'depositos.fecha_pago', 'depositos.concepto',
                    'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote');

        if($banco != '') // Busqueda por cuenta bancaria
            $depositos = $depositos->where('depositos.banco','=',$request->banco);
        if($desde != '') // Busqueda por fecha de deposito
            $depositos = $depositos->whereBetween('depositos.fecha_pago', [$request->desde, $request->hasta]);

        $depositos = $depositos->get();
        // Retorno en excel.
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
    // Funciíon para registrar depositos.
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            // Registro en la Tabla Depositos
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
            // Pago sin interes
            $pago = $request->cant_depo - $request->interes_mor - $request->interes_ord;
            $descuento = $request->descuento;
            // Se accede al pagare correspondiente del deposito.
            $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
            // Se calcula el monto restante del pagare
            $pago_contrato->restante =  $pago_contrato->restante - $pago;
            // Si el monto del pagare ha sido cubierto completamente.
            if($pago_contrato->restante <= 0){
                $pago_contrato->pagado = 2; // Pagare pagado.
                if($pago_contrato->restante < 0){ // Si el restante supera el monto del pagare
                    $restante = abs($pago_contrato->restante); // Se calcula el monto sobrante
                    $pago_contrato->restante = 0; // El pagare se iguala a 0
                    $pago_contrato->save(); // Guardan cambios
                    
                    $nextPago = Pago_contrato::select('id') // El restante se añade al siguiente pagare
                                ->where('contrato_id','=',$pago_contrato->contrato_id)
                                ->where('pagado','<',2)
                                ->where('tipo_pagare','=',$pago_contrato->tipo_pagare)
                                ->orderBy('fecha_pago','asc')
                                ->get();
                    if(sizeof($nextPago)){
                        $i = 0;
                        do{
                            $n = Pago_contrato::findOrFail($nextPago[$i]->id);
                            $n->restante = $n->restante - $restante;
                            $restante = $n->restante;
                            $n->pagado = 1;
                            if($n->restante < 0){
                                $restante = abs($n->restante);
                                $n->restante = 0;
                                $n->pagado = 2;
                            }
                            $n->save();
                            $i++;
                        }while($n->restante < 0);
                    }
                        
                }
            }
            else{ // Si el monto del pagare no ha sido cubierto.
                $pago_contrato->pagado = 1; // El pagare queda como abonado.
            }

            if($request->interes_ord != 0){ // En caso de tener intereses ordinarios.
                // El interes se almacena en la tabla de Gastos Administrativos.
                $gasto = new Gasto_admin(); 
                $gasto->contrato_id = $pago_contrato->contrato_id;
                $gasto->concepto = 'Interes Ordinario';
                $gasto->costo = $request->interes_ord;
                $gasto->fecha = $request->fecha_pago;
                $gasto->observacion = '';
                $gasto->save();
            }

            if($request->interes_mor != 0){ // En caso de tener intereses moratorios.
                // El interes se almacena en la tabla de Gastos Administrativos.
                $gasto = new Gasto_admin();
                $gasto->contrato_id = $pago_contrato->contrato_id;
                $gasto->concepto = 'Interes Moratorio';
                $gasto->costo = $request->interes_mor;
                $gasto->fecha = $request->fecha_pago;
                $gasto->observacion = '';
                $gasto->save();
            }
            // Se actualiza el saldo del contrato.
            $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
            $contrato->saldo = round($contrato->saldo - $pago - $descuento,2);
            
            // Se indica a que lote pertenece el deposito.
            $credit = Credito::findOrFail($pago_contrato->contrato_id);
            $deposito->lote_id = $credit->lote_id;
            // En caso de ser un lote alianza
            if($credit->porcentaje_terreno > 0){
                // Se calcula el monto correspondiente al terreno.
                $saldo = $credit->valor_terreno - $credit->saldo_terreno;
                $porcentaje = $credit->porcentaje_terreno/100;
                $monto_terreno = $pago*$porcentaje;
                // En caso de ser mayor el monto calculado al saldo del terreno
                if($monto_terreno > $saldo)
                    $deposito->monto_terreno = $saldo; // monto se iguala al saldo
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
                // Se envia notificación en caso de ser Apartado y al alcanzar deposito mayor a 50'000.00
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
    // Función para actualizar depositos.
    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            // Se accede al deposito
            $deposito = Deposito::findOrFail($request->id);
            // Se accede al pagare correspondiente.
            $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
            $diferencia = 0;
            // Se verifica si el monto ha cambiado
            if($deposito->cant_depo != $request->cant_depo){
                // Se calcula la diferencia de montos entre el registrado contra la actualización
                $diferencia = $deposito->cant_depo - $request->cant_depo;
            }
            // Se obtiene el monto Anterior
            $pagoAnt = $deposito->cant_depo - $deposito->interes_mor - $deposito->interes_ord;
            // Se obtiene el nuevo monto
            $pago = $request->cant_depo - $request->interes_mor - $request->interes_ord;
            // Se verifica si hay interes ordinario
            if($request->interes_ord != $deposito->interes_ord  && $request->interes_ord>0){
                // Se busca si existe registro de un interes ordinario anterior.
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Ordinario')
                            ->where('costo','=',$deposito->interes_ord)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                // Se actualiza el interes ordinario existente
                if(sizeof($b_gasto) != 0){
                    $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                    $gasto->costo = $request->interes_ord;
                    $gasto->save();
                }
                else{ // En caso de no tener interes ordinario, se crea uno nuevo.
                    $gasto = new Gasto_admin();
                    $gasto->contrato_id = $pago_contrato->contrato_id;
                    $gasto->concepto = 'Interes Ordinario';
                    $gasto->costo = $request->interes_ord;
                    $gasto->fecha = $request->fecha_pago;
                    $gasto->observacion = '';
                    $gasto->save();
                }              
            }
            // Si la actualización no incluye interes ordinario
            elseif($request->interes_ord != $deposito->interes_ord  && $request->interes_ord==0){
                // Se busca el registro anterior
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Ordinario')
                            ->where('costo','=',$deposito->interes_ord)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                    // Se elimina el interes ordinario anterior.
                    $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                    $gasto->delete();
            }
            // Se verifica si hay interes moratorio.
            if($request->interes_mor != $deposito->interes_mor  && $request->interes_mor>0){
                // Se busca el registro del interes moratorio
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Moratorio')
                            ->where('costo','=',$deposito->interes_mor)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                // Se actualiza el registro existente
                if(sizeof($b_gasto) != 0){
                    $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                    $gasto->costo = $request->interes_mor;
                    $gasto->save();
                }
                else{   // En caso de no tener interes anterior, se crea uno nuevo.
                    $gasto = new Gasto_admin();
                    $gasto->contrato_id = $pago_contrato->contrato_id;
                    $gasto->concepto = 'Interes Moratorio';
                    $gasto->costo = $request->interes_mor;
                    $gasto->fecha = $request->fecha_pago;
                    $gasto->observacion = '';
                    $gasto->save();
                }              
            }
            // Si la actualización no incluye interes moratorio.
            elseif($request->interes_mor != $deposito->interes_mor  && $request->interes_mor==0){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Moratorio')
                            ->where('costo','=',$deposito->interes_mor)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                    // Se elimina el registro anterior.
                    $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                    $gasto->delete();
            }
            // Se actualiza el registro del deposito.
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
           // Calculo del saldo para el pagare.
            $pago_contrato->restante = $pago_contrato->restante + $diferencia;
            if($pago_contrato->restante == 0)
                // Si el pagare ha sido liquidado.
                $pago_contrato->pagado = 2;
            else{
                // Si el pagare tiene saldo pendiente.
                $pago_contrato->pagado = 1;
            }
            // Se accede al registro del contrato
            $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
            // Se actualiza el nuevo saldo del contrato.
            $contrato->saldo = round($contrato->saldo + $pagoAnt + $descAnt - $descuento - $pago,2);
            $contrato->save(); 
            // Se accede al registro del crédito.
            $credit = Credito::findOrFail($pago_contrato->contrato_id);
            // En caso de ser un lote de alianza.
            if($credit->porcentaje_terreno > 0){
                // Se calcula el saldo actual del terreno.
                $saldo = $credit->valor_terreno - $credit->saldo_terreno;
                $porcentaje = $credit->porcentaje_terreno/100;
                // Calculo del porcentaje de monto correspondiente al deposito.
                $monto_terreno = $pago*$porcentaje;
                // Si el monto calculado supera al saldo actual
                if($monto_terreno > $saldo)
                    // El monto se iguala al saldo
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
    // Función para eliminar el registro de un deposito.
    public function delete(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            // Se accede al registro del deposito.
            $deposito = Deposito::findOrFail($request->id);
            // Se calcula el pago realizado.
            $pagoAnt = $deposito->cant_depo - $deposito->interes_mor - $deposito->interes_ord;
            // Se accede al pagare.
            $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
            $pago = $deposito->cant_depo - $deposito->interes_mor - $deposito->interes_ord;
            // Se actualiza el saldo del pagare sumandole el monto depositado que se eliminara.
            $pago_contrato->restante = $pago_contrato->restante + $pago;
            // Se accede al registro del contrato.
            $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
            // Actualización del saldo.
            $contrato->saldo = round($contrato->saldo + $pagoAnt, 2);
            // Se verifica que exista un registro por interes ordinario
            if($deposito->interes_ord != 0){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Ordinario')
                            ->where('costo','=',$deposito->interes_ord)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                // Se elimina el registro.
                $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                $gasto->delete();
            }
            // Se verifica que exista un registro por interes moratorio
            if($deposito->interes_mor != 0){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Moratorio')
                            ->where('costo','=',$deposito->interes_mor)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();
                // Se elimina el registro.
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
    // Función para imprimir el recibo del deposito en PDF.
    public function reciboPDF($id){
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                    ->join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('creditos','creditos.id','=','contratos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('depositos.id', 'depositos.pago_id', 'depositos.cant_depo',
                            'depositos.interes_mor','depositos.interes_ord',
                            'lotes.emp_constructora','lotes.emp_terreno',
                            'depositos.obs_mor','depositos.obs_ord','depositos.num_recibo',
                            'depositos.banco','depositos.concepto','depositos.fecha_pago',
                            'creditos.manzana', 'creditos.num_lote','personal.nombre',
                            'personal.apellidos','creditos.fraccionamiento'
                            )
                    ->where('depositos.id','=',$id)
                    ->get();
        // Se da formato a la cantidad depositada
        $depositos[0]->cantdepLetra = NumerosEnLetras::convertir($depositos[0]->cant_depo,'Pesos',true,'Centavos');
        $fechaDeposito = new Carbon($depositos[0]->fecha_pago);
        // Se da formato a la fecha.
        $depositos[0]->fecha_pago = $fechaDeposito->formatLocalized('%d días de %B de %Y');
        // Se carga la vista blade con el formado del deposito.
        $pdf = \PDF::loadview('pdf.reciboDePagos',['depositos' => $depositos]);
        return $pdf->stream('recibo_de_pago.pdf');
    }
    // Función privada que obtiene los contratos para el estado de cuenta.
    private function getContratosEdoCuenta(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $criterio = $request->criterio;
        $b_status = $request->b_status;
        $credito = $request->credito;
        $b_direccion = $request->b_direccion;

        // Query
        $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
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
                        'creditos.email_fisc',
                        'creditos.tel_fisc',
                        'creditos.nombre_fisc',
                        'creditos.direccion_fisc',
                        'creditos.col_fisc',
                        'creditos.cp_fisc',
                        'creditos.rfc_fisc',
                        'creditos.cfi_fisc',
                        'creditos.regimen_fisc',
                        'creditos.banco_fisc',
                        'creditos.num_cuenta_fisc',
                        'creditos.clabe_fisc',
                        'creditos.archivo_fisc',
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
        $contratos = $contratos->where('i.elegido', '=', 1);
        // Estatus del contrato
        if($b_status != '')
            $contratos = $contratos
                ->where('contratos.status','=',$b_status);
        // Dirección oficial
        if($b_direccion != '')
            $contratos = $contratos->where(DB::raw("CONCAT(lotes.calle,' ',lotes.numero)"), 'like', '%'. $b_direccion . '%');
                
        switch($criterio){
            // Busqueda por cliente
            case 'c.nombre':{
                $contratos = $contratos;
                if($buscar != '')
                    $contratos = $contratos->where('c.nombre','like','%'. $buscar . '%');
                if($buscar2 != '')
                    $contratos = $contratos->where('c.apellidos','like','%'. $buscar2 . '%');
                break;
            }
            // Busqueda por folio de contrato
            case 'contratos.id':{
                $contratos = $contratos;
                if($buscar != '')
                    $contratos = $contratos->where('contratos.id','=', $buscar );
                break;
            }

            case 'contratos.fecha':{
                if($buscar2 == '' && $buscar != ''){
                    $contratos = $contratos
                        ->where('contratos.fecha','=', $buscar );
                }
                else{
                    $contratos = $contratos
                        ->whereBetween('contratos.fecha', [$buscar, $buscar2] );
                }
                break;
            }

            case 'lotes.fraccionamiento_id':{
                $contratos = $contratos;

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

        return $contratos;

    }
    // Función que retorna los contratos para estado de cuenta.
    public function indexEstadoCuenta(Request $request){
        if(!$request->ajax())return redirect('/');
        // Llamada a la función privada para obtener los contratos para el edo de cuenta.
        $contratos = $this->getContratosEdoCuenta($request);
        $contratos = $contratos->orderBy('contratos.saldo','desc')
                        ->orderBy('contratos.id','asc')
                        ->paginate(15);

        if(sizeOf($contratos)){
            // Se recorren todos los contratos
            foreach($contratos as $index => $contrato){
                // Se obtienen las instituciones de financiamiento del contrato
                $instEle = inst_seleccionada::select('monto_credito','cobrado')->where('credito_id','=',$contrato->folio)->where('elegido','=',1)->get();
                $instEle2 = inst_seleccionada::select('monto_credito','cobrado')->where('credito_id','=',$contrato->folio)->where('tipo','=',1)->get();
                $cobrado = 0;
                $monto_credito = 0;
                if(sizeOf($instEle2)){ // Se obtienen los montos cobrados
                    // Para el segundo financiamiento.
                    $cobrado = $instEle2[0]->cobrado;
                    $monto_credito = $instEle2[0]->monto_credito;
                }
                // Se obtiene el monto cobrado para el primer financiamiento.
                $contrato->cobrado = $instEle[0]->cobrado + $cobrado;
                // La suma total de crédito solicitado.
                $contrato->credito_solic = $instEle[0]->monto_credito + $monto_credito;
                $contrato->depositos = 0;
                // Se obtienen todos los depositos
                $depositos = Deposito::join('pagos_contratos as pg','depositos.pago_id','=','pg.id')
                    ->select('depositos.id','depositos.cant_depo')
                    ->where('pg.contrato_id','=',$contrato->folio)
                    ->get();
                
                if(sizeof($depositos))
                    foreach ($depositos as $key => $deposito) {
                        // Se suman los depositos.
                        $contrato->depositos += $deposito->cant_depo;
                    }
                // Se calcula el monto pendiente por cobrar del enganche.
                $contrato->pendiente_enganche = $contrato->enganche_total - $contrato->depositos;
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
    // Función que retorna los contratos para estado de cuenta en excel.
    public function excelEstadoCuenta(Request $request){
        // Llamada a la función privada para obtener los contratos para el edo de cuenta.
        $contratos = $this->getContratosEdoCuenta($request);
        $contratos = $contratos->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();        

        if(sizeOf($contratos)){
            // Se recorren todos los contratos
            foreach($contratos as $index => $contrato){
                // Se obtienen las instituciones de financiamiento del contrato
                $instEle = inst_seleccionada::select('monto_credito','cobrado')->where('credito_id','=',$contrato->folio)->where('elegido','=',1)->get();
                $instEle2 = inst_seleccionada::select('monto_credito','cobrado')->where('credito_id','=',$contrato->folio)->where('tipo','=',1)->get();
                $cobrado = 0;
                $monto_credito = 0;
                if(sizeOf($instEle2)){ // Se obtienen los montos cobrados
                    // Para el segundo financiamiento.
                    $cobrado = $instEle2[0]->cobrado;
                    $monto_credito = $instEle2[0]->monto_credito;
                }
                // Se obtiene el monto cobrado para el primer financiamiento.
                $contrato->cobrado = $instEle[0]->cobrado + $cobrado;
                // La suma total de crédito solicitado.
                $contrato->credito_solic = $instEle[0]->monto_credito + $monto_credito;
                $contrato->depositos = 0;
                // Se obtienen todos los depositos
                $depositos = Deposito::join('pagos_contratos as pg','depositos.pago_id','=','pg.id')
                    ->select('depositos.id','depositos.cant_depo')
                    ->where('pg.contrato_id','=',$contrato->folio)
                    ->get();
                
                if(sizeof($depositos))
                    foreach ($depositos as $key => $deposito) {
                        // Se suman los depositos.
                        $contrato->depositos += $deposito->cant_depo;
                    }
                // Se calcula el monto pendiente por cobrar del enganche.
                $contrato->pendiente_enganche = $contrato->enganche_total - $contrato->depositos;
            }
        }
        // Créación de Excel.
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
                    //$depositos = $contrato->totalPagares - $contrato->totalRestante;
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
                        $contrato->depositos,
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
    // Función para retornar el estado de cuenta por cliente.
    public function estadoPDF ($id){
        setlocale(LC_TIME, 'es_MX.utf8');
        // Fecha actual
        $tiempo = new Carbon();
        $tiempo = $tiempo->formatLocalized('%d de %B de %Y');
        // Se obtienen los datos del contrato.
        $contrato = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
            ->leftJoin('creditos','contratos.id','=','creditos.id')
            ->leftJoin('lotes','creditos.lote_id','=','lotes.id')
            ->leftJoin('modelos','lotes.modelo_id','modelos.id')
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
                    'modelos.nombre as modelo',
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
                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                        WHERE gastos_admin.contrato_id = contratos.id 
                        GROUP BY gastos_admin.contrato_id) as gastos")
                    )
            ->where('i.elegido', '=', 1)
            ->where('contratos.id',$id)
        ->first();
        // variable para almacenar los intereses
        $contrato->interesGen = 0;
        // Venta de terrenos
        if($contrato->modelo == 'Terreno'){
            // Se obtienen los pagos del contrato con los interes correspondientes a cada pagare
            $pagos = Pago_contrato::join('pagos_lotes','pagos_contratos.id','=','pagos_lotes.pagare_id')
                        ->select('pagos_lotes.interes_monto')
                        ->where('pagos_contratos.contrato_id','=',$contrato->folio)->get();

            foreach ($pagos as $key => $pago) {
                // Se suma el total de intereses por cada pagare.
                $contrato->interesGen += $pago->interes_monto;
            }
            // Precio de venta con intereses.
            $contrato->ventaCInteres = $contrato->interesGen + $contrato->precio_venta;
            $contrato->ventaCInteres = number_format((float)$contrato->ventaCInteres, 2, '.', ',');
        }
        // Llamada a la función privada para calcular el saldo del terreno para venta por alianza.
        $this->calculateSaldoTerreno($id);
        // Si no hay un descuento registrado, se iguala la variable a 0
        if($contrato->descuento == NULL)
            $contrato->descuento = 0;
        // Suma total de cargos a la venta.
        $contrato->totalCargo = $contrato->precio_venta + $contrato->gastos + $contrato->interesGen;
        // Contratos cancelados o no firmados.
        if($contrato->status== 0 || $contrato->status == 2){
            // Calculo para el saldo de la venta.
            $contrato->saldo = $contrato->saldo - $contrato->precio_venta - $contrato->interesGen;
            $contrato->totalCargo = $contrato->totalCargo - $contrato->precio_venta - $contrato->interesGen;
        }
        // Query para obtener los depositos realizados a la venta.
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                             ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                             ->select('depositos.cant_depo','depositos.interes_mor','depositos.num_recibo','depositos.fecha_pago','depositos.banco')
                             ->where('contratos.id','=',$id)
                             ->get();
        // Query para obtener los descuentos a intereses generados.
        $desc_interes = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                             ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                             ->select('depositos.desc_interes','depositos.num_recibo','depositos.fecha_pago','depositos.banco')
                             ->where('contratos.id','=',$id)
                             ->where('depositos.desc_interes','>',0)
                             ->get();

        for ($i = 0; $i < count($depositos); $i++) {
            // Se calcula el monto depositado a capital
            $depositos[$i]->cant_depo = $depositos[$i]->cant_depo - $depositos[$i]->interes_mor;
            if($depositos[$i]->cant_depo < 0)
                // En caso de solo cubrir intereses, el monto a capital se iguala a 0
                $depositos[$i]->cant_depo = 0;
            // Se da formato numerico para el pdf.
            $depositos[$i]->cant_depo = number_format((float)$depositos[$i]->cant_depo, 2, '.', ',');
            $depositos[$i]->interes_mor = number_format((float)$depositos[$i]->interes_mor, 2, '.', ',');
        }
        // Formato a los descuento de intereses.
        for ($i = 0; $i < count($desc_interes); $i++) {
            $desc_interes[$i]->desc_interes = number_format((float)$desc_interes[$i]->desc_interes, 2, '.', ',');
        }
        // Query para obtener la suma total de monto depositado y monto total de intereses moratorios
        $totalDepositos =  Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
        ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
        ->select(DB::raw("SUM(depositos.cant_depo) as sumDeposito"),DB::raw("SUM(depositos.interes_mor) as sumMor"))
        ->where('contratos.id','=',$id)
        ->get();
        // Query para obtener la suma total de descuento de intereses
        $totalDesc =  Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
        ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
        ->select(DB::raw("SUM(depositos.desc_interes) as sumDesc"))
        ->where('contratos.id','=',$id)
        ->get();
        // Se almacena la suma de depositos.
        $contrato->sumDeposito = $totalDepositos[0]->sumDeposito;
        // Se aplica formato numerico.
        $contrato->gastos = number_format((float)$contrato->gastos, 2, '.', ',');
        $contrato->precio_venta = number_format((float)$contrato->precio_venta, 2, '.', ',');
        $contrato->valor_escrituras = number_format((float)$contrato->valor_escrituras, 2, '.', ',');
        $contrato->interesTerreno = number_format((float)$contrato->interesGen, 2, '.', ',');
        // Query para obtener los gastos administrativos.
        $gastos_admin = Gasto_admin::select('concepto','costo','fecha')
        ->where('contrato_id','=',$id)
        ->get();

        if(sizeof($gastos_admin)){
            // Recorren los gastos administrativos
            foreach ($gastos_admin as $key => $gasto) {
                $gasto->cuenta = '';
                // Para los gastos por devolución
                if($gasto->concepto == 'Devolución por cancelación' || $gasto->concepto == 'Devolución'){
                    // Se busca la devolución correspondiente para obtener la cuenta bancaria.
                    $dev = Devolucion::select('cuenta')->where('contrato_id','=',$id)
                                ->where('devolver','=',$gasto->costo)
                                ->where('fecha','=',$gasto->fecha)->first();
                    
                    if($dev)
                        $gasto->cuenta = $dev->cuenta;
                }
            }
        }
        // Formato numerico para cada gasto administrativo.
        for($i = 0; $i < count($gastos_admin); $i++){
            $gastos_admin[$i]->costo = number_format((float)$gastos_admin[$i]->costo, 2, '.', ',');
        }
        // Query para obtener los depositos por crédito bancario.
        $depositos_credito = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                                        ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->select('dep_creditos.cant_depo','dep_creditos.banco','dep_creditos.fecha_deposito','inst_seleccionadas.institucion')
                                        ->where('creditos.id','=',$id)
                                        ->get();
        $contrato->sumDepositoCredito = 0;
        
        for($i = 0; $i < count($depositos_credito); $i++){
            // Se suman todos los depositos institucionales
            $contrato->sumDepositoCredito += $depositos_credito[$i]->cant_depo;
            // Formato numerco para cada deposito institucional
            $depositos_credito[$i]->cant_depo = number_format((float)$depositos_credito[$i]->cant_depo, 2, '.', ',');
        }
       
        $totalDesc[0]->sumDesc += $contrato->descuento;
        // Se calcula el total de abonos a la venta.
        $contrato->totalAbono = $contrato->sumDeposito + $contrato->sumDepositoCredito + $totalDesc[0]->sumDesc;
        // Calculo del saldo pendiente.
        $contrato->saldo = $contrato->totalCargo - $contrato->totalAbono;
        // Se actualiza el saldo actual de la venta.
        $contratoChange = Contrato::findOrFail($id);
        $contratoChange->saldo = $contrato->totalCargo - $contrato->totalAbono;
        $contratoChange->save();
        // Formato numerico.
        $contrato->sumDepositoCredito = number_format((float)$contrato->sumDepositoCredito, 2, '.', ',');
        $contrato->sumDeposito = number_format((float)$contrato->sumDeposito, 2, '.', ',');
        $contrato->totalAbono = number_format((float)$contrato->totalAbono, 2, '.', ',');
        $contrato->totalCargo = number_format((float)$contrato->totalCargo, 2, '.', ',');
        $contrato->saldo = number_format((float)$contrato->saldo, 2, '.', ',');
        $contrato->descuento = number_format((float)$contrato->descuento, 2, '.', ',');
        $contrato->totalDesc = number_format((float)$totalDesc[0]->sumDesc, 2, '.', ',');
        // Si la venta esta cancelada, el precio de venta queda en 0
        if($contrato->status== 0 || $contrato->status == 2){
            $contrato->precio_venta = ' 0.00';
        }
        // Retorno del PDF
        $pdf = \PDF::loadview('pdf.contratos.estadoDeCuenta', ['contrato' => $contrato, 'depositos' => $depositos, 'descuentos' => $desc_interes, 'gastos_admin' => $gastos_admin, 'depositos_credito' => $depositos_credito, 'fecha'=> $tiempo]);
        return $pdf->stream('EstadoDeCuenta.pdf');
    }
    // Función para calcular el saldo del terreno para ventas por alianza.
    private function calculateSaldoTerreno($id){
        // Accede al registro en la tabla de Creditos.
        $credito = Credito::findOrFail($id);
        // Se obtienen las cuentas bancarias de Cumbres.
        $cuentas = $this->getCuentas('Grupo Constructor Cumbres');
            // Se obtiene la sumatoria porcentual de depositos institucionales ingresados a cumbres
            $sumaDepositoCreditTerreno = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->select(DB::raw("SUM(dep_creditos.monto_terreno) as suma"))->where('contratos.id','=',$id)
                ->where('dep_creditos.fecha_ingreso_concretania','!=',NULL)
                ->where('inst_seleccionadas.elegido','=',1)
                ->get();
                if($sumaDepositoCreditTerreno[0]->suma == NULL){
                    $sumaDepositoCreditTerreno[0]->suma = 0;
                }
            // Se obtiene la sumatoria porcentual de depositos de enganche ingresados a cumbres
            $sumaDepositoTerreno = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                ->select(DB::raw("SUM(depositos.monto_terreno) as suma"))->where('contratos.id','=',$id)
                ->where('depositos.fecha_ingreso_concretania','!=',NULL)
                ->where('depositos.lote_id','=',$credito->lote_id)
                ->get();
                if($sumaDepositoTerreno[0]->suma == NULL){
                    $sumaDepositoTerreno[0]->suma = 0;
                }
            // Sumatoria de depositos ingresados cuentas cumbres.
            $sumaCuentaCumbres = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                ->select(DB::raw("SUM(depositos.cant_depo) as suma"))
                ->where('contratos.id','=',$id)
                ->where('depositos.lote_id','=',$credito->lote_id)
                ->whereIn('depositos.banco',$cuentas)
                ->get();
                if($sumaCuentaCumbres[0]->suma == NULL){
                    $sumaCuentaCumbres[0]->suma = 0;
                }
            // Sumatoria de depositos reubicados a de cuentas cumbres
            $depositoGCC = Deposito_gcc::select(DB::raw("SUM(depositos_gcc.monto) as suma"))
                ->where('depositos_gcc.contrato_id','=',$id)
                ->where('depositos_gcc.lote_id','=',$credito->lote_id)
                ->get();
                if($depositoGCC[0]->suma == NULL){
                    $depositoGCC[0]->suma = 0;
                }
            // Sumatoria de depositos reubicados a de cuentas concretania
            $depositoConc = Deposito_conc::select(DB::raw("SUM(depositos_conc.monto) as suma"))
                ->where('depositos_conc.contrato_id','=',$id)
                ->where('depositos_conc.lote_id','=',$credito->lote_id)
                ->where('depositos_conc.devolucion','=',1)
                ->get();
                if($depositoConc[0]->suma == NULL){
                    $depositoConc[0]->suma = 0;
                }
        // Calculo del saldo.
        $credito->saldo_terreno = $sumaDepositoCreditTerreno[0]->suma + $sumaCuentaCumbres[0]->suma + 
                $sumaDepositoTerreno[0]->suma + $depositoGCC[0]->suma -  $depositoConc[0]->suma;
        $credito->save();
    }
    // Función para retornar los ingresos pendientes por ingresar a cumbres, para ventas por alianza.
    public function pendeintesIngresar(Request $request){
        if(!$request->ajax())return redirect('/');
        // Llamada a la función privada que obtiene los ingresos.
        $pendientes = $this->pendientesIngresoConcretania($request->b_fecha,$request->b_fecha2);
        return['pendientes' => $pendientes];
    }
    // Función para retornar en Excel los ingresos pendientes por ingresar a cumbres, para ventas por alianza.
    public function pendeintesIngresarExcel(Request $request){
        //if(!$request->ajax())return redirect('/');
        // Llamada a la función privada que obtiene los ingresos.
        $pendientes = $this->pendientesIngresoConcretania($request->b_fecha,$request->b_fecha2);
        // Se crea el documento Excel.
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
    // Funcion para obtener todos los ingresos pendientes por ingresar a cumbres.
    private function pendientesIngresoConcretania($fecha1, $fecha2){
        // Se obtienen las cuentas bancarias de Concretania.
        $cuentas = $this->getCuentas('CONCRETANIA');
        $depositos = $this->getDepositosConc();// Query para depositos de enganche.
        $ingresosCreditos = $this->getIngresosConc();// Query para ingresos institucionales.
        $depositos = $depositos // Filtros para depositos de enganche
            ->where('lotes.emp_constructora','=','Concretania')
            ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
            ->where('monto_terreno','>',0)
            ->where('contratos.status','!=',2)
            ->whereIn('depositos.banco',$cuentas)
            ->where('fecha_ingreso_concretania','=',NULL);
            if($fecha1 != '' && $fecha2 != ''){
                $depositos = $depositos->whereBetween('depositos.fecha_pago', [$fecha1, $fecha2]);
            }
            $depositos = $depositos->get();

        $ingresosCreditos = $ingresosCreditos // Filtros para ingresos institucionales.
            ->where('lotes.emp_constructora','=','Concretania')
            ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
            ->where('monto_terreno','>',0)
            ->Where(function ($query) use($cuentas) {
                for ($i = 0; $i < count($cuentas); $i++){
                    $query->orwhere('dep_creditos.banco', 'like',  $cuentas[$i] .'%');
                }      
            })
            ->where('dep_creditos.fecha_ingreso_concretania','=',NULL);
            if($fecha1 != '' && $fecha2 != ''){
                $ingresosCreditos = $ingresosCreditos->whereBetween('dep_creditos.fecha_deposito', [$fecha1, $fecha2]);
            }
            $ingresosCreditos = $ingresosCreditos->get();

        
        $cont =1;
        
        if($fecha1 != '' && $fecha2 != ''){
            $depositosAnt = $this->getDepositosConc();
            $ingresosCreditosAnt = $this->getIngresosConc();

            $depositosAnt = $depositosAnt
                ->where('lotes.emp_constructora','=','Concretania')
                ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
                ->where('monto_terreno','>',0)
                ->where('contratos.status','!=',2)
                ->whereIn('depositos.banco',$cuentas)
                ->where('fecha_ingreso_concretania','=',NULL);
                if($fecha1 != '' && $fecha2 != ''){
                    $depositosAnt = $depositosAnt->whereBetween('depositos.fecha_pago', [$fecha1, $fecha2]);
                }
                $depositosAnt = $depositosAnt->orWhere('lotes.emp_constructora','=','Concretania')
                ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
                ->where('monto_terreno','>',0)
                ->where('contratos.status','!=',2)
                ->whereIn('depositos.banco',$cuentas)
                ->where('fecha_ingreso_concretania','=',NULL);
                if($fecha1 != '' && $fecha2 != ''){
                    $depositosAnt = $depositosAnt->whereBetween('depositos.fecha_pago', [$fecha1, $fecha2]);
                }
                $depositosAnt = $depositosAnt->get();

            $ingresosCreditosAnt = $ingresosCreditosAnt
                ->where('lotes.emp_constructora','=','Concretania')
                ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
                ->where('monto_terreno','>',0)
                ->Where(function ($query) use($cuentas) {
                    for ($i = 0; $i < count($cuentas); $i++){
                        $query->orwhere('dep_creditos.banco', 'like',  $cuentas[$i] .'%');
                    }      
                })
                ->where('dep_creditos.fecha_ingreso_concretania','=',NULL);
                if($fecha1 != '' && $fecha2 != ''){
                    $ingresosCreditosAnt = $ingresosCreditosAnt->whereBetween('dep_creditos.fecha_deposito', [$fecha1, $fecha2]);
                }
                $ingresosCreditosAnt = $ingresosCreditosAnt->orWhere('lotes.emp_constructora','=','Concretania')
                ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
                ->where('monto_terreno','>',0)
                ->Where(function ($query) use($cuentas) {
                    for ($i = 0; $i < count($cuentas); $i++){
                    $query->orwhere('dep_creditos.banco', 'like',  $cuentas[$i] .'%');
                    }      
                })
                ->where('dep_creditos.fecha_ingreso_concretania','=',NULL);
                if($fecha1 != '' && $fecha2 != ''){
                    $ingresosCreditosAnt = $ingresosCreditosAnt->whereBetween('dep_creditos.fecha_deposito', [$fecha1, $fecha2]);
                }
                $ingresosCreditosAnt = $ingresosCreditosAnt->get();

        }

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
        // Se juntan los depositos de enganche e ingresos bancarios en un solo arreglo
        $pendienteIngresar = collect($depositos)->merge(collect($ingresosCreditos));
        return $pendienteIngresar;
    }
    // Función privada para obtener los depositos por pago de enganche.
    private function getDepositosConc(){
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
                    'depositos.fecha_pago as fecha_dep','depositos.banco');
        
        return $depositos;

    }
    // Función privada para obtener los ingresos institucionales
    private function getIngresosConc(){
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
                    'dep_creditos.fecha_deposito as fecha_dep','dep_creditos.banco');

        return $ingresosCreditos;
    }
    // Función para guardar ingreso a cumbres en modalidad alianza.
    public function guardarIngreso(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        // Se accede al registro del crédito.
        $credito = Credito::findOrFail($request->id);
        if($request->tipo == 0){ // Depositos por pago de enganche
            $deposito = Deposito::findOrFail($request->depId);
            $deposito->cuenta = $request->cuenta;
            $deposito->lote_id = $credito->lote_id;
            $deposito->fecha_ingreso_concretania = $request->fecha_ingreso_concretania;
            $deposito->obs_ingreso = $request->observacion;
            $deposito->save();
        }
        else{ // Depositos institucionales.
            $deposito = Dep_credito::findOrFail($request->depId);
            $deposito->cuenta = $request->cuenta;
            $deposito->fecha_ingreso_concretania = $request->fecha_ingreso_concretania;
            $deposito->obs_ingreso = $request->observacion;
            $deposito->save();
        }
        // Se actualiza el saldo del terreno.
        $credito = Credito::findOrFail($request->id);
        $credito->saldo_terreno += $request->monto_terreno;
        $credito->save();
    }
    // Funcion para retornar el historial de ingresos por alianza.
    public function historialIngresos(Request $request){
        if(!$request->ajax())return redirect('/');
        // Depositos por pago de enganche 
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
            ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
            ->join('creditos','creditos.id','=','contratos.id')
            ->join('lotes','depositos.lote_id','=','lotes.id')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select('contratos.id','depositos.id as depId',
                    'pagos_contratos.fecha_pago', 'fraccionamientos.nombre as fraccionamiento',
                    'etapas.num_etapa as etapa', 'lotes.manzana', 'lotes.num_lote', 'creditos.saldo_terreno',
                    'personal.nombre','personal.apellidos','depositos.concepto', 'creditos.valor_terreno',
                    'depositos.monto_terreno', 'depositos.fecha_ingreso_concretania', 'depositos.cuenta',
                    'depositos.fecha_pago as fecha','depositos.cuenta','depositos.obs_ingreso')
            ->whereBetween('depositos.fecha_ingreso_concretania', [$request->fecha, $request->fecha2])
            ->where('monto_terreno','>',0);
        // Depositos por ingresos institucionales.
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
                    'dep_creditos.f_carga_factura_terreno','dep_creditos.cuenta', 'dep_creditos.fecha_deposito as fecha',
                    'dep_creditos.obs_ingreso'
                    )
            ->whereBetween('dep_creditos.fecha_ingreso_concretania', [$request->fecha, $request->fecha2])
            ->where('monto_terreno','>',0);

            if($request->cuenta != ''){// Filtro si se especifica una cuenta bancaria.
                $depositos = $depositos->where('depositos.cuenta','=',$request->cuenta);
                $ingresosCreditos = $ingresosCreditos->where('dep_creditos.cuenta','=',$request->cuenta);
            }
        
        if($request->buscar != '')                            
        switch($request->criterio){
            case 'cliente':{ // Busqueda por cliente.
                $depositos = $depositos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->buscar . '%');
                $ingresosCreditos = $ingresosCreditos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->buscar . '%');
                break;
            }
            case 'fraccionamiento':{ // Busqueda por proyecto.
                $depositos = $depositos->where('lotes.fraccionamiento_id','=',$request->buscar);
                $ingresosCreditos = $ingresosCreditos->where('lotes.fraccionamiento_id','=',$request->buscar);
                
                if($request->etapa != '')// Etapa
                    $depositos = $depositos->where('lotes.etapa_id','=',$request->etapa);
                    $ingresosCreditos = $ingresosCreditos->where('lotes.etapa_id','=',$request->etapa);
                if($request->manzana != '')// Manzana
                    $depositos = $depositos->where('lotes.manzana', 'like', '%'. $request->manzana . '%');
                    $ingresosCreditos = $ingresosCreditos->where('lotes.manzana', 'like', '%'. $request->manzana . '%');
                if($request->lote != '')// Lote
                    $depositos = $depositos->where('lotes.num_lote','=',$request->lote);
                    $ingresosCreditos = $ingresosCreditos->where('lotes.num_lote','=',$request->lote);
                break;
            }
        }

        $depositos = $depositos->get();
        $ingresosCreditos = $ingresosCreditos->get();

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
        // Se juntan los depositos de enganche e ingresos bancarios en un solo arreglo
        $ingresos = collect($depositos)->merge(collect($ingresosCreditos));

        return[//'depositos' => $depositos,
                //'ingresosCreditos' => $ingresosCreditos,
                'ingresos' => $ingresos,
                
            ];
    }
    // Función para obtener los pagos vencidos.
    public function getPagosVencidos(Request $request){
        $hoy = new Carbon();
        $pagos = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('creditos','creditos.id','=','contratos.id')
                    ->select('creditos.id','creditos.prospecto_id')
                    ->where('pagos_contratos.pagado','<',2)
                    ->where('fecha_pago','<=',$hoy)
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
    public function asignarLotes(){
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                                ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->select('depositos.id','pagos_contratos.contrato_id','creditos.lote_id','depositos.lote_id as dep_lote')
                                ->get();

        return $depositos;
    }

    // Función privada que retorna las cuentas bancarias por empresa.
    private function getCuentas($cuenta){
        $cuentas = Cuenta::select('num_cuenta','banco')->where('empresa','=',$cuenta)->get();
        $arrayCuentas = [];

        foreach($cuentas as $index => $cuenta){
            array_push($arrayCuentas,$cuenta->num_cuenta.'-'.$cuenta->banco);
        }

        return $arrayCuentas;

    }

}
