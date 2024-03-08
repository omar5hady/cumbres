<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Contrato;
use App\Devolucion;
use App\Gasto_admin;
use App\Dev_concretania;
use App\Dev_virtual;
use App\Pago_contrato;
use App\Deposito_conc;
use App\Deposito_gcc;
use App\Deposito;
use App\Cuenta;
use App\Credito;
use App\Obs_devolucionCanc;
use Carbon\Carbon;
use NumerosEnLetras;
use Excel;
use Auth;
use DB;

/*  Controlador para devoluciones por cancelación de contrato.  */
class DevolucionController extends Controller
{
    // Función que retorna los contratos cancelados.
    public function indexCancelaciones(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        // Llamada a la función privada para obtener la query necesaria.
        $contratos = $this->getCancelados($request);

        $contratos = $contratos
            ->where('contratos.status', '=', '0') // Contratos cancelados
            ->where('contratos.devolucion', '!=', '2') // Contrato con devolución pendiente.
            ->orderBy('contratos.saldo','asc')
            ->orderBy('fecha_status', 'asc')->paginate(50);

            if(sizeof($contratos)){ // Se recorre el arreglo de contratos
                foreach ($contratos as $index => $contrato) {
                    $contrato->totalDep = 0; // Total depositado
                    // Se obtienen la suma de depositos del contrato.
                    $depositos_pagado = Pago_contrato::join('depositos','pagos_contratos.id','=','depositos.pago_id')
                    ->select(DB::raw("SUM(depositos.cant_depo) as pagado"))
                    ->where('pagos_contratos.contrato_id','=',$contrato->id)
                    ->first();

                    if($depositos_pagado->pagado != NULL){
                        $contrato->sumaPagares = round($depositos_pagado->pagado,2);
                        $contrato->sumaRestante = 0;
                    }
                    if($contrato->descuento == NULL)
                        $contrato->descuento = 0;

                    $contrato->devolver = $contrato->sumaPagares + $contrato->descuento - $contrato->sumGastos;
                    $contrato->devolver = round($contrato->devolver,2);
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
            ], 'contratos' => $contratos//, 'contadorContrato' => $contadorContratos
        ];
    }

    // Función privada que retorna las cuentas bancarias de cumbres.
    private function getCuentas(){
        $cuentas = Cuenta::select('num_cuenta','banco')->where('empresa','=','Grupo Constructor Cumbres')->get();
        $arrayCuentas = [];

        foreach($cuentas as $index => $cuenta){
            array_push($arrayCuentas,$cuenta->num_cuenta.'-'.$cuenta->banco);
        }

        return $arrayCuentas;
    }

    public function cambiarStatus(Request $request){
        $contrato = Contrato::findOrFail($request->id);
        $contrato->status_devolucion = $request->status;
        $contrato->save();

        $observacion = new Obs_devolucionCanc();
        $observacion->contrato_id = $request->id;
        $observacion->observacion = 'Se ha actualizado el estatus de la solicitud a: '.$request->status;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    // Función que retorna los contratos con saldo pendiente de terreno para devolucion virtual
    public function cancelacionesVirtuales(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        // Llamada a la funcion privada que devuelve las cuentas bancarias de cumbres
        $cuentas = $this->getCuentas();
        // Query para obtener los contratos cancelados
        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('personal as v', 'creditos.vendedor_id', 'v.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.precio_base',
                'creditos.precio_venta',
                'creditos.valor_terreno',
                'creditos.saldo_terreno',
                'creditos.dev_terreno',
                'creditos.fraccionamiento as proyecto',
                'creditos.lote_id',

                'personal.nombre',
                'personal.apellidos',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),

                'v.nombre as vendedor_nombre',
                'v.apellidos as vendedor_apellidos',
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),

                'contratos.status',
                'contratos.fecha_status'
        );

        if ($buscar != '') {
            switch ($criterio){
                case 'lotes.fraccionamiento_id':{
                    $contratos = $contratos->where($criterio, '=', $buscar);

                    if($b_etapa != '')
                        $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')
                        $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                    if($b_lote != '')
                        $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);

                    break;
                }
                case 'creditos.id':{
                    $contratos = $contratos
                    ->where($criterio, '=', $buscar);
                    break;
                }
                case 'personal.nombre':{
                    $contratos = $contratos
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
            }
        }

        $contratos = $contratos
            ->where('contratos.status', '=', '0')// Contrato cancelados
            ->where('creditos.dev_terreno', '!=', '2')// Sin devolución
            ->where('lotes.emp_constructora','=','CONCRETANIA')// Alianza
            ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')//Alianza
            ->orderBy('fecha_status', 'asc')->get();

            if(sizeof($contratos)){
                foreach ($contratos as $index => $contrato) {
                    $contrato->depositos = 0;
                    $contrato->devolucionTotal = 0;
                    $contrato->sumaDev = 0;

                    $contrato->gccTransf = 0;
                    $contrato->concTransf = 0;
                    // Devoluciones que han salido de cuentas cumbres
                    $devoluciones = Devolucion::whereIn('devoluciones.cuenta',$cuentas)
                                    ->where('devoluciones.contrato_id','=',$contrato->id)
                                    ->get();
                    // Devoluciones virtuales para alianza
                    $dev_virtuales = Dev_virtual::where('contrato_id','=',$contrato->id)
                                    ->get();
                    // Depositos pendientes por ingresar a cuenta cumbres
                    $depositos_pagado = Pago_contrato::join('depositos','pagos_contratos.id','=','depositos.pago_id')
                        ->select(DB::raw("SUM(depositos.monto_terreno) as pagado"))
                        ->where('pagos_contratos.contrato_id','=',$contrato->id)
                        ->where('depositos.fecha_ingreso_concretania','!=',NULL)
                        ->where('depositos.lote_id','=',$contrato->lote_id)
                        ->first();
                    // Depositos reubicados
                    $transfGCC = Deposito_gcc::select(DB::raw("SUM(depositos_gcc.monto) as pagado"))
                        ->where('depositos_gcc.contrato_id','=',$contrato->id)
                        ->where('depositos_gcc.lote_id','=',$contrato->lote_id)
                        ->first();
                    // Depositos reubicados
                    $transfConc = Deposito_conc::select(DB::raw("SUM(depositos_conc.monto) as pagado"))
                        ->where('depositos_conc.contrato_id','=',$contrato->id)
                        ->where('depositos_conc.lote_id','=',$contrato->lote_id)
                        ->where('depositos_conc.devolucion','=',1)
                        ->first();
                    // Sumatoria de devoluciones virtuales.
                    if(sizeof($dev_virtuales)){
                        $contrato->dev_virtuales = $dev_virtuales;
                        foreach ($dev_virtuales as $key => $dev) {
                            $contrato->sumaDev += $dev->monto;
                        }
                    }
                    // Sumatoria de devoluciones bancarias.
                    if(sizeof($devoluciones)){
                        $contrato->devoluciones = $devoluciones;
                        foreach ($devoluciones as $key => $dev) {
                            $contrato->devolucionTotal += $dev->devolver;
                        }
                    }

                    if($depositos_pagado->pagado != NULL){
                        $contrato->depositos = $depositos_pagado->pagado;
                    }

                    if($transfGCC->pagado != NULL){
                        $contrato->gccTransf = 0;
                    }

                    if($transfConc->pagado != NULL){
                        $contrato->concTransf = 0;
                    }
                }
            }

        return [
            'contratos' => $contratos//, 'contadorContrato' => $contadorContratos
        ];
    }

    // Función para registrar la devolución.
    public function storeDevolucion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            // Conteo de devoluciones.
            $contDev = Devolucion::select('id')->where('contrato_id','=',$request->id)->count();
            // Se crea la devolución
            $devolucion = new Devolucion();
            $devolucion->contrato_id = $request->id;
            $devolucion->devolver = $request->cant_dev;
            $devolucion->fecha = $request->fecha;
            $devolucion->cheque = $request->cheque;
            $devolucion->cuenta = $request->cuenta;
            $devolucion->observaciones = $request->observaciones;
            $devolucion->save();
            // Acceso al registro del credito y del contrato.
            $credito = Credito::findOrFail($request->id);
            $contrato = Contrato::findOrFail($request->id);
            // Devolución en 1 indica que se a abonado para la devolicion.
            $contrato->devolucion = 1;
            if($request->devolver == $request->cant_dev) // si el saldo quedo liquidado
                $contrato->devolucion = 2; // estatus 2 indica que no hay saldo pendiente
            if($contDev==0){ // Si es la primera devolicion
                $contrato->saldo =  round($contrato->saldo - $credito->precio_venta,2); // se calcula la devolución pendiente.
            }
            $contrato->saldo += $request->cant_dev;
            $contrato->saldo = round($contrato->saldo,2);
            $contrato->save();
            // Registro de gasto administrativo como devolución por cancelación.
            $gastos = new Gasto_admin();
            $gastos->contrato_id = $request->id;
            $gastos->concepto = "Devolución por cancelación";
            $gastos->costo = $request->cant_dev;
            $gastos->observacion = $request->observaciones;
            $gastos->fecha = $request->fecha;
            $gastos->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    // Función para registrar una devolucion virtual para saldo pendiente de terreno en alianza.
    public function storeDevolucionVirtual(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            // Se registra devolución virtual.
            $devolucion = new Dev_virtual();
            $devolucion->contrato_id = $request->id;
            $devolucion->monto = $request->monto;
            $devolucion->fecha = $request->fecha;
            $devolucion->cheque = $request->cheque;
            $devolucion->cuenta = $request->cuenta;
            $devolucion->observaciones = $request->observaciones;
            $devolucion->save();
            // Sumatoria de devoluciones virtuales.
            $sum = Dev_virtual::select(DB::raw("SUM(dev_virtuales.monto) as totalMonto"))->where('contrato_id','=',$request->id)->first();
            $credito = Credito::findOrFail($request->id);
            if($credito->saldo_terreno == $sum->totalMonto)
                $credito->dev_terreno = 2;
            else
                $credito->dev_terreno = 1;
            $credito->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    // Función para retornar las devoluciones virtuales por contrato.
    public function indexDevolucionesVirtuales(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        // Query para obtener las devoluciones virtuales
        $devoluciones = Dev_virtual::join('creditos', 'dev_virtuales.contrato_id', '=', 'creditos.id')
                        ->join('contratos','creditos.id','=','contratos.id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->select('dev_virtuales.*',
                            'creditos.etapa',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.num_lote',
                            'creditos.etapa',
                            'creditos.manzana',
                            'contratos.fecha_status',

                            'personal.nombre',
                            'personal.apellidos'
                        );

        if ($buscar != '') {
            switch ($criterio){// Filtros de busqueda
                case 'lotes.fraccionamiento_id':{ // Busqueda por proyecto
                    $devoluciones = $devoluciones
                        ->where($criterio, '=', $buscar);
                    if($b_etapa != '')
                        $devoluciones = $devoluciones->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')
                        $devoluciones = $devoluciones->where('lotes.manzana', '=', $b_manzana);
                    if($b_lote != '')
                        $devoluciones = $devoluciones->where('lotes.num_lote', '=', $b_lote);
                    break;
                }
                case 'creditos.id':{ // Busqueda por # de contrato
                    $devoluciones = $devoluciones
                    ->where($criterio, '=', $buscar);
                    break;
                }
                case 'personal.nombre':{ // Busqued por cliente.
                    $devoluciones = $devoluciones
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
            }
        }

        $devoluciones = $devoluciones
            ->orderBy('id', 'desc')->paginate(10);

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

    // Función para retornar las devoluciones por cancelación por contrato.
    public function indexDevoluciones(Request $request){
        if(!$request->ajax())return redirect('/');
        // Llamada a la función privada que retorna la query necesaria.
        $devoluciones = $this->getHistorialDev($request);

        $devoluciones = $devoluciones
            ->where('contratos.status', '=', '0')
            ->where('contratos.devolucion', '=', '2')
            ->orderBy('id', 'desc')->paginate(8);

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

    // Función que retorna los contratos cancelados a excel.
    public function excelDevoluciones(Request $request){
        // Llamada a la función privada para obtener la query necesaria.
        $contratos = $this->getCancelados($request);

        $contratos = $contratos
            ->where('contratos.status', '=', '0') // Contratos cancelados
            ->where('contratos.devolucion', '!=', '2') // Contrato con devolución pendiente.
            ->orderBy('contratos.saldo','asc')
            ->orderBy('fecha_status', 'asc')->get();

            if(sizeof($contratos)){ // Se recorre el arreglo de contratos
                foreach ($contratos as $index => $contrato) {
                    $contrato->totalDep = 0; // Total depositado
                    // Se obtienen la suma de depositos del contrato.
                    $depositos_pagado = Pago_contrato::join('depositos','pagos_contratos.id','=','depositos.pago_id')
                    ->select(DB::raw("SUM(depositos.cant_depo) as pagado"))
                    ->where('pagos_contratos.contrato_id','=',$contrato->id)
                    ->first();

                    if($depositos_pagado->pagado != NULL){
                        $contrato->sumaPagares = round($depositos_pagado->pagado,2);
                        $contrato->sumaRestante = 0;
                    }
                    if($contrato->descuento == NULL)
                        $contrato->descuento = 0;

                    $contrato->devolver = $contrato->sumaPagares + $contrato->descuento - $contrato->sumGastos;
                    $contrato->devolver = round($contrato->devolver,2);
                }
            }
        // Creación y retorno del excel.
        return Excel::create('Devoluciones pendientes por cancelación', function($excel) use ($contratos){
            $excel->sheet('cancelaciones', function($sheet) use ($contratos){

                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto', 'Etapa', 'Manzana',
                    '# Lote','Depositos', 'Pendiente a devolver', 'Fecha cancelación'
                ]);

                $sheet->setColumnFormat(array(
                    'G' => '$#,##0.00',
                    'H' => '$#,##0.00',
                ));


                $sheet->cells('A1:I1', function ($cells) {
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
                $cont1=2;

                foreach($contratos as $index => $devolucion) {


                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($devolucion->fecha_status);
                    $devolucion->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');

                    $depositos = $devolucion->sumaPagares - $devolucion->sumaRestante;
                    $pendiente = $devolucion->sumaPagares - $devolucion->sumaRestante -  $devolucion->sumGastos;

                    if(($devolucion->devolver) > 0){
                        $sheet->row($cont1, [
                            $devolucion->id,
                            $devolucion->nombre_cliente,
                            $devolucion->proyecto,
                            $devolucion->etapa,
                            $devolucion->manzana,
                            $devolucion->num_lote,
                            $devolucion->sumaPagares,
                            $devolucion->devolver,
                            $devolucion->fecha_status

                        ]);
                        $cont1++;
                        $cont++;
                    }
                }
                $num='A1:I' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        )->download('xls');

    }

    // Función privada que retorna la query con contratos cancelados para las devoluciones.
    private function getCancelados(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        // Query para obtener los contratos
        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->leftJoin('expedientes','contratos.id','=','expedientes.id')
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

                'expedientes.descuento',
                'expedientes.obs_descuento',

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
                'contratos.saldo',
                'contratos.status_devolucion',

                DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                            WHERE devoluciones.contrato_id = contratos.id
                            GROUP BY devoluciones.contrato_id) as sumaDev"),

                DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                    WHERE gastos_admin.contrato_id = contratos.id
                    GROUP BY gastos_admin.contrato_id) as sumGastos")
        );
        // Filtro para empresa constructora
        if($request->b_empresa != ''){
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        if($request->b_status != ''){
            $contratos= $contratos->where('contratos.status_devolucion','=',$request->b_status);
        }

        if ($buscar != '') { // Filtros
            switch ($criterio){
                case 'lotes.fraccionamiento_id':{ // Busqueda por proyecto
                    $contratos = $contratos->where($criterio, '=', $buscar);
                    if($b_etapa != '') // Etapa
                        $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '') // Manzana
                        $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                    if($b_lote != '') // Lote
                        $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
                    break;
                }
                case 'creditos.id':{        // Busqeda por # contrato
                    $contratos = $contratos
                    ->where($criterio, '=', $buscar);
                    break;
                }
                case 'personal.nombre':{    // Busqueda por cliente.
                    $contratos = $contratos
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
            }
        }
        return $contratos;
    }

    // Función privada para obtener la query con los las devoluciones registradas.
    private function getHistorialDev(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        // Query para retornar las devoluciones por cancelación creados
        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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

                'devoluciones.fecha',
                'devoluciones.cheque',
                'devoluciones.cuenta',
                'devoluciones.observaciones',
                'devoluciones.devolver',


                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                            WHERE pagos_contratos.contrato_id = contratos.id
                            GROUP BY pagos_contratos.contrato_id) as sumaPagares"),

                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                            WHERE pagos_contratos.contrato_id = contratos.id
                            GROUP BY pagos_contratos.contrato_id) as sumaRestante")
        );
        if($request->b_empresa != '') // Busqueda por empresa constructora
            $devoluciones= $devoluciones->where('lotes.emp_constructora','=',$request->b_empresa);

        if ($buscar != '') {
            switch ($criterio){
                case 'lotes.fraccionamiento_id':{// Busqueda por proyecto
                    $devoluciones = $devoluciones
                        ->where($criterio, '=', $buscar);
                    if($b_etapa != '')// Etapa
                        $devoluciones = $devoluciones->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')// Manzana
                        $devoluciones = $devoluciones->where('lotes.manzana', '=', $b_manzana);
                    if($b_lote != '')// Lote
                        $devoluciones = $devoluciones->where('lotes.num_lote', '=', $b_lote);
                    break;
                }
                case 'creditos.id':{// Busqueda por #Contrato
                    $devoluciones = $devoluciones->where($criterio, '=', $buscar);
                    break;
                }
                case 'personal.nombre':{ // Busqueda por cliente.
                    $devoluciones = $devoluciones
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
            }
        }

        return $devoluciones;
    }

    // Función para retornar las devoluciones por cancelación por contrato en excel.
    public function excelHistDev(Request $request){
       // Llamada a la función privada que retorna la query necesaria.
        $devoluciones = $this->getHistorialDev($request);
        $devoluciones = $devoluciones
            ->where('contratos.status', '=', '0') // Contratos cancelados
            ->where('contratos.devolucion', '=', '2') // Contratos con devolucion completada
            ->orderBy('id', 'desc')->get();
        // Retorno de la devoluciones en excel.
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

    private function getData(Request $request){
        $data = Contrato::join('creditos as cr', 'cr.id','=','contratos.id')
            ->join('inst_seleccionadas as i', 'i.credito_id','=','cr.id')
            ->join('lotes as l', 'l.id', 'cr.lote_id')
            ->join('fraccionamientos as f', 'f.id', '=', 'l.fraccionamiento_id')
            ->join('etapas as e', 'e.id', '=', 'l.etapa_id')
            ->join('personal as cl', 'cl.id', '=', 'cr.prospecto_id')
            ->join('personal as g', 'g.id', '=', 'f.gerente_id')
            ->join('personal as a', 'a.id', '=', 'cr.vendedor_id')
            ->select(
                'contratos.id', 'contratos.saldo', 'l.emp_constructora',
                'contratos.status',
                'l.num_lote', 'l.sublote', 'l.manzana', 'e.num_etapa as etapa',
                'f.nombre as proyecto',
                'cl.nombre as cl_nombre', 'cl.apellidos as cl_apellidos',
                'g.nombre as g_nombre', 'g.apellidos as g_apellidos',
                'a.nombre as a_nombre', 'a.apellidos as a_apellidos',
                'contratos.fecha','contratos.fecha_status', 'i.tipo_credito'
            )
            ->where('i.elegido','=',1)
            ->where('contratos.id', '=', $request->id)
            ->first();

            if($data){
                $data->monto = number_format((float)$data->saldo*-1, 2, '.', ',');
                $data->saldo = NumerosEnLetras::convertir($data->saldo*-1, 'Pesos', true, 'Centavos');
                $data->hoy = Carbon::now()->formatLocalized('%d de %B de %Y');

                $fecha = new Carbon($data->fecha);
                $data->fecha = $fecha->formatLocalized('%d de %B de %Y');

                $data->depositos = Deposito::join('pagos_contratos as p', 'p.id', '=', 'depositos.pago_id')
                    ->select(
                        DB::raw("SUM(depositos.cant_depo) as depositado")
                    )->where('p.contrato_id','=',$data->id)
                    ->first();

                if($data->depositos){
                    $data->monto_deposito = number_format((float)$data->depositos->depositado, 2, '.', ',');
                    $data->depositado = NumerosEnLetras::convertir($data->depositos->depositado, 'Pesos', true, 'Centavos');
                }
            }

            return $data;
    }

    public function printSolicCancelacion(Request $request){
        $data = $this->getData($request);
        $pdf = \PDF::loadview('pdf.devolucion.solicCancelacion', ['data'=> $data]);

        return $pdf->stream('Solicitud_cancelacion.pdf');
    }

    public function printSolicDevolucion(Request $request){
        $data = $this->getData($request);
        $pdf = \PDF::loadview('pdf.devolucion.solicDevolucion', ['data'=> $data ,'tipo' => $request->tipo]);

        return $pdf->stream('Solicitud_devolucion.pdf');
    }
}
