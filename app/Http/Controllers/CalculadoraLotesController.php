<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\datos_calc_lotes;
use App\lotes_individuales;
use App\Fraccionamiento;
use App\Cotizacion_lotes;
use App\Pagos_lotes;
use App\Cliente;
use Carbon\Carbon;
use App\User;
use App\Personal;
use App\Lote;
use App\Credito;
use App\Contrato;
use App\inst_seleccionada;
use App\Empresa;
use App\Pago_contrato;
use Auth;
use Illuminate\Support\Facades\DB;

use App\Modelo;

// Controlador para cotizador de venta de Lotes
class CalculadoraLotesController extends Controller
{
    //Funcion para retornar los porcentajes de interes segun corresponde el tiempo de financiamiento.
    public function listarPorcentaje(){
        $lista = datos_calc_lotes::all();
        return $lista;
    }

    // Función para modificar el porcentaje de interes.
    public function editaPorcentaje(Request $request){
        if (!$request->ajax()) return redirect('/');

        $element = datos_calc_lotes::findOrFail($request->id);

        $element->valor = $request->valor;
        //$element->descripcion = $request->descripcion;
        $element->save();
    }

    // Función que retorna el costo por m2 por etapa.
    public function listarPrecios(){
        $lotes = lotes_individuales::join('etapas', 'lotes_individuales.etapa_id', '=', 'etapas.id')
        ->join('fraccionamientos', 'etapas.fraccionamiento_id', 'fraccionamientos.id')
            ->select('lotes_individuales.id', 'lotes_individuales.etapa_id', 'lotes_individuales.costom2',
            'etapas.num_etapa', 'fraccionamientos.nombre')
        ->get();
        return $lotes;
    }

    // Función para cambiar el valor de venta del terreno
    public function editEnterPrice(Request $request){
        if (!$request->ajax()) return redirect('/');

        // Primero se actualiza el precio por etapa
        $lote = lotes_individuales::findOrFail($request->id);
        $lote->costom2 = $request->costom2;
        $lote->save();

        /*  Llamado a la función que modificara el precio a todos los lotes que
            en venta que correspondan a esa etapa y esten habilitados.
        */
        $this->actPrecioLotes($lote->etapa_id, $request->costom2);
    }

    // Función para crear un nuevo costo de venta de tereno para etapa.
    public function addWindowPrice(Request $request){
        if (!$request->ajax()) return redirect('/');
        $lote = new lotes_individuales();

        $lote->etapa_id = $request->etapa_id;
        $lote->costom2 = $request->costom2;
        $lote->save();
    }

    // Función para actualizar el valor de venta de terreno.
    public function editWindowPrice(Request $request){
        if (!$request->ajax()) return redirect('/');

        $lote = lotes_individuales::findOrFail($request->id);

        $lote->etapa_id = $request->etapa_id;
        $lote->costom2 = $request->costom2;
        $lote->save();

         /*  Llamado a la función que modificara el precio a todos los lotes que
            en venta que correspondan a esa etapa y esten habilitados.
        */
        $this->actPrecioLotes($request->etapa_id, $request->costom2);
    }

    // Funcion privada para actualizar el costo de venta del terreno para todos los lotes de una etapa.
    private function actPrecioLotes($etapa, $costom2){
        $lotes = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
            ->select('lotes.id')->where('lotes.etapa_id','=',$etapa)
            ->where('lotes.contrato','=',0)
            ->where('modelos.nombre','=','Terreno')->get();

        if(sizeof($lotes)){
            foreach ($lotes as $index => $lot) {
                $l= Lote::findOrFail($lot->id);
                $l->precio_base = $costom2 * $l->terreno;
                $l->save();
            }
        }
    }

    // Función para retornar los Proyectos con disponibilidad de venta de terrenos.
    public function selectFraccionamientoLotes(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $fraccionamientos = lotes_individuales::join('etapas', 'lotes_individuales.etapa_id', '=', 'etapas.id')
            ->join('fraccionamientos', 'etapas.fraccionamiento_id', 'fraccionamientos.id')
            ->select('fraccionamientos.nombre','fraccionamientos.id')
            ->where('fraccionamientos.id','!=','1')
            ->orderBy('fraccionamientos.nombre','asc')
            ->groupBy('fraccionamientos.id')
        ->get();

        return['fraccionamientos' => $fraccionamientos];
    }

    // Función para retornar las etapas con disponibilidad de venta de terrenos tomando como criterio de busqueda el proyecto seleccionado.
    public function selectEtapa_proyectoLotes(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;

        $etapas = lotes_individuales::join('etapas', 'lotes_individuales.etapa_id', '=', 'etapas.id')
            ->select('etapas.num_etapa','etapas.id')
            ->where('etapas.fraccionamiento_id', '=', $buscar )
            ->groupBy('etapas.id')
        ->get();

        return['etapas' => $etapas];
    }

    /*
        Función para retornar los lotes con disponibilidad de venta de terrenos.
        Se toma como criterio los lotes habilitados para venta,
        Sin contrato y que no esten apartados.
    */
    public function lotesDisponiblesLotes (Request $request)
    {
        if(!$request->ajax())return redirect('/');

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')

            ->leftJoin('apartados','lotes.id','apartados.lote_id')
            ->leftJoin('clientes','apartados.cliente_id','clientes.id')
            ->leftJoin('vendedores','apartados.vendedor_id','vendedores.id')
            ->leftJoin('personal','clientes.id','personal.id')
            ->leftJoin('personal as v','vendedores.id','v.id')

            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance','lotes.extra','lotes.extra_ext',
                        'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste','lotes.excedente_terreno','lotes.apartado','lotes.obra_extra','lotes.fecha_termino_ventas',
                        'personal.nombre as c_nombre', 'personal.apellidos as c_apellidos', 'lotes.emp_constructora', 'lotes.emp_terreno',
                        'v.nombre as v_nombre', 'apartados.fecha_apartado','lotes.regimen_condom'
                    );

        $lotes = $query->where('modelos.nombre', '=', 'Terreno')
            ->where('etapas.id', '=', $request->etapaId)
            ->where('lotes.manzana','like','%'.$request->manzana.'%')

            ->where('lotes.habilitado','=',1)
            ->where('lotes.contrato','=',0)
            ->where('lotes.apartado','=',0)

            ->where('lotes.casa_muestra', '=', 0)
        ->where('lotes.lote_comercial', '=', 0);
            //->where('lotes.regimen_condom', '=', 0)

        //Buscar manzana
        // if($request->manzana != ''){
        //     $lotes = $lotes->where('lotes.manzana','like', '%'.$request->manzana.'%');
        // }

        $lotes = $lotes->orderBy('fraccionamientos.nombre','DESC')
            ->orderBy('etapas.num_etapa','ASC')
            ->orderBy('lotes.manzana','ASC')
            ->orderBy('lotes.num_lote','ASC')
        ->get();
        //->paginate(10);

        return $lotes;

    }

    // Función para crear la cotización del terreno
    public function guardaCotizacion(Request $request){
        if (!$request->ajax()) return redirect('/');

        // Arreglo de pagos generados
        $arrayPagos = $request->pago;

        $cotizacion = new Cotizacion_lotes();

        $cotizacion->cliente_id = $request->idCliente;
        $cotizacion->lotes_id = $request->idLote;
        $cotizacion->valor_venta = $request->valor_venta;
        $cotizacion->valor_descuento = $request->valor_descuento;
        $cotizacion->fecha = $request->fecha;
        $cotizacion->mensualidades = $request->mensualidades;
        $cotizacion->interes = $request->interes;
        $cotizacion->save();

        // Se rrecorre el arrego de pagos para almacenar cada uno de los registros.
        foreach($arrayPagos as $pago){
            if($pago['cantidad'] != 0){
                $newPago = new Pagos_lotes();

                $newPago->cotizacion_lotes_id = $cotizacion->id;
                $newPago->folio = $pago['folio'];
                $newPago->pago = $pago['pago'];
                $newPago->cantidad = $pago['cantidad'];
                $newPago->fecha = $pago['fecha'];
                $newPago->descuento = $pago['descuento'];
                $newPago->dias = $pago['dias'];
                $newPago->descuento_porc = $pago['interesesPor'];
                $newPago->interes_monto = $pago['interesMont'];
                $newPago->total_a_pagar = $pago['totalAPagar'];
                $newPago->saldo = $pago['saldo'];
                $newPago->save();
            }
        }

        return $cotizacion->id;
    }

    // Función para crear la vista en pdf de la cotización
    public function generaPdf(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $cotizacion = Cotizacion_lotes::join('clientes', 'cotizacion_lotes.cliente_id', '=', 'clientes.id')
            ->join('personal', 'clientes.id', '=', 'personal.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as p_ventas', 'vendedores.id', '=', 'p_ventas.id')
            ->join('lotes', 'cotizacion_lotes.lotes_id', '=', 'lotes.id')
            ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
            ->join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->select(
                'cotizacion_lotes.id', 'cotizacion_lotes.cliente_id', 'cotizacion_lotes.lotes_id',
                'cotizacion_lotes.valor_venta', 'cotizacion_lotes.valor_descuento',
                'cotizacion_lotes.created_at', 'cotizacion_lotes.updated_at', 'cotizacion_lotes.fecha',
                'cotizacion_lotes.mensualidades','cotizacion_lotes.interes',

                'personal.apellidos', 'personal.nombre',

                'clientes.id as cliente_personal_id',

                'lotes.num_lote',
                'lotes.sublote',
                'lotes.manzana',
                'lotes.terreno as terreno_m2',
                'etapas.num_etapa',
                'fraccionamientos.nombre as fraccionamiento',

                'p_ventas.apellidos as v_apellidos',
                'p_ventas.nombre as v_nombre'
            )
            ->where('cotizacion_lotes.id', '=', $request->idCotizacion)
        ->first();

        $fecha= new Carbon($cotizacion->fecha);
        $cotizacion->fecha = $fecha->formatLocalized('%d/%m/%Y');

        // Se calcula el costo por m2 de la cotizacion
        $cotizacion->m2 = $cotizacion->valor_venta/$cotizacion->terreno_m2;
        // Total a pagar descontando descuento generado si lo hay.
        $cotizacion->total_pagar = $cotizacion->valor_venta-$cotizacion->valor_descuento;

        $cotizacion->valor_descuento = number_format((float)$cotizacion->valor_descuento, 2, '.', ',');
        $cotizacion->valor_venta = number_format((float)$cotizacion->valor_venta, 2, '.', ',');
        $cotizacion->total_pagar = number_format((float)$cotizacion->total_pagar, 2, '.', ',');
        $cotizacion->m2 = number_format((float)$cotizacion->m2, 2, '.', ',');

        $pago = Pagos_lotes::where('cotizacion_lotes_id', '=', $cotizacion->id)
            ->orderBy('folio')
        ->get();

        if(sizeof($pago)){
            $totalP1 = $totalP2 = $totalP3 = $totalP4 = 0;
            foreach ($pago as $index => $p) {
                $totalP1+= $p->cantidad;
                $totalP2+= $p->descuento;
                $totalP3+= $p->interes_monto;
                $totalP4+= $p->total_a_pagar;

                $p->cantidad = $p->cantidad + $p->descuento;
                $p->cantidad = number_format((float)$p->cantidad, 2, '.', ',');
                $p->descuento = number_format((float)$p->descuento, 2, '.', ',');
                $p->interes_monto = number_format((float)$p->interes_monto, 2, '.', ',');
                $p->total_a_pagar = number_format((float)$p->total_a_pagar, 2, '.', ',');
                $p->saldo = number_format((float)$p->saldo, 2, '.', ',');

                $fecha_pago = new Carbon($p->fecha);
                $p->fecha = $fecha_pago->formatLocalized('%d/%m/%Y');
            }
            $totalP1 = number_format((float)$totalP1, 2, '.', ',');
            $totalP2 = number_format((float)$totalP2, 2, '.', ',');
            $totalP3 = number_format((float)$totalP3, 2, '.', ',');
            $totalP4 = number_format((float)$totalP4, 2, '.', ',');
        }

        //Retorno de la vista en pdf
        $pdf = \PDF::loadview('pdf.calculadoraLotes.cotizacionLote', ['cotizacion' => $cotizacion, 'pago' => $pago,
            'totalP1' => $totalP1,'totalP2' => $totalP2,'totalP3' => $totalP3,'totalP4' => $totalP4,
        ]);
        return $pdf->stream('cotizacion_lote_con_servicios.pdf');
    }

    // Función para obtener las cotizaciones creadas.
    /*
        Los estatus posibles en una cotizacion son:
            0 = Pendiente
            1 = Aprobado
            2 = Cancelada
    */
    public function getClientes(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $queryGen = Cliente::join('personal','clientes.id','=','personal.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('cotizacion_lotes', 'clientes.id', '=', 'cotizacion_lotes.cliente_id')
            //->join('pagos_lotes', 'cotizacion_lotes.id', '=', 'pagos_lotes.cotizacion_lotes_id')
            ->join('lotes', 'cotizacion_lotes.lotes_id', '=', 'lotes.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->leftJoin('contratos','cotizacion_lotes.num_contrato','=','contratos.id')
            ->select(
                'clientes.coacreditado', 'clientes.lugar_nacimiento_coa', 'clientes.nombre_coa',
                'clientes.apellidos_coa', 'clientes.f_nacimiento_coa','clientes.direccion_coa',
                'clientes.ciudad_coa', 'clientes.colonia_coa', 'clientes.estado_coa', 'clientes.cp_coa',
                'clientes.telefono_coa', 'clientes.celular_coa','clientes.empresa_coa',

                'personal.id as personalId','personal.nombre','personal.apellidos',
                'personal.celular', 'personal.email', 'personal.telefono',
                'personal.direccion','personal.colonia','personal.cp', 'personal.email',
                'personal.rfc', 'personal.empresa_id',
                'clientes.nss', 'clientes.curp','clientes.lugar_nacimiento','clientes.estado',
                'clientes.ciudad','clientes.puesto',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"),

                'cotizacion_lotes.id', 'cotizacion_lotes.cliente_id', 'cotizacion_lotes.lotes_id',
                'cotizacion_lotes.valor_venta', 'cotizacion_lotes.valor_descuento',
                'cotizacion_lotes.created_at', 'cotizacion_lotes.fecha',
                'cotizacion_lotes.mensualidades', 'cotizacion_lotes.estatus',
                'cotizacion_lotes.interes',

                'contratos.id as folio', 'contratos.status as contratoStatus',

                'etapas.num_etapa',
                'lotes.num_lote',
                'lotes.sublote',
                'lotes.manzana',
                'fraccionamientos.nombre as fraccionamiento'
            );



        //nombre
        if($request->b_cliente != '')
            $personas = $queryGen->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $request->b_cliente . '%');
        //fecha
        if($request->b_fecha != '')
            $personas = $queryGen->whereDate('cotizacion_lotes.fecha', $request->b_fecha);
        //proyecto
        if($request->r_proyecto != '')
            $personas = $queryGen->where('fraccionamientos.id', '=', $request->r_proyecto);
        //etapa
        if($request->r_etapa != '')
            $personas = $queryGen->where('etapas.id', '=', $request->r_etapa);

        //manzana
        if($request->r_manzana != '')
            $personas = $queryGen->where('lotes.manzana', 'like', '%'.$request->r_manzana.'%');
        //lote
        if($request->b_lote != '')
            $personas = $queryGen->where('lotes.id', '=', $request->b_lote);
        //mensualidades
        if($request->b_mensualidad != '')
            $personas = $queryGen->where('cotizacion_lotes.mensualidades', '=', $request->b_mensualidad);

        if(
            $request->b_cliente == ''&&
            $request->b_fecha == ''&&
            $request->r_proyecto == ''&&
            $request->r_etapa == ''&&
            $request->b_lote == ''&&
            $request->b_mensualidad == ''
        ){$personas = $queryGen->where('cotizacion_lotes.estatus', '=', 0);}

        if(Auth::user()->rol_id == 2){
            $personas = $personas->where('clientes.vendedor_id','=',Auth::user()->id);
        }

        $personas = $personas->orderBy('personal.nombre', 'asc')
            ->orderBy('personal.apellidos', 'asc')
            //->groupBy('cotizacion_lotes.id')
        ->paginate(20);

        return $personas;
    }

    // Función para cancelar la cotización
    public function cancelaCotizacion(Request $request){
        if (!$request->ajax()) return redirect('/');

        $cotizacion = Cotizacion_lotes::findOrFail($request->id);
        $cotizacion->estatus = 2;
        $cotizacion->save();
    }

    // Función para aprobar la cotización
    /*
        Al aprobar la cotización se genera en BD el registro en la tabla de Creditos
        y registro en la tabla contratos, ya que se crea una venta.

        Tambien se crean los pagos correspondientes.
    */
    public function aprovarCotizacion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $cotizacion = Cotizacion_lotes::findOrFail($request->id);
        $datos = $request->datos;

        // Primero se verifica que la venta correspondiente a la cotizacion no se haya creado antes.
        $contrato = Contrato::join('creditos','creditos.id','=','contratos.id')
                                ->select('creditos.lote_id')
                                ->where('contratos.status','=',1)
                                ->where('creditos.lote_id','=',$cotizacion->lotes_id)
                                ->orWhere('contratos.status','=',3)
                                ->where('creditos.lote_id','=',$cotizacion->lotes_id)
                                ->get();

        if(sizeOf($contrato)){
            return redirect('/');
        }
        else{
            try {
                DB::beginTransaction();

                // Se pone estatus de aprobado.
                $cotizacion->estatus = 1;
                $lote = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->select('lotes.num_lote','lotes.manzana','modelos.nombre as modelo',
                                        'etapas.num_etapa', 'lotes.precio_base', 'lotes.terreno',
                                        'fraccionamientos.nombre as proyecto')
                            ->where('lotes.id','=',$cotizacion->lotes_id)->get();

                $pagos = Pagos_lotes::where('cotizacion_lotes_id','=',$request->id)->get();

                $empresa = Empresa::where('id','=',$datos['empresa_id'])->get();


                // Se actualizan los datos del Cliente segun lo capturado en la cotización.
                // PERSONAL
                    $persona = Personal::findOrFail($datos['personalId']);
                    $persona->direccion = $datos['direccion'];
                    $persona->colonia = $datos['colonia'];
                    $persona->cp = $datos['cp'];
                    $persona->save();

                //Cliente
                    $cliente = Cliente::findOrFail($datos['personalId']);
                    $cliente->curp = $datos['curp'];
                    $cliente->nss = $datos['nss'];
                    $cliente->empresa = $datos['empresa'];
                    $cliente->estado = $datos['estado'];
                    $cliente->ciudad = $datos['ciudad'];
                    $cliente->puesto = $request->puesto;
                    if($cliente->coacreditado == 1){
                        $empresaCoa = Empresa::where('id','=',$datos['empresaCoa_id'])->get();
                        $cliente->empresa_coa = $datos['empresa_coa'];
                        $cliente->nss_coa = $datos['nss_coa'];
                        $cliente->curp_coa = $datos['curp_coa'];
                        $cliente->telefono_coa = $datos['telefono_coa'];
                        $cliente->celular_coa = $datos['celular_coa'];
                        $cliente->direccion_coa = $datos['direccion_coa'];
                        $cliente->colonia_coa = $datos['colonia_coa'];
                        $cliente->estado_coa = $datos['estado_coa'];
                        $cliente->ciudad_coa = $datos['ciudad_coa'];
                        $cliente->cp_coa = $datos['cp_coa'];
                    }

                    $cliente->save();

                //Se crea el registro del Credito
                    $credito = new Credito();
                    $credito->prospecto_id = $datos['personalId'];
                    $credito->num_dep_economicos = $request->num_dep_economicos;
                    $credito->tipo_economia = $request->tipo_economia;
                    $credito->nombre_primera_ref = $request->nombre_primera_ref;
                    $credito->nombre_segunda_ref = $request->nombre_segunda_ref;
                    $credito->telefono_primera_ref = $request->telefono_primera_ref;
                    $credito->telefono_segunda_ref = $request->telefono_segunda_ref;
                    $credito->celular_primera_ref = $request->celular_primera_ref;
                    $credito->superficie = $lote[0]->terreno;
                    $credito->celular_segunda_ref = $request->celular_segunda_ref;
                    $credito->fraccionamiento = $lote[0]->proyecto;
                    $credito->etapa = $lote[0]->num_etapa;
                    $credito->manzana = $lote[0]->manzana;
                    $credito->modelo = $lote[0]->modelo;
                    $credito->num_lote = $lote[0]->num_lote;
                    $credito->precio_base = $cotizacion->valor_venta;
                    $credito->precio_venta = $cotizacion->valor_venta - $cotizacion->valor_descuento;
                    $credito->plazo = $cotizacion->mensualidades;
                    $credito->credito_solic = $credito->precio_venta;
                    if($cotizacion->valor_descuento > 0){
                        $credito->promocion = 'Descuento de $'.$cotizacion->valor_descuento;
                        $credito->descripcion_promocion = 'Descuento de $'.$cotizacion->valor_descuento;
                        $credito->descuento_promocion = $cotizacion->valor_descuento;
                    }
                    $credito->status = 2;
                    $credito->lote_id = $cotizacion->lotes_id;
                    $credito->contrato = 1;
                    $credito->vendedor_id = $cliente->vendedor_id;
                    $credito->save();

                // Inst Seleccionada
                    $inst_seleccionada = new inst_seleccionada();
                    $inst_seleccionada->credito_id = $credito->id;
                    $inst_seleccionada->tipo_credito = "Crédito Directo";
                    $inst_seleccionada->status = 2;
                    $inst_seleccionada->institucion = "Grupo Cumbres";
                    $inst_seleccionada->monto_credito = $credito->precio_venta;
                    $inst_seleccionada->plazo_credito = $credito->plazo;
                    $inst_seleccionada->elegido = 1;
                    $inst_seleccionada->save();

                // Se genera el registro en la tabla de Contratos
                    $contrato = new Contrato();
                    $contrato->id = $credito->id;
                    $contrato->total_pagar = $credito->precio_venta;
                    $contrato->enganche_total = $credito->precio_venta;
                    $contrato->saldo = $credito->precio_venta;
                    $contrato->status = 1;
                    $contrato->publicidad_id = $cliente->publicidad_id;
                    $contrato->fecha = Carbon::now();
                    $contrato->direccion_empresa = $empresa[0]->direccion;
                    $contrato->cp_empresa = $empresa[0]->cp;
                    $contrato->colonia_empresa = $empresa[0]->colonia;
                    $contrato->estado_empresa = $empresa[0]->estado;
                    $contrato->ciudad_empresa = $empresa[0]->ciudad;
                    $contrato->telefono_empresa = $empresa[0]->telefono;
                    $contrato->ext_empresa = $empresa[0]->ext;
                    if($cliente->coacreditado == 1){
                        $contrato->direccion_empresa_coa = $empresaCoa[0]->direccion;
                        $contrato->cp_empresa_coa = $empresaCoa[0]->cp;
                        $contrato->colonia_empresa_coa = $empresaCoa[0]->colonia;
                        $contrato->estado_empresa_coa = $empresaCoa[0]->estado;
                        $contrato->ciudad_empresa_coa = $empresaCoa[0]->ciudad;
                        $contrato->telefono_empresa_coa = $empresaCoa[0]->telefono;
                        $contrato->ext_empresa_coa = $empresaCoa[0]->ext;
                    }

                    $contrato->save();

                    $cotizacion->num_contrato = $credito->id;

                //Generación de pagos
                foreach ($pagos as $ep => $det) {
                    $pago = new Pago_contrato();
                    $pago->contrato_id = $credito->id;
                    $pago->num_pago = $det['folio'];
                    $pago->monto_pago = $det['total_a_pagar'];
                    $pago->restante = $det['total_a_pagar'];
                    $pago->fecha_pago = $det['fecha'];
                    $pago->save();

                    //Al los pagos de la cotizacion se indica el pago del contrato que le corresponde.
                    $pagoLote = Pagos_lotes::findOrFail($det['id']);
                    $pagoLote->pagare_id = $pago->id;
                    $pagoLote->save();
                }


                // Deshabilita lote para venta
                    $lote = Lote::findOrFail($cotizacion->lotes_id);
                    $lote->contrato = 1;
                    $lote->save();

                    $cotizacion->save();

                // En caso de existir mas cotizaciones del mismo lote se cancelan.
                $this->cancelaOtros($cotizacion->id, $cotizacion->lotes_id);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }

        }


    }

    // Funcion para cancelar otras cotizaciones de un lote.
    private function cancelaOtros($id,$lote){
        $cotizacion = Cotizacion_lotes::select('id')
        ->where('lotes_id','=',$lote)
        ->where('id','!=',$id)
        ->get();

        foreach ($cotizacion as $key => $cot) {
            $c = Cotizacion_lotes::findOrFail($cot->id);
            $c->estatus = 2;
            $c->save();
        }

        $lote = Lote::findOrFail($lote);
        $lote->contrato = 1;
        $lote->save();
    }

    // Funcion para obtener los datos de una cotizacion segun la cotizacion seleccionada.
    public function getCotizacionEdita(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $cotizacion = Cotizacion_lotes::join('clientes', 'cotizacion_lotes.cliente_id', '=', 'clientes.id')
            ->join('personal', 'clientes.id', '=', 'personal.id')
            ->join('lotes', 'cotizacion_lotes.lotes_id', '=', 'lotes.id')
            ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
            ->join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->select(
                'cotizacion_lotes.id', 'cotizacion_lotes.cliente_id', 'cotizacion_lotes.lotes_id',
                'cotizacion_lotes.valor_venta', 'cotizacion_lotes.valor_descuento',
                'cotizacion_lotes.created_at', 'cotizacion_lotes.updated_at', 'cotizacion_lotes.fecha',
                'cotizacion_lotes.mensualidades',

                'personal.apellidos', 'personal.nombre',

                'clientes.id as cliente_personal_id',

                'lotes.num_lote',
                'lotes.sublote',
                'lotes.manzana',
                'lotes.terreno as terreno_m2',
                'etapas.num_etapa',
                'fraccionamientos.nombre as fraccionamiento'
            )
            ->where('cotizacion_lotes.id', '=', $request->idCotizacion)
        ->first();

        $pago = Pagos_lotes::where('cotizacion_lotes_id', '=', $cotizacion->id)
            ->orderBy('folio')
        ->get();

        return [
            'cotizacion' => $cotizacion,
            'pago' => $pago
        ];
    }

    // Funcion para actualizar datos en la cotización
    public function actualizarCotizacion(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $arrayPagos = $request->pago;

        $cotizacion = Cotizacion_lotes::where('id', '=', $request->idCotizacionEdit)->first();//findOrFail($request->idCotizacionEdit);

        //$cotizacion->cliente_id = $request->idCliente;
        //$cotizacion->lotes_id = $request->idLote;
        //$cotizacion->valor_venta = $request->valor_venta;
        //$cotizacion->valor_descuento = $request->valor_descuento;
        $cotizacion->fecha = $request->fecha;
        $cotizacion->interes = $request->interes;
        //$cotizacion->mensualidades = $request->mensualidades;
        $cotizacion->save();

        foreach($arrayPagos as $pago){
            $newPago = Pagos_lotes::findOrFail($pago['id']);

            //$newPago->cotizacion_lotes_id = $cotizacion->id;

            $newPago->folio = $pago['folio'];
            $newPago->pago = $pago['pago'];
            $newPago->cantidad = $pago['cantidad'];
            $newPago->fecha = $pago['fecha'];
            $newPago->descuento = $pago['descuento'];
            $newPago->dias = $pago['dias'];
            $newPago->descuento_porc = $pago['descuento_porc'];
            $newPago->interes_monto = $pago['interes_monto'];
            $newPago->total_a_pagar = $pago['total_a_pagar'];
            $newPago->saldo = $pago['saldo'];

            $idPago = $newPago->pagare_id;
            $totalPagar = $pago['total_a_pagar'];
            $fecha = $pago['fecha'];
            $folio = $pago['folio'];



            if($idPago != NULL){
                $p = Pago_contrato::select('id')->where('id','=',$idPago)->get();
                if(sizeOf($p)){
                    $pContrato = Pago_contrato::findOrFail($idPago);

                    if($totalPagar != 0){
                        $pContrato->restante = ($pContrato->monto_pago - $pContrato->restante) + $totalPagar;
                        $pContrato->monto_pago = $totalPagar;

                        $pContrato->fecha_pago = $fecha;
                        $pContrato->save();
                    }
                    else{
                        $pContrato->delete();
                    }

                }
                else{
                     if($totalPagar != 0 && $totalPagar != '0'){
                         $pContrato = new Pago_contrato();
                         $pContrato->contrato_id = $cotizacion->num_contrato;
                         $pContrato->num_pago = $folio;
                         $pContrato->monto_pago = $totalPagar;
                         $pContrato->fecha_pago = $fecha;
                         $pContrato->restante = $totalPagar;
                         $pContrato->save();

                         $newPago->pagare_id = $pContrato->id;
                     }

                 }

            }

            $newPago->save();
        }

        return $cotizacion->id;
    }

    // Funcion para retornar los pagos de una cotización.
    public function getDatosPago(Request $request){
        $pago = Pagos_lotes::select('id','dias','interes_monto','pagare_id')->where('pagare_id','=',$request->pagare_id)->get();

        return ['datos_pago'=>$pago];
    }
}
