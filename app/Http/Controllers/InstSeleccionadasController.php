<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationReceived;
use App\Notifications\NotifyAdmin;
use App\inst_seleccionada;
use App\Gasto_admin;
use App\Dev_credito;
use App\Pago_contrato;
use App\Lote_puente;
use App\Pago_puente;
use App\Expediente;
use Carbon\Carbon;
use App\Dep_credito;
use App\Personal;
use App\Contrato;
use App\Credito;
use App\User;
use Excel;
use Auth;
use DB;

//Controlador para el modelo Instituciones Seleccionadas.
class InstSeleccionadasController extends Controller
{
    //Función que retorna la query necesaria para obtener los registros de financiamientos
    private function getFinanciamientos(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $buscar4 = $request->buscar4;
        $b_cobrados = $request->b_cobrados;
        $criterio = $request->criterio;
        $empresa = $request->empresa;
        //Query principal
        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('contratos','contratos.id','=','creditos.id')
            ->join('lotes','lotes.id','=','creditos.lote_id')
            ->join('personal','personal.id','=','creditos.prospecto_id')
            ->leftJoin('lotes_puente','lotes.id','=','lotes_puente.lote_id')
            ->select('contratos.id as folio', 'lotes.credito_puente',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote',
                    'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                    'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion',
                    'lotes.puente_id',
                    'lotes.fecha_termino_ventas', 'lotes.emp_constructora', 'lotes.emp_terreno',
                    'lotes_puente.saldo as saldoPuente','lotes_puente.abonado as abonadoPuente',
                    'lotes_puente.cobrado as cobradoPuente', 'lotes_puente.liberado', 'lotes_puente.precio_c',
                    'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
            //Ventas firmadas
            ->where('lotes.firmado','=',$request->firmado)
            //Busqueda por empresa constructora
            ->where('lotes.emp_constructora','like','%'.$empresa.'%')
            ->where('inst_seleccionadas.status', '=', 2)//Financiamiento autorizado
            ->where('contratos.status', '=', 3)//Contrato firmado
            //Financiamientos diferentes a Créditos Directos (Solo bancarios)
            ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo');
            //Financiamiento con saldo pendiente
            if($b_cobrados == 0)
                $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito');
            //Financiamiento cobrado
            else
                $creditos = $creditos->whereRaw('inst_seleccionadas.cobrado = inst_seleccionadas.monto_credito');

            if($buscar != '' ){
                switch($criterio){
                    //Busqueda por nombre de cliente
                    case 'personal.nombre':{
                        //Financiamiento elegido para el contrato
                        if($buscar != '')//Busqueda por nombre
                            $creditos = $creditos->where($criterio, 'like', '%' . $buscar . '%');
                        if($buscar2 != '')//Busqueda por apellidos
                            $creditos = $creditos->where('personal.apellidos', 'like', '%' . $buscar2 . '%');
                        break;
                    }
                    case 'creditos.fraccionamiento': {
                        if($buscar != '')//Busqueda por proyecto
                            $creditos = $creditos->where($criterio, '=', $buscar);
                        if($buscar2 != '')//Busqueda por etapa
                            $creditos = $creditos->where('creditos.etapa', '=', $buscar2);
                        if($buscar3 != '')//Busqueda por manzana
                            $creditos = $creditos->where('creditos.manzana', '=', $buscar3);
                        if($buscar4 != '')//Busqueda por numero de lote
                            $creditos = $creditos->where('creditos.num_lote', '=', $buscar4);
                        break;
                    }
                    case 'contratos.id': {//Numero de folio
                        $creditos = $creditos->where($criterio, '=', $buscar);
                        break;
                    }
                }
            }

        $creditos = $creditos->orderBy('inst_seleccionadas.cobrado','asc')
                            ->orderBy('inst_seleccionadas.monto_credito','desc');

        return $creditos;
    }

    //Función que retorna los financiamientos bancarios creados para los contratos de venta
    public function indexCreditoSel(Request $request){
        if(!$request->ajax())return redirect('/');
        //Llamada a la función privada que retorna la query principal
        $creditos = $this->getFinanciamientos($request);
        $creditos = $creditos->paginate(10);

        if(sizeof($creditos)){
            //Se recorren los resultados obtenidos
            foreach($creditos as $et=>$contrato){
                //Se buscan los pagares que corresponden al contrato
                $pagos = Pago_contrato::select('num_pago','fecha_pago')
                    ->where('tipo_pagare','=',0)
                    ->where('contrato_id','=',$contrato->folio)
                    ->orderBy('fecha_pago','desc')
                    ->get();
                //Se almacena la fecha del ultimo pagare
                $contrato->pagare=$pagos[0]->fecha_pago;
                //Si se buscan los contratos con firma de escrituras
                if($request->firmado == 1){
                    $expediente = Expediente::findOrFail($contrato->folio);
                    //Se almacena la fecha de firma.
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

    //Función que retorna los depositos de entidades de financiamiento.
    public function indexDepCredito(Request $request){
        if(!$request->ajax())return redirect('/');
        //Query principal
        $depositos = Dep_credito::join('inst_seleccionadas','inst_seleccionadas.id','=','dep_creditos.inst_sel_id')
            ->select('dep_creditos.id','dep_creditos.cant_depo', 'dep_creditos.banco', 'dep_creditos.fecha_deposito',
                    'dep_creditos.inst_sel_id', 'inst_seleccionadas.institucion','inst_seleccionadas.monto_credito',
                    'inst_seleccionadas.cobrado')
            ->where('inst_seleccionadas.id', '=', $request->id)//Busqueda por financiamiento elegido.
            ->get();

        return ['depositos' => $depositos];
    }

    //Función privada que retornara la query necesaria para el historial de depositos
    private function getHistorialDep(Request $request){
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $banco = $request->banco;

        $depositos = Dep_credito::join('inst_seleccionadas','inst_seleccionadas.id','=','dep_creditos.inst_sel_id')
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
        if($banco != '')//Busqueda por institución bancaria
            $depositos = $depositos->where('dep_creditos.banco','like',$banco.'%');
        if($fecha1 != '' && $fecha2 != '') //Busqueda por rango de fecha depositado
            $depositos = $depositos->whereBetween('dep_creditos.fecha_deposito', [$fecha1, $fecha2]);
        //Busqueda por monto depositado
        if($request->bMonto != "") $depositos = $depositos->where('dep_creditos.cant_depo','=',$request->bMonto);

        return $depositos;
    }

    //Función que retorna el historial de depositos por entidades de financiamiento.
    public function historialDepositos(Request $request){
        if(!$request->ajax())return redirect('/');
        //Llamada a la función privada que retorna la query necesaria.
        $depositos = $this->getHistorialDep($request);
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
            'depositos' => $depositos,'fecha1'=>$request->fecha1
        ];
    }

    ////Función que retorna el historial de depositos por entidades de financiamiento en excel
    public function excelHistorialDep(Request $request){
        //Llamada a la función privada que retorna la query necesaria.
        $depositos = $this->getHistorialDep($request);
        $depositos = $depositos->orderBy('dep_creditos.fecha_deposito','desc')->get();
        //Creación y retorno de los resultados en Excel.
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

    //Función para registrar un nuevo deposito institucional.
    public function storeDepositoCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            DB::beginTransaction();
            //Se crea el registro nuevo en la tabla dep_creditos
            $deposito = new Dep_credito();
            $deposito->inst_sel_id = $request->inst_sel_id;
            $deposito->cant_depo = $request->cant_depo;
            $deposito->banco = $request->banco;
            $deposito->fecha_deposito = $request->fecha_deposito;
            //Se actualiza el saldo del crédito en la tabla inst_seleccionadas
            $credito = inst_seleccionada::findOrFail($request->inst_sel_id);
            $credito->cobrado = $credito->cobrado + $request->cant_depo;
            //Se actualiza el saldo de la venta en el Contrato.
            $contrato = Contrato::findOrFail($credito->credito_id);
            $contrato->saldo = round($contrato->saldo - $deposito->cant_depo,2);
            $contrato->save();
            //Se accede al registro del Crédito
            $credit = Credito::findOrFail($credito->credito_id);
            //En caso de ser una venta por alianza
            if($credit->porcentaje_terreno > 0){
                //Se calcula el monto correspondiente por el terreno
                $saldo = $credit->valor_terreno - $credit->saldo_terreno;
                $porcentaje = $credit->porcentaje_terreno/100;
                $monto_terreno = $deposito->cant_depo*$porcentaje;

                if($deposito->monto_terreno > $saldo)
                    $deposito->monto_terreno = $saldo;
                else
                    $deposito->monto_terreno =  $monto_terreno;
            }

            $credito->save();
            $deposito->save();

            /// Aqui se verifica si el deposito va a dirigido a un credito Puente
            if( $request->puente_id != '' && str_contains($request->banco, 'CREDITO PUENTE') ){
                $pagoPuente = new Pago_puente();
                $pagoPuente->credito_puente_id = $request->puente_id;
                $pagoPuente->concepto = 'Pago Hipoteca M.'.$credit->manzana. ' L.'.$credit->num_lote;
                $pagoPuente->fecha = $request->fecha_deposito;
                $pagoPuente->abono = $request->cant_depo;
                $pagoPuente->tipo = 1;
                $pagoPuente->deposito_id = $deposito->id;
                $pagoPuente->pendiente = 1;
                $pagoPuente->save();
            }
            //Se envia notificación del deposito.
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

    //Función para actualizar un deposito institucional
    public function updateDepositoCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try {
            DB::beginTransaction();
            //Se accede al registro del Deposito
            $deposito = Dep_credito::findOrFail($request->dep_id);
            //Se accede al registro del financiamiento
            $credito = inst_seleccionada::findOrFail($request->inst_sel_id);
            //Se actualiza el saldo del financiamiento
            $credito->cobrado = $credito->cobrado - $deposito->cant_depo + $request->cant_depo;
            //Se actualiza el saldo en el contrato
            $contrato = Contrato::findOrFail($credito->credito_id);
            $contrato->saldo = round($contrato->saldo + $deposito->cant_depo - $request->cant_depo,2);
            $contrato->save();

            $credit = Credito::findOrFail($credito->credito_id);
            // Se actualiza el monto correspondiente al terreno en caso de ser venta en alianza
            if($credit->porcentaje_terreno > 0){
                $saldo = $credit->valor_terreno - $credit->saldo_terreno;
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

    //Función que retorna los financiamientos bancarios creados para los contratos de venta en Excel.
    public function excelCobroCredito (Request $request){
        //Llamada a la función privada que retorna la query principal
        $creditos = $this->getFinanciamientos($request);
        $creditos = $creditos->get();

        if(sizeof($creditos)){
            //Se recorren los resultados obtenidos
            foreach($creditos as $et=>$contrato){
                //Se buscan los pagares que corresponden al contrato
                $pagos = Pago_contrato::select('num_pago','fecha_pago')
                    ->where('tipo_pagare','=',0)
                    ->where('contrato_id','=',$contrato->folio)
                    ->orderBy('fecha_pago','desc')
                    ->get();
                //Se almacena la fecha del ultimo pagare
                $contrato->pagare=$pagos[0]->fecha_pago;
            }
        }
        //Creación y retorno de los resultados en Excel
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

    //Función para retornar la query de contratos con saldo a favor.
    private function getDevoluciones(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        //Query principal.
        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
            ->join('contratos','contratos.id','=','creditos.id')
            ->join('lotes','lotes.id','=','creditos.lote_id')
            ->leftJoin('expedientes','expedientes.id','=','contratos.id')
            ->join('personal','personal.id','=','creditos.prospecto_id')
            ->select('contratos.id', 'lotes.credito_puente',
                    'contratos.status_devolucion',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote',
                    'personal.nombre','personal.apellidos',
                    'expedientes.fecha_firma_esc',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                    'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                    'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion',
                    'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
            )
            ->where('contratos.saldo','<',0)//Saldo a favor
            ->where('inst_seleccionadas.elegido', '=', 1);//Financiamiento elegido

            if($buscar != ''){
                switch($criterio){
                    case 'creditos.id':{//Busqueda por folio
                            $creditos = $creditos->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'personal.nombre':{//Busqueda por nombre de cliente
                            $creditos = $creditos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                            //Busqueda por proyecto
                            $creditos = $creditos->where($criterio,'=',$buscar);
                        if($b_etapa != '')//Busqueda por etapa
                            $creditos = $creditos->where('lotes.etapa_id','=',$b_etapa);
                        if($b_manzana != '')//Busqueda por manzana
                            $creditos = $creditos->where('lotes.manzana','=',$b_manzana);
                        if($b_lote != '')//Busqueda por número de lote
                            $creditos = $creditos->where('lotes.num_lote','=',$b_lote);
                        break;
                    }
                }
            }

        if($request->b_empresa != ''){//Busqueda por empresa constructora
            $creditos= $creditos->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        if($request->b_status != ''){//Busqueda por empresa constructora
            $creditos= $creditos->where('contratos.status_devolucion','=',$request->b_status);
        }

        $creditos = $creditos->where('contratos.status','!=',0)//Diferente a cancelado
                            ->orderBy('expedientes.fecha_firma_esc','asc')
                            ->orderBy('inst_seleccionadas.cobrado','asc')
                            ->orderBy('inst_seleccionadas.monto_credito','desc');

        return $creditos;
    }

    //Función que retorna los registros de contratos con saldo a favor
    public function indexDevolucion (Request $request){
        //Llamada a la función privada que retorna la query principal
        $creditos = $this->getDevoluciones($request);
        $creditos = $creditos->paginate(10);

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

    //Función que retorna los registros de contratos con saldo a favor en excel.
    public function excelDevolucion (Request $request){
        //Llamada a la función privada que retorna la query principal
        $creditos = $this->getDevoluciones($request);
        $creditos = $creditos->get();

        //Creación y retorno de los registros obtenidos en excel.
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

    //Función para registrar una devolución para contratos con saldo a favor.
    public function storeDevolucion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            //Se genera el registro para la devolución
            $devolucion = new Dev_credito();
            $devolucion->contrato_id = $request->id;
            $devolucion->devolver = $request->cant_dev;
            $devolucion->fecha = $request->fecha;
            $devolucion->cheque = $request->cheque;
            $devolucion->cuenta = $request->cuenta;
            $devolucion->observaciones = $request->observaciones;
            $devolucion->save();
            //Se accede al registro del contrato y se actualiza el saldo.
            $contrato = Contrato::findOrFail($request->id);
            $contrato->saldo += $request->cant_dev;
            $contrato->saldo = round($contrato->saldo,2);
            if($contrato->saldo < 0 && $contrato->saldo > -0.001)
                $contrato->saldo = 0;
            $contrato->save();
            //Se genera un registro en la tabla gastos administrativos
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

    //Función para finalizar el cobro de un financiamiento.
    public function finalizar(Request $request){
        if(!$request->ajax())return redirect('/');
        $credito = inst_seleccionada::findOrFail($request->id);
        $credito->monto_credito = $credito->cobrado;
        $credito->save();
    }

    //Función para eliminar el registro de un financiamiento.
    public function eliminar(Request $request){
        if(!$request->ajax())return redirect('/');
        $credito = inst_seleccionada::findOrFail($request->id);
        $credito->delete();
    }

    private function getHistDev(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        //Query principal
        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
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
        //Busqueda por empresa constructora
        if($request->b_empresa != '')
            $devoluciones= $devoluciones->where('lotes.emp_constructora','=',$request->b_empresa);
        //Criterios de busqueda
        if($buscar != ''){
            switch ($criterio){
                case 'lotes.fraccionamiento_id':{//Busqueda por proyecto
                        $devoluciones = $devoluciones->where($criterio, '=', $buscar);
                    if($b_etapa != '')//Busqueda por etapa
                        $devoluciones = $devoluciones->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')//Busqueda por manzana
                        $devoluciones = $devoluciones->where('lotes.manzana', '=', $b_manzana);
                    if($b_lote != '')//Busqueda por numero de lote
                        $devoluciones = $devoluciones->where('lotes.num_lote', '=', $b_lote);
                    break;
                }
                case 'creditos.id':{//Busqueda por numero de folio
                        $devoluciones = $devoluciones->where($criterio, '=', $buscar);
                    break;
                }
                case 'personal.nombre':{//Busqueda por nombre del cliente
                        $devoluciones = $devoluciones->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
            }
        }
        return $devoluciones;
    }

    //Funcion para retornar el historial de devoluciones generadas.
    public function indexHistorialDev(Request $request){
        if(!$request->ajax())return redirect('/');
        //Llamada a la función privada que retorna la query necesaria
        $devoluciones = $this->getHistDev($request);
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

    //Funcion para retornar el historial de devoluciones generadas en excel
    public function excelHistDev(Request $request){
        //Llamada a la función privada que retorna la query necesaria
        $devoluciones = $this->getHistDev($request);
        $devoluciones = $devoluciones->orderBy('id', 'desc')->paginate(8);
        //Creación y retorno de los resultados en excel
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
