<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use DB;
use App\Obs_expediente;
use Auth;
use App\Expediente;
use Excel;
use Carbon\Carbon;
use App\Pago_contrato;
use App\Liquidacion;
use App\inst_seleccionada;
use App\Gasto_admin;
use NumerosEnLetras;
use App\Lote;
use App\Credito;
use App\Entrega;
use App\Avaluo;
use App\Bono_recomendado;
use App\PlanoProyecto;
use App\Obs_exp_entregado;
use App\Http\Controllers\BonoRecomendadoController;
use App\Mail\NotificationReceived;
use Illuminate\Support\Facades\Mail;
use App\Personal;
use App\Tipo_credito;
use App\Notifications\NotifyAdmin;
use App\User;

use App\Http\Resources\PlanoResource;

class ExpedienteController extends Controller
{
    private function calcularDiasHabiles($ini, $fin){
        $dt = Carbon::parse($ini);
        $dt2 = Carbon::parse($fin);
        return $daysForExtraCoding = $dt->diffInDaysFiltered(function(Carbon $date) {
            return !$date->isWeekend();
        }, $dt2);

    }

    private function getPlanos($lote_id){
        return PlanoResource::collection(PlanoProyecto::where('lote_id','=',$lote_id)->get());
    }

    // Funcion que retorna los contratos para la integración de expedientes.
    public function indexContratos(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        // Llamada a la función privada que obtiene la query principal.
        $contratos = $this->getQueryIntegracion($request);
        // Se obtienen el resultado de la query ordenando por avaluo preventivo
        $contratos = $contratos->orderBy('contratos.avaluo_preventivo','desc')
                                ->orderBy('licencias.avance','desc')->paginate(8);

        if(sizeof($contratos)){
            // Se recorren los registros para obtener fecha de pago del ultimo pagare y datos del avaluo.
            foreach($contratos as $index => $contrato){

                $contrato->planos = $this->getPlanos($contrato->lote_id);

                $credito_sel = Tipo_credito::where('nombre','=',$contrato->tipo_credito)->where('institucion_fin','=',$contrato->institucion)->first();

                $contrato->diasTramites = $credito_sel->dias_nat;

                if($contrato->avance_contrato < 95)
                    $contrato->diasTramites += $this->calcularDiasHabiles($contrato->fecha_contrato, $contrato->fin_obra);

                $contrato->diasTramites += (($contrato->diasTramites/7)*2);

                $contrato->fechaGest = new Carbon($contrato->fecha_contrato);
                $contrato->fechaGest = $contrato->fechaGest->addDays($contrato->diasTramites)->format('Y-m-d');

                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();

                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();

                if(sizeof($avaluos)){
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->pdf = '';
                }

                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }
                else{
                    $contrato->ultimo_pagare = '';
                }
            }
        }
        // Retorno del resultado.
        return [
            'pagination' => [
                'total'        => $contratos->total(),
                'current_page' => $contratos->currentPage(),
                'per_page'     => $contratos->perPage(),
                'last_page'    => $contratos->lastPage(),
                'from'         => $contratos->firstItem(),
                'to'           => $contratos->lastItem(),
            ],
            'contratos' => $contratos,
        ];
    }

    // Función para obtener los comentarios de un expediente.
    public function listarObservaciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $observaciones = Obs_expediente::where('contrato_id','=', $request->folio)->orderBy('created_at','desc')->get();

        return ['observacion' => $observaciones];
    }

    // Función para registrar una nueva observación.
    public function storeObservacion(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_expediente();
        $observacion->contrato_id = $request->folio;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    // Funcion para exportar a excel los contratos pendientes para integración de expediente.
    public function exportExcel(Request $request){
        // Llamada a la función privada que obtiene la query principal.
        $contratos = $this->getQueryIntegracion($request);
        $contratos = $contratos->orderBy('contratos.avaluo_preventivo','desc')
                                ->orderBy('licencias.avance','desc')->get();

        if(sizeof($contratos)){
            // Se recorren los registros para obtener fecha de pago del ultimo pagare y datos del avaluo.
            foreach($contratos as $index => $contrato){
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();

                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();

                if(sizeof($avaluos)){
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->pdf = '';
                }

                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }
                else{
                    $contrato->ultimo_pagare = '';
                }
            }
        }

        // Retorno y creación del excel.
        return Excel::create('expediente', function($excel) use ($contratos){
            $excel->sheet('contratos', function($sheet) use ($contratos){
                $sheet->row(1, [
                    '# Folio', 'Cliente' ,'Asesor', 'Proyecto', 'Etapa', 'Manzana', 'Lote', 'Modelo',
                    '% Avance','Visita de avaluo' ,'Firma', 'Tipo de credito','Institucion Financiera','Solicitud de avaluo',
                    'Depositado', 'Fecha ultimo pagare',
                    'Aviso preventivo',
                    'Crédito puente',
                    'Regimen en condominio','Conyuge'
                ]);
                $sheet->cells('A1:S1', function ($cells) {
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
                foreach($contratos as $index => $contrato) {

                    $cont++;

                    if($contrato->regimen_condom == 1){
                        $regimen = 'Si';
                    }else{
                        $regimen = 'No';
                    }

                    $avaluo_prev = '';
                    if($contrato->avaluo_preventivo == "0000-01-01"){
                        $avaluo_prev = 'No aplica';
                    }
                    else{
                        $avaluo_prev = $contrato->avaluo_preventivo;
                    }

                    $aviso_prev = '';
                    if($contrato->aviso_prev == "0000-01-01"){
                        $aviso_prev = 'No aplica';
                    }
                    elseif($contrato->aviso_prev){
                        if($contrato->aviso_prev_venc)
                            $aviso_prev = 'Vencimiento: '.$contrato->aviso_prev;
                        else{
                            $aviso_prev = 'Solicitud: '.$contrato->aviso_prev;
                        }
                    }

                    $lote = $contrato->num_lote;
                    if($contrato->sublote != NULL){
                        $lote = $contrato->num_lote.' '.$contrato->sublote;
                    }

                    $sheet->row($index+2, [
                        $contrato->folio,
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $lote,
                        $contrato->modelo,
                        $contrato->avance_lote.'%',
                        $contrato->visita_avaluo,
                        $contrato->fecha_status,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $avaluo_prev,
                        ($contrato->totPagare-$contrato->totRest),
                        $contrato->ultimo_pagare,
                        $aviso_prev,
                        $contrato->credito_puente,
                        $regimen,
                        $contrato->nombre_conyuge

                    ]);
                }
                $num='A1:S' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }

        )->download('xls');
    }

    // Función privada que retorna la query para los contratos pendientes por integrar expediente.
    private function getQueryIntegracion(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            //->leftjoin('avaluos','contratos.id','=','avaluos.contrato_id')
            ->select(
                'contratos.id as folio',
                'contratos.avance_lote as avance_contrato',
                'contratos.fecha as fecha_contrato',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.lote_id',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.foto_acta',
                'licencias.visita_avaluo',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'contratos.avaluo_preventivo',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'lotes.regimen_condom',
                'lotes.fin_obra',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                'clientes.coacreditado',

                'c.celular',
                'clientes.email_institucional',
                'c.email',

                'lotes.credito_puente',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'lotes.emp_constructora',
                'lotes.sublote',

                'contratos.detenido',
                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                            WHERE pagos_contratos.tipo_pagare = 0
                            and pagos_contratos.contrato_id = contratos.id
                            and pagos_contratos.pagado != 3) as totPagare"),
                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                            WHERE pagos_contratos.tipo_pagare = 0
                            and pagos_contratos.contrato_id = contratos.id
                            and pagos_contratos.pagado != 3) as totRest")
            )
            ->where('i.elegido', '=', 1)
            ->where('contratos.integracion', '=', 0)
            ->where('contratos.status', '=', 3);

            switch($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($buscar != '')
                        $contratos = $contratos->where($criterio, '=', $buscar);
                    if($b_etapa != '')
                        $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')
                        $contratos = $contratos->where('creditos.manzana', 'like', '%' . $b_manzana . '%');
                    if($b_lote != '')
                        $contratos = $contratos->where('creditos.num_lote', 'like', '%' . $b_lote . '%');

                    break;
                }
                case 'c.nombre':{
                    if($buscar != '')
                        $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'v.nombre':{
                    if($buscar != '')
                        $contratos = $contratos->where(DB::raw("CONCAT(v.nombre,' ',v.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }

                default:{
                    if($buscar != '')
                        $contratos = $contratos->where($criterio, 'like', '%' . $buscar . '%');
                    break;
                }
            }

        //filtro por estatus (Detenido, activo)
        if($request->btn_status==1){
            $contratos = $contratos->where('contratos.detenido', '=', 1);
        }elseif($request->btn_status==0){
            $contratos = $contratos->where('contratos.detenido', '=', 0);
        }
        // Filtro para empresa constructora
        if($request->b_empresa != ''){
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        return $contratos;
    }

    // Función para crear el registro en la tabla de expedientes
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();
        // Se busca en la tabla expedientes algun registro con el mismo id
        $buscar = Expediente::select('id')->where('id','=',$request->folio)->count();
        // En caso de no encontrar un registro previo
        if($buscar == 0){
            //Se crea un nuevo registro
            $expediente = new Expediente();
            $expediente->id = $request->folio;
            $expediente->fecha_integracion = $hoy;
            $expediente->gestor_id = $request->gestor_id;
            $expediente->save();
        }
        // Se indica en la tabla contrato que ya se encuentra un registro en expedientes.
        $contrato = Contrato::findOrFail($request->folio);
        $contrato->integracion = 1;
        $contrato->save();
    }

    // Función que retorna los registros de expedientes para la asiganción de un gestor.
    public function indexAsignarGestor(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('personal as g','expedientes.gestor_id','=','g.id')
            ->select(
                'contratos.id as folio',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'expedientes.gestor_id',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'lotes.sublote',
                DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
            )
            ->where('i.elegido', '=', 1)
            ->where('contratos.integracion', '=', 1) // El contrato ya se encuentra integrado.
            ->where('contratos.status', '!=', 0) // Diferente de Cancelado
            ->where('contratos.status', '!=', 2); // Diferente de No Firmado

            // Filtros de busqueda
            switch ($criterio){
                case 'lotes.fraccionamiento_id' : {
                    if($buscar != '')
                        $contratos = $contratos->where($criterio, '=', $buscar);
                    if($b_etapa != '')
                        $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')
                        $contratos = $contratos->where('creditos.manzana', 'like', '%' . $b_manzana . '%');
                    if($b_lote != '')
                        $contratos = $contratos->where('creditos.num_lote', 'like', '%' . $b_lote . '%');

                    break;
                }
                case 'c.nombre':{
                    if($buscar != '')
                        $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'g.nombre':{
                    if($buscar != '')
                        $contratos = $contratos->where(DB::raw("CONCAT(g.nombre,' ',g.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                default :{
                    if($buscar != '')
                        $contratos = $contratos->where($criterio, 'like', '%' . $buscar . '%');
                }
            }
        // Busqueda por empresa constructora
        if($request->b_empresa != ''){
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);
        }
        $contratos = $contratos->paginate(10);
        //Retorno de resultados.
        return [
            'pagination' => [
                'total'        => $contratos->total(),
                'current_page' => $contratos->currentPage(),
                'per_page'     => $contratos->perPage(),
                'last_page'    => $contratos->lastPage(),
                'from'         => $contratos->firstItem(),
                'to'           => $contratos->lastItem(),
            ],
            'contratos' => $contratos,
        ];
    }
    // Funcion para asignar el gestor seleccionado al registro.
    public function asignarGestor(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $asignar = Expediente::findOrFail($request->folio);
        $asignar->gestor_id =  $request->gestor_id;
        $asignar->save();
    }

    // Funcion que retorna los registros de Expedientes por ingresar
    public function indexIngresarExp(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;
        // Llamada a la funcion que retorna la query principal
        $query = $this->querySeguimiento($request);

        //
        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            $contratos = $query
            ->where('expedientes.fecha_ingreso','=',NULL)
            ->where('i.elegido', '=', 1)
            ->where('contratos.status', '!=', 0)
            ->where('contratos.status', '!=', 2);

        }else{
            $contratos = $query
            ->where('expedientes.fecha_ingreso','=',NULL)
            ->where('expedientes.gestor_id','=',Auth::user()->id)
            ->where('i.elegido', '=', 1)
            ->where('contratos.status', '!=', 0)
            ->where('contratos.status', '!=', 2);
        }
        // Filtro de Busqueda
        switch($criterio){
            case 'c.nombre':{
                if($buscar != '')
                    $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }
            case 'lotes.fraccionamiento_id':{
                if($buscar != '')
                    $contratos = $contratos->where($criterio, '=', $buscar);
                if($b_etapa != '')
                    $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                if($b_manzana != '')
                    $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);

                break;
            }
            default:{
                if($buscar != '')
                    $contratos = $contratos->where($criterio,'=',$buscar);
                break;
            }
        }
        // Busqueda por empresa constructora
        if($request->b_empresa != '')
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();

        if(sizeof($contratos)){
            // Se recorren los registros encontrados
            foreach($contratos as $index => $contrato){
                //Ultimo pagare del expediente
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();
                // Datos del avaluo para el expediente
                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();

                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->resultado = '';
                    $contrato->fecha_recibido = '';
                    $contrato->avaluoId = '';
                    $contrato->fecha_concluido = '';
                    $contrato->pdf = '';
                }

                if(sizeof($lastPagare))
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                else
                    $contrato->ultimo_pagare = '';
            }
        }
        $contador = $contratos->count();

        return [
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    // Funcion para indicar el ingreso de un expediente
    public function ingresarExp(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $asignar = Expediente::findOrFail($request->folio);
        $asignar->fecha_ingreso =  $request->fecha_ingreso;
        $asignar->valor_escrituras =  $request->valor_escrituras;
        $asignar->save();
    }

    // Funcion para registrar la inscripcion de infonavit de un expediente
    public function inscribirInfonavit(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $expediente = Expediente::findOrFail($request->folio);
        $expediente->fecha_infonavit =  $request->fecha_infonavit;
        $expediente->save();
    }

    // Funcion que retorna los registros de Expedientes autorizados
    public function indexAutorizados(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;
        // Llamada a la funcion que retorna la query principal
        $query = $this->querySeguimiento($request);

        $query = $query->where('i.elegido', '=', 1)
        ->where('i.status','=',2)//Crédito bancario autorizado
        ->where('contratos.status', '!=', 0)// Diferente a Cancelado
        ->where('contratos.status', '!=', 2)// Diferente a No Firmado
        ->where('expedientes.fecha_ingreso','!=',NULL) //Expediente ingresado
        ->where('expedientes.valor_escrituras','!=',0) //Valor de escrituras capturado
        ->where('expedientes.fecha_infonavit','=',NULL); // Sin fecha de infonavit capturado
        // Filtro para empresa constructora
        if($request->b_empresa != ''){
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            $contratos = $query;
        }
        else{
            $contratos = $query
                ->where('expedientes.gestor_id','=',Auth::user()->id);
        }
        //Filtros de busqueda
        switch($criterio){
            case 'c.nombre':{
                    $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }
            case 'lotes.fraccionamiento_id':{

                    if($buscar != '')
                        $contratos = $contratos->where($criterio, '=', $buscar);
                    if($b_etapa != '')
                        $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')
                        $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                    if($b_lote != '')
                        $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
            }
            default :{
                if($buscar != '')
                    $contratos = $contratos->where($criterio,'=',$buscar);
                break;
            }
        }

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();

        $contador = $contratos->count();

        if(sizeof($contratos)){
            //Se recorren los resultados obtenidos.
            foreach($contratos as $index => $contrato){
                //Se obtienen los datos del ultimo pagare
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();
                // Se obtienen los datos del avaluo para
                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();

                $contrato->resultado = '';
                $contrato->fecha_recibido = '';
                $contrato->avaluoId = '';
                $contrato->fecha_concluido = '';
                $contrato->pdf = '';
                $contrato->ultimo_pagare = '';

                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }

                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }
            }
        }

        return [
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    // Funcion para indicar que el Expediente no aplica tramite de Infonavit.
    public function noAplicaInfonavit(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $expediente = Expediente::findOrFail($request->folio);
        $expediente->fecha_infonavit = "0000-01-01";
        $expediente->save();
    }

    // Funcion que retorna los registros de Expedientes por Liquidar
    public function indexLiquidacion(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;
        //Llamada a la funcion privada que retorna la query principal.
        $query = $this->querySeguimiento($request);

        $query = $query->where('i.elegido', '=', 1)
        ->where('i.status','=',2)//Crédito bancario autorizado
        ->where('contratos.status', '!=', 0)//Diferente a Cancelado
        ->where('contratos.status', '!=', 2)//Diferente a No Firmado
        ->where('expedientes.fecha_ingreso','!=',NULL)//Expediente ingresado
        ->where('expedientes.valor_escrituras','!=',0)//Valor de escrituras capturado
        ->where('expedientes.fecha_infonavit','!=',NULL)//Fecha de infonavit capturada
        ->where('expedientes.liquidado','=',0);//Sin Liquidar

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
                $contratos = $query;
        }
        else{
            $contratos = $query->where('expedientes.gestor_id','=',Auth::user()->id);
        }
        //Filtros de busqueda
        switch($criterio){
            case 'c.nombre':{
                if($buscar != '')
                    $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }

            case 'lotes.fraccionamiento_id':{
                if($buscar != '')
                    $contratos = $contratos->where($criterio, '=', $buscar);
                if($b_etapa != '')
                    $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                if($b_manzana != '')
                    $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
            }
            default:{
                if($buscar != '')
                    $contratos = $contratos->where($criterio,'=',$buscar);
                break;
            }
        }
        //Filtro para empresa constructora
        if($request->b_empresa != '')
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();

        if(sizeof($contratos)){
            //Se recorren los registros encontrados
            foreach($contratos as $index => $contrato){
                //Se obtienen los datos del ultimo pagare
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();
                //Se obtienen los datos del avaluo para el contrato.
                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();

                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->resultado = '';
                    $contrato->fecha_recibido = '';
                    $contrato->avaluoId = '';
                    $contrato->fecha_concluido = '';
                    $contrato->pdf = '';
                }

                $contrato->intereses_terreno = 0;

                if($contrato->modelo == 'Terreno'){
                    // Se obtienen los pagos del contrato con los interes correspondientes a cada pagare
                    $pagos = Pago_contrato::join('pagos_lotes','pagos_contratos.id','=','pagos_lotes.pagare_id')
                                ->select('pagos_lotes.interes_monto')
                                ->where('pagos_contratos.contrato_id','=',$contrato->folio)->get();

                    foreach ($pagos as $key => $pago) {
                        // Se suma el total de intereses por cada pagare.
                        $contrato->intereses_terreno += $pago->interes_monto;
                    }
                }

                if(sizeof($lastPagare))
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                else
                    $contrato->ultimo_pagare = '';
            }
        }

        $contador = $contratos->count();
        return [
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    // Funcion para obtener los pagares pendientes por liquidar
    public function pagaresExpediente(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $totalDeposito = 0;
        $folio = $request->folio;
        // Se obtienen los pagares pendientes
        $pagares = Pago_contrato::select('contrato_id','num_pago','fecha_pago','monto_pago','restante','pagado')
                    ->where('contrato_id','=',$folio)
                    ->where('pagado','!=',2)
                    ->orderBy('contrato_id','asc')
                    ->orderBy('num_pago','asc')->get();
        // Se Calcula el monto restante por pagar y monto total de los pagares
        $calculos = Pago_contrato::select(DB::raw("SUM(monto_pago) as enganche"),
                        DB::raw("SUM(restante) as total_restante"))
                    ->groupBy('contrato_id')
                    ->where('contrato_id','=',$folio)
                    ->get();
        // Se obtiene la suma total de los depositos
        $depositos = Pago_contrato::join('depositos','pagos_contratos.id','=','depositos.pago_id')
                                    ->select(DB::raw("SUM(depositos.cant_depo) as pagado"))
                                    ->where('pagos_contratos.contrato_id','=',$folio)
                                    ->first();
        // Se obtiene la suma de intereses moratorios
        $moratorio = Pago_contrato::join('depositos','pagos_contratos.id','=','depositos.pago_id')
                                    ->select(DB::raw("SUM(depositos.interes_mor) as moratorio"))
                                    ->where('pagos_contratos.contrato_id','=',$folio)
                                    ->first();
        // Se obtiene la suma de intereses ordinarios.
        $ordinario = Pago_contrato::join('depositos','pagos_contratos.id','=','depositos.pago_id')
                                    ->select(DB::raw("SUM(depositos.interes_ord) as ordinario"))
                                    ->where('pagos_contratos.contrato_id','=',$folio)
                                    ->first();

        $pagos = Pago_contrato::join('pagos_lotes','pagos_contratos.id','=','pagos_lotes.pagare_id')
                    ->select('pagos_lotes.interes_monto')
                    ->where('pagos_contratos.contrato_id','=',$folio)->get();

        $calculos[0]->interesGen = 0;

        if(sizeof($pagos))
        foreach ($pagos as $key => $pago) {
            // Se suma el total de intereses por cada pagare.
            $calculos[0]->interesGen += $pago->interes_monto;
        }

        if($depositos->pagado == NULL)
            $depositos->pagado = 0;
        else
            $depositos->pagado = $depositos->pagado;

        return ['pagares' => $pagares,
                'depositos' => $depositos,
                'calculos' => $calculos];
    }

    // Funcion para generar la liquidacion del contrato
    public function setLiquidacion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            //Se accede al registro del expediente
            $expediente = Expediente::findOrFail($request->folio);
            $expediente->fecha_liquidacion = $request->fecha_liquidacion;
            $expediente->valor_escrituras = $request->valor_escrituras;
            $expediente->descuento = $request->descuento;
            $expediente->notas_liquidacion = $request->notas_liquidacion;
            $expediente->obs_descuento = $request->obs_descuento;
            //Se accede al registro del contrato
            $contrato = Contrato::findOrFail($request->folio);
            $contrato->saldo = $contrato->saldo - round($request->descuento,2);
            $contrato->integracion = 1;
            $contrato->save();
            //No hay saldo pendiente por liquidar
            if(round($request->total_liquidar,2) <= 0){
                $expediente->liquidado = 1;
                //Se obtienen los registros de los pagares que tienen saldo pendiente
                $pagaresContrato = Pago_contrato::select('id','pagado','contrato_id')
                                            ->where('contrato_id','=',$request->folio)
                                            ->where('pagado','=',1)
                                            ->orWhere('pagado','=',0)
                                            ->where('contrato_id','=',$request->folio)
                                            ->get();
                // Los pagares obtenidos se cambian a estatus liquidado.
                foreach ($pagaresContrato as $pagaresAnteriores){
                    $pagaresCambio = Pago_contrato::findOrFail($pagaresAnteriores->id);
                    $pagaresCambio->pagado = 3;
                    $pagaresCambio->save();
                }
            }else{
                $expediente->liquidado = 0;
            }
            $expediente->total_liquidar = $request->total_liquidar;
            $expediente->infonavit = $request->infonavit;
            $expediente->fovissste = $request->fovissste;
            // En caso de ser un contrato con dos bancos de financiamientos
            // Se actualizan los montos del crédito capturados en la simulacion.
            if($expediente->infonavit != 0 && $expediente->fovissste == 0 ){
                //Se crea el registro para el segundo financiamiento
                $inst_seleccionada = new inst_seleccionada();
                $inst_seleccionada->credito_id = $request->folio;
                $inst_seleccionada->tipo_credito = "INFONAVIT";
                $inst_seleccionada->institucion = "INFONAVIT";
                $inst_seleccionada->monto_credito = $request->infonavit;
                $inst_seleccionada->tipo = 1;
                $inst_seleccionada->status = 2;
                $inst_seleccionada->save();

                $elegido = inst_seleccionada::select('id')
                            ->where('credito_id','=', $request->folio)
                            ->where('elegido','=',1)->get();

                $creditoEle = inst_seleccionada::findOrFail($elegido[0]->id);
                $creditoEle->segundo_credito = $request->infonavit;
                $creditoEle->monto_credito = $request->monto_credito;
                $creditoEle->save();
            }
            elseif($expediente->infonavit == 0 && $expediente->fovissste != 0){
                $inst_seleccionada = new inst_seleccionada();
                $inst_seleccionada->credito_id = $request->folio;
                $inst_seleccionada->tipo_credito = "FOVISSSTE";
                $inst_seleccionada->institucion = "FOVISSSTE";
                $inst_seleccionada->monto_credito = $request->fovissste;
                $inst_seleccionada->tipo = 1;
                $inst_seleccionada->status = 2;
                $inst_seleccionada->save();

                $elegido = inst_seleccionada::select('id')
                            ->where('credito_id','=', $request->folio)
                            ->where('elegido','=',1)->get();

                $creditoEle = inst_seleccionada::findOrFail($elegido[0]->id);
                $creditoEle->segundo_credito = $request->fovissste;
                $creditoEle->monto_credito = $request->monto_credito;
                $creditoEle->save();
            }
            else{
                $elegido = inst_seleccionada::select('id')
                ->where('credito_id','=', $request->folio)
                ->where('elegido','=',1)->get();

                $creditoEle = inst_seleccionada::findOrFail($elegido[0]->id);
                $creditoEle->monto_credito = $request->monto_credito;
                $creditoEle->save();

            }
            $expediente->save();
            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }

    // Funcion para crear los pagos para un nuevo financiamiento con interes
    public function generarPagares(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $intereses = 0;
            // Se crea el registro de la liquidacion
            $liquidacion = new Liquidacion();
            $liquidacion->id = $request->folio;
            $liquidacion->interes_ord = $request->intereses_ordinarios;
            $liquidacion->interes_mor = $request->intereses_moratorios;
            $liquidacion->fecha_ini_interes = $request->fecha_ini_interes;
            $liquidacion->nombre_aval = $request->nombre_aval;
            $liquidacion->direccion =  $request->direccion_aval;
            $liquidacion->telefono = $request->telefono_aval;
            $liquidacion->nombre_aval2 = $request->nombre_aval2;
            $liquidacion->direccion2 =  $request->direccion_aval2;
            $liquidacion->telefono2 = $request->telefono_aval2;
            $liquidacion->save();
            // El expediente pasa a status liquidado
            $expediente = Expediente::findOrFail($request->folio);
            $expediente->liquidado = 1;

            $pagares = $request->pagares;

            $pagaresContrato = Pago_contrato::select('id','pagado','contrato_id')
                                            ->where('contrato_id','=',$request->folio)
                                            ->where('pagado','=',1)
                                            ->orWhere('pagado','=',0)
                                            ->where('contrato_id','=',$request->folio)
                                            ->get();
            // Los pagares pendientes se cambian a liquidado
            foreach ($pagaresContrato as $pagaresAnteriores){
                $pagaresCambio = Pago_contrato::findOrFail($pagaresAnteriores->id);
                $pagaresCambio->pagado = 3;
                $pagaresCambio->save();
            }

            // Se crean los nuevos pagares.
            foreach($pagares as $ep=>$det)
            {
                $pagos = new Pago_contrato();
                $pagos->contrato_id = $request->folio;
                $pagos->num_pago = $ep;
                $pagos->monto_pago = $det['monto_pago'];
                $pagos->fecha_pago = $det['fecha_pago'];
                $pagos->restante = $det['monto_pago'];
                $pagos->pagado = 0;
                $pagos->tipo_pagare = 1;
                $pagos->save();

                $intereses += $det['monto_pago'];

            }

            $intereses -= $expediente->total_liquidar;
            $expediente->interes_ord = round($intereses,2);
            // Se crean los registros correspondientes a los interes Ordinarios
            if($expediente->interes_ord != 0){
                $gasto = new Gasto_admin();
                $gasto->contrato_id = $request->folio;
                $gasto->concepto = 'Interes Ordinario';
                $gasto->costo = $expediente->interes_ord;
                $gasto->fecha = $request->fecha_ini_interes;;
                $gasto->observacion = 'Intereses ordinarios al generar liquidación';
                $gasto->save();
            }

            $expediente->save();

            $contrato = Contrato::findOrFail($request->folio);
            $contrato->saldo += round($intereses,2);
            $contrato->save();
            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }

    // Funcion que retorna los registros de Expedientes pendientes por firma de escrituras
    public function indexProgramacion(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;
        //Llamada a la funcion privada que retorna la query principal.
        $query = $this->querySeguimiento($request);

        $query = $query->where('i.elegido', '=', 1)
        ->where('i.status','=',2)//Crédito bancario autorizado
        ->where('contratos.status', '!=', 0)//Diferente a Cancelado
        ->where('contratos.status', '!=', 2)//Diferente a No Firmado
        ->where('expedientes.fecha_ingreso','!=',NULL)//Expediente Ingresado
        ->where('expedientes.valor_escrituras','!=',0)//Con valor de escrituras capturado
        ->where('expedientes.fecha_infonavit','!=',NULL)//Fecha de infonavit capturada
        ->where('expedientes.liquidado','=',1)//Expediente liquidado
        ->where('expedientes.postventa','!=',1);//

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            $contratos = $query;
        }else{
            $contratos = $query->where('expedientes.gestor_id','=',Auth::user()->id);
        }
        //Filtros de busqueda
        switch($criterio){
            case 'c.nombre':{
                if($buscar != '')
                    $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }

            case 'lotes.fraccionamiento_id':{
                    if($buscar != '')
                        $contratos = $contratos->where($criterio, '=', $buscar);
                    if($b_etapa != '')
                        $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')
                        $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                    if($b_lote != '')
                        $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
            }
            default:{
                if($buscar != '')
                    $contratos = $contratos->where($criterio,'=',$buscar);
                break;
            }
        }

        if($request->b_empresa != ''){
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $contratos = $contratos->orderBy('contratos.id','asc')->get();

        if(sizeof($contratos)){
            //Se recorren los registros encontrados
            foreach($contratos as $index => $contrato){
                $contrato->entrega = 0;
                $entregas = Entrega::select('id')->where('id','=',$contrato->folio)->get();
                if(sizeof($entregas))
                    $contrato->entrega = 1;
                // Se obtienen los datos del ultimo pagare
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();
                // Se obtienen los datos del avaluo solicitado
                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();

                $contrato->resultado = '';
                $contrato->fecha_recibido = '';
                $contrato->avaluoId = '';
                $contrato->fecha_concluido = '';
                $contrato->pdf = '';
                $contrato->ultimo_pagare = '';

                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }

                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }

            }
        }

        $contador = $contratos->count();

        return [
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    // Funcion que retorna los registros de Expedientes finalizados y enviados a Postventa
    public function indexEnviados(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;
        // Llamada a la función privada que retorna la query principal
        $query = $this->querySeguimiento($request);

        $query = $query->where('i.elegido', '=', 1)
        ->where('i.status','=',2)//Crédito bancario autorizado
        ->where('contratos.status', '!=', 0)//Diferente a Cancelado
        ->where('contratos.status', '!=', 2)//Diferente a No Firmado
        ->where('expedientes.fecha_ingreso','!=',NULL)//Expediente ingresado
        ->where('expedientes.valor_escrituras','!=',0)//Valor de escrituras capturado
        ->where('expedientes.fecha_infonavit','!=',NULL)//Fecha de ingreso a infonavit capturado
        ->where('expedientes.liquidado','=',1)//Expediente liquidado
        ->where('expedientes.postventa','=',1);//Expediente enviado a postventa

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701)
            $contratos = $query;
        else
            $contratos = $query->where('expedientes.gestor_id','=',Auth::user()->id);

        //Filtros de busqueda
        switch($criterio){
            case 'c.nombre':{
                if($buscar != '')
                    $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }

            case 'lotes.fraccionamiento_id':{
                if($buscar != '')
                    $contrato = $contratos->where($criterio, '=', $buscar);
                if($b_etapa != '')
                    $contrato = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                if($b_manzana != '')
                    $contrato = $contratos->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $contrato = $contratos->where('lotes.num_lote', '=', $b_lote);
            }
            default:{
                if($buscar != '')
                    $contratos = $contratos->where($criterio,'=',$buscar);
                break;
            }
        }
        //Busqueda por empresa constructora
        if($request->b_empresa != '')
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);

        $contratos = $contratos->distinct()
                                ->orderBy('contratos.id','asc')
                                ->paginate(10);

        if(sizeof($contratos)){
            //Se recorren los registros encontrados
            foreach($contratos as $index => $contrato){
                //Se obtienen los datos del ultimo pagare
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();
                //Se obtienen los datos del avaluo
                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();

                $contrato->resultado = '';
                $contrato->fecha_recibido = '';
                $contrato->avaluoId = '';
                $contrato->fecha_concluido = '';
                $contrato->pdf = '';
                $contrato->ultimo_pagare = '';

                if(sizeof($lastPagare))
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;

                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }
            }
        }

        return [
            'contratos' => $contratos,
            'contador' => $contratos->total(),
                'pagination' => [
                    'total'         => $contratos->total(),
                    'current_page'  => $contratos->currentPage(),
                    'per_page'      => $contratos->perPage(),
                    'last_page'     => $contratos->lastPage(),
                    'from'          => $contratos->firstItem(),
                    'to'            => $contratos->lastItem(),
            ],
        ];
    }

    // Función que crea y retorna el PDF para la liquidación del contrato.
    public function liquidacionPDF($id){
        //Query para obtener los datos necesarios.
        $liquidacion = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
        ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
        ->join('expedientes','contratos.id','=','expedientes.id')
        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
        ->join('licencias', 'lotes.id', '=', 'licencias.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->join('personal as v', 'vendedores.id', '=', 'v.id')
        ->join('personal as g', 'expedientes.gestor_id','=','g.id')
        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
        ->leftjoin('liquidacion','expedientes.id','=','liquidacion.id')
        ->select(
            'contratos.id as folio',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
            DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor"),
            'c.rfc as rfc_cliente',
            'c.homoclave as homoclave_cliente',
            'c.direccion','c.colonia','c.cp','c.telefono',
            'clientes.ciudad','clientes.estado',
            'creditos.fraccionamiento as proyecto',
            'creditos.etapa',
            'creditos.manzana',
            'creditos.num_lote',
            'creditos.modelo',
            'creditos.precio_venta',
            'creditos.precio_base',
                'creditos.terreno_excedente',
                'creditos.precio_terreno_excedente',
                'creditos.descuento_promocion',
                'creditos.precio_venta',
                'creditos.costo_paquete',
                'creditos.precio_obra_extra',
            'contratos.fecha_status',
            'i.tipo_credito',
            'i.institucion',
            'i.fecha_vigencia',
            'i.monto_credito as credito_solic',
            'i.cobrado',
            'i.segundo_credito',
            'contratos.avaluo_preventivo',
            'contratos.aviso_prev',
            'contratos.aviso_prev_venc',
            'contratos.saldo',
            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
            'clientes.rfc_coa as rfc_conyuge',
            'clientes.homoclave_coa as homoclave_coa',
            'clientes.coacreditado',
            'contratos.integracion',
            'lotes.fraccionamiento_id',
            'lotes.emp_constructora',
            'lotes.emp_terreno',
            'expedientes.valor_escrituras',
            'expedientes.fecha_liquidacion',
            'expedientes.liquidado',
            'expedientes.infonavit',
            'expedientes.fovissste',
            'expedientes.total_liquidar',
            'expedientes.notaria',
            'expedientes.fecha_firma_esc',
            'expedientes.interes_ord',
            'expedientes.descuento',
            'expedientes.obs_descuento',
            'expedientes.notas_liquidacion',
            'liquidacion.nombre_aval',
            'liquidacion.direccion as direccion_aval',
            'liquidacion.telefono as telefono_aval',
            'liquidacion.nombre_aval2',
            'liquidacion.direccion2 as direccion_aval2',
            'liquidacion.telefono2 as telefono_aval2',
            'lotes.calle','lotes.numero','lotes.interior',
            'lotes.ajuste','lotes.sobreprecio',
            'avaluos.resultado','avaluos.fecha_recibido'
        )
        ->where('i.elegido', '=', 1)//Financiamiento elegido
        ->where('i.status','=',2)//Financiamiento aprobado
        ->where('expedientes.liquidado','=',1)
        ->where('contratos.id', '=', $id)
        ->orderBy('contratos.id','asc')
        ->get();



        $sumGastos = 0;
        //Se obtienen los gastos administrativos del contrato
        $gastos=Gasto_admin::select('concepto','costo','id')
        ->where('contrato_id','=',$id)
        ->get();

        for($i = 0; $i < count($gastos); $i++){
            //Se calcula el total de gastos y se da formato.
            $sumGastos += $gastos[$i]->costo;
            $gastos[$i]->costo = number_format((float)$gastos[$i]->costo, 2, '.', ',');
        }

        $pagosTerreno = Pago_contrato::join('pagos_lotes','pagos_contratos.id','=','pagos_lotes.pagare_id')
                    ->select('pagos_lotes.interes_monto')
                    ->where('pagos_contratos.contrato_id','=',$id)->get();

        $interesGen = 0;

        if(sizeof($pagosTerreno))
        foreach ($pagosTerreno as $key => $pago) {
            // Se suma el total de intereses por cada pagare.
            $interesGen += $pago->interes_monto;
        }
        //Precio base de la venta
        $liquidacion[0]->precio_base = $liquidacion[0]->precio_base - $liquidacion[0]->descuento_promocion + $interesGen;
        $liquidacion[0]->precio_venta = $liquidacion[0]->precio_venta + $interesGen;

        //Pagares pendientes
       $pagares = Pago_contrato::select('fecha_pago','restante','num_pago')->where('pagado','<',2)->where('contrato_id','=',$id)->get();
       // Monto depositado al contrato
       $depositos_pagado = Pago_contrato::join('depositos','pagos_contratos.id','=','depositos.pago_id')
       ->select(DB::raw("SUM(depositos.cant_depo) as pagado"))
       ->where('pagos_contratos.contrato_id','=',$id)
       ->first();

       $liquidacion[0]->sumaDepositos = $depositos_pagado->pagado;

       setlocale(LC_TIME, 'es_MX.utf8');

       // Se recorren los pagares
       for($i = 0; $i < count($pagares); $i++){
            $pagares[$i]->restante1 = number_format((float)$pagares[$i]->restante, 2, '.', ',');
            $fecha3 = $pagares[$i]->fecha_pago;
            $tiempo4 = new Carbon($fecha3);
            // Se da formato
            $pagares[$i]->fecha_pago_letra = $tiempo4->formatLocalized('%d de %B de %Y');
            $pagares[$i]->montoPagoLetra = NumerosEnLetras::convertir($pagares[$i]->restante, 'Pesos', true, 'Centavos');
       }

       $totalRestante = [];
       //Monto restante en los pagares
       $totalRestante = Pago_contrato::select(DB::raw("SUM(restante) as sumRestante"))
       ->groupBy('contrato_id')
       ->where('pagado','<',2)
       ->where('contrato_id','=',$id)
       ->get();

       if(sizeof($totalRestante) > 0)
            $cantRestante= $totalRestante[0]->sumRestante;
        else
            $cantRestante = 0;
       //Suma de los abonos al contrato
       $liquidacion[0]->sumaParcial = $liquidacion[0]->credito_solic + $liquidacion[0]->fovissste + $liquidacion[0]->infonavit + $liquidacion[0]->sumaDepositos;

       //Calculo de monto restante a liquidar
       $liquidacion[0]->totalRestante =
            $liquidacion[0]->precio_venta - $liquidacion[0]->credito_solic -
            $liquidacion[0]->fovissste - $liquidacion[0]->infonavit - $liquidacion[0]->sumaDepositos - $liquidacion[0]->descuento + $sumGastos;

        //Formato a los datos
        $liquidacion[0]->valor_escrituras = number_format((float)$liquidacion[0]->valor_escrituras, 2, '.', ',');
        $liquidacion[0]->precio_venta = number_format((float)$liquidacion[0]->precio_venta, 2, '.', ',');
        $liquidacion[0]->interes_ord = number_format((float)$liquidacion[0]->interes_ord, 2, '.', ',');
        $liquidacion[0]->credito_solic = number_format((float)$liquidacion[0]->credito_solic, 2, '.', ',');
        $liquidacion[0]->sumaDepositos = number_format((float)$liquidacion[0]->sumaDepositos, 2, '.', ',');
        $liquidacion[0]->fovissste = number_format((float)$liquidacion[0]->fovissste, 2, '.', ',');
        $liquidacion[0]->infonavit = number_format((float)$liquidacion[0]->infonavit, 2, '.', ',');
        $liquidacion[0]->descuento = number_format((float)$liquidacion[0]->descuento, 2, '.', ',');
        $liquidacion[0]->totalRestante = number_format((float)$liquidacion[0]->totalRestante, 2, '.', ',');
        $liquidacion[0]->sumaParcial = number_format((float)$liquidacion[0]->sumaParcial, 2, '.', ',');

        $liquidacion[0]->precio_base = number_format((float)$liquidacion[0]->precio_base, 2, '.', ',');
        $liquidacion[0]->precio_terreno_excedente = number_format((float)$liquidacion[0]->precio_terreno_excedente, 2, '.', ',');
        $liquidacion[0]->precio_obra_extra = number_format((float)$liquidacion[0]->precio_obra_extra, 2, '.', ',');
        $liquidacion[0]->sobreprecio = number_format((float)$liquidacion[0]->sobreprecio, 2, '.', ',');
        $liquidacion[0]->costo_paquete = number_format((float)$liquidacion[0]->costo_paquete, 2, '.', ',');

        if($liquidacion[0]->fecha_firma_esc != NULL){
            $fecha1 = new Carbon($liquidacion[0]->fecha_firma_esc);
            $liquidacion[0]->fecha_firma_esc = $fecha1->formatLocalized('%d de %B de %Y');
        }

        $fecha2 = new Carbon($liquidacion[0]->fecha_liquidacion);
        $liquidacion[0]->fecha_liquidacion = $fecha2->formatLocalized('%d de %B de %Y');
        //Retorno del pdf
        $pdf = \PDF::loadview('pdf.contratos.liquidacion', ['liquidacion' => $liquidacion , 'gastos' => $gastos, 'pagares' => $pagares]);
        return $pdf->stream('Liquidacion.pdf');
    }

    //Funcion para actualizar el monto de crédito
    public function updateMontoCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $credito = Credito::findOrFail($request->id);
            $credito->credito_solic = $request->monto_credito;
            $credito->save();

            $inst_selec_id = inst_seleccionada::select('id')->where('credito_id','=',$request->id)->where('elegido','=',1)->where('status','=',2)->get();

            $inst_seleccionada = inst_seleccionada::findOrFail($inst_selec_id[0]->id);
            $inst_seleccionada->monto_credito = $request->monto_credito;
            $inst_seleccionada->save();
            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }

    //Funcion para generar la firma de escrituras de un expediente
    public function generarInstruccionNot(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            DB::beginTransaction();
            $contrato = Contrato::FindOrFail($request->folio);
            $contrato->integracion = 1;
            $contrato->status = 3;
            $contrato->save();

            //Se accede al registro de expediente
            $asignar = Expediente::findOrFail($request->folio);
            $asignar->fecha_firma_esc =  $request->fecha_firma_esc;
            $asignar->notaria_id =  $request->notaria_id;
            $asignar->notario =  $request->notario;
            $asignar->notaria =  $request->notaria;
            $asignar->hora_firma =  $request->hora_firma;
            $asignar->direccion_firma =  $request->direccion_firma;

            $credito = Credito::findOrFail($request->folio);
            $lote = $credito->lote_id;
            $firmado = Lote::findOrFail($lote);
            $etapa = $firmado->etapa_id;
            $cliente = $credito->prospecto_id;
            $firmado->firmado = 1;
            $firmado->save();

            $contrato = Contrato::findOrFail($request->folio);
            //Si la venta es por Recomendación
            if($contrato->publicidad_id == 1){
                //Se crea el regitro para el bono por recomendado.
                $bonoAnt = Bono_recomendado::select('id')->where('id','=',$request->folio)->get();
                if(sizeOf($bonoAnt)==0){
                    $bono = new BonoRecomendadoController();
                    $bono->store($request->folio, $etapa, $cliente, $contrato->fecha_status);
                }
            }
            //Se verifica que no exista un registro de Entregas ya creado
            $entrega = Entrega::select('id')->where('id','=',$credito->id)->get();

            if(sizeOf($entrega)){
                $asignar->postventa = 1;
            }
            $asignar->save();

            $typCredit = inst_seleccionada::where('credito_id', '=', $request->folio)->where('elegido', '=', 1)->get();
            //Se envia notificacion de la firma
            if($typCredit[0]->tipo_credito != "Crédito Directo"){
                $toAlert = [24706, 24705];
                $msj = 'Se ha realizado una nueva firma de credito escriturado';

                foreach($toAlert as $index => $id){
                    $senderData = DB::table('users')->select('foto_user', 'usuario')->where('id','=',Auth::user()->id)->get();

                    $dataAr = [
                        'notificacion'=>[
                            'usuario' => $senderData[0]->usuario,
                            'foto' => $senderData[0]->foto_user,
                            'fecha' => Carbon::now(),
                            'msj' => $msj,
                            'titulo' => 'Firma de escritura'
                        ]
                    ];
                    User::findOrFail($id)->notify(new NotifyAdmin($dataAr));

                    $persona = Personal::findOrFail($id);

                    Mail::to($persona->email)->send(new NotificationReceived($msj));

                }
            }
        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    //Funcion para regresar el expediente a integración
    public function regresarExpediente(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $folio = $request->folio;
        try{
            DB::beginTransaction();
            // Se accede al registor del contrato
            $contrato = Contrato::findOrFail($folio);
            $contrato->integracion = 0;
            $contrato->aviso_prev = NULL;
            $contrato->avaluo_preventivo = NULL;
            $contrato->save();
            //Se elimina el registo del expediente.
            $expediente = Expediente::findOrFail($folio);
            $expediente->delete();
            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
    }

    //Función para exportar en excel los expedientes por ingresar
    public function excelIngresarExp(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $rolId = Auth::user()->rol_id;

        // Llamada a la funcion que retorna la query principal
        $query = $this->querySeguimiento($request);
        $query = $query->where('expedientes.fecha_ingreso','=',NULL)//Expediente sin ingresar
            ->where('i.elegido', '=', 1)//Financiamiento elegido
            ->where('contratos.status', '!=', 0)//Diferente a Cancelado
            ->where('contratos.status', '!=', 2);//Diferente a No Firmado

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            $contratos = $query;

        }else{
            $contratos = $query
            ->where('expedientes.gestor_id','=',Auth::user()->id);
        }
        //Filtros de busqueda
        switch($criterio){
            case 'c.nombre':{
                if($buscar != '')
                    $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }
            case 'lotes.fraccionamiento_id':{
                if($buscar != '')
                    $contratos = $contratos->where($criterio, '=', $buscar);
                if($b_etapa != '')
                    $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                if($b_manzana != '')
                    $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);

                break;
            }
            default:{
                if($buscar != '')
                    $contratos = $contratos->where($criterio,'=',$buscar);
                break;
            }
        }
        //Busqueda por empresa constructora
        if($request->b_empresa != '')
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();
        //Creación y retorno del excel.
        return Excel::create('Expediente por Ingresar', function($excel) use ($contratos){
            $excel->sheet('Por Ingresar', function($sheet) use ($contratos){

                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Asesor', 'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Modelo','Dirección',
                    'Avance obra', 'Firma contrato', 'Resultado avaluo', 'Aviso preventivo',
                    'Tipo de crédito', 'Institución de financiamiento', 'Valor de la vivienda', 'Crédito puente', 'Saldo'
                ]);
                $sheet->cells('A1:Q1', function ($cells) {
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
                    'J' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                ));
                $cont=1;
                foreach($contratos as $index => $contrato) {
                    $cont++;

                    if($contrato->avaluo_preventivo=='0000-01-01' || $contrato->avaluo_preventivo == NULL){
                        $contrato->resultado = 'No aplica';
                    }
                    if($contrato->aviso_prev=='0000-01-01' || $contrato->aviso_prev == NULL){
                        $contrato->aviso_prev = 'No aplica';
                    }
                    else{
                        $fecha2 = new Carbon($contrato->aviso_prev);
                        $contrato->aviso_prev = $fecha2->formatLocalized('%d de %B de %Y');
                    }

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($contrato->fecha_status);
                    $contrato->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $contrato->folio,
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->modelo,
                        $contrato->calle.' '.$contrato->numero,
                        $contrato->avance_lote,
                        $contrato->fecha_status,
                        $contrato->resultado,
                        $contrato->aviso_prev,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->precio_venta,
                        $contrato->credito_puente,
                        $contrato->saldo,

                    ]);
                }
                $num='A1:Q' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }
    //Función para exportar en excel los expedientes autorizados
    public function excelAutorizados(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;

        // Llamada a la funcion que retorna la query principal
        $query = $this->querySeguimiento($request);
        $query = $query->where('i.elegido', '=', 1)//Financiamiento elegido
        ->where('i.status','=',2)//Financiamiento aprobado
        ->where('contratos.status', '!=', 0)//Diferente de Cancelado
        ->where('contratos.status', '!=', 2)//Diferente de No Firmado
        ->where('expedientes.fecha_ingreso','!=',NULL)//Expediente ingresado
        ->where('expedientes.valor_escrituras','!=',0)//Con Valor de escrituras caputrado
        ->where('expedientes.fecha_infonavit','=',NULL);//Sin Fecha de infonavit
        //Busqueda por empresa constructora
        if($request->b_empresa != '')
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701)
            $contratos = $query;
        else
            $contratos = $query->where('expedientes.gestor_id','=',Auth::user()->id);
        //Filtros de busqueda
        switch($criterio){
            case 'c.nombre':{
                    $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }
            case 'lotes.fraccionamiento_id':{
                if($buscar != '')
                    $contratos = $contratos->where($criterio, '=', $buscar);
                if($b_etapa != '')
                    $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                if($b_manzana != '')
                    $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
            }
            default :{
                if($buscar != '')
                    $contratos = $contratos->where($criterio,'=',$buscar);
                break;
            }
        }

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();

        $contador = $contratos->count();

        return Excel::create('Expediente', function($excel) use ($contratos){
            $excel->sheet('Autorizados', function($sheet) use ($contratos){

                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Asesor', 'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Modelo','Dirección',
                    'Avance obra', 'Firma contrato', 'Resultado avaluo', 'Aviso preventivo',
                    'Tipo de crédito', 'Institución de financiamiento', 'Monto autorizado', 'Fecha vigencia',
                    'Valor de la vivienda', 'Valor a escriturar','Crédito puente', 'Saldo'
                ]);

                $sheet->cells('A1:T1', function ($cells) {
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
                    'K' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                    'T' => '$#,##0.00',
                ));

                $cont=1;

                foreach($contratos as $index => $contrato) {
                    $cont++;

                    if($contrato->avaluo_preventivo=='0000-01-01' || $contrato->avaluo_preventivo == NULL){
                        $contrato->resultado = 'No aplica';
                    }
                    if($contrato->aviso_prev=='0000-01-01' || $contrato->aviso_prev == NULL){
                        $contrato->aviso_prev = 'No aplica';
                    }
                    else{
                        $fecha2 = new Carbon($contrato->aviso_prev);
                        $contrato->aviso_prev = $fecha2->formatLocalized('%d de %B de %Y');
                    }

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($contrato->fecha_status);
                    $contrato->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $contrato->folio,
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->modelo,
                        $contrato->calle.' '.$contrato->numero,
                        $contrato->avance_lote,
                        $contrato->fecha_status,
                        $contrato->resultado,
                        $contrato->aviso_prev,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->credito_solic,
                        $contrato->fecha_vigencia,
                        $contrato->precio_venta,
                        $contrato->valor_escrituras,
                        $contrato->credito_puente,
                        $contrato->saldo,
                    ]);
                }
                $num='A1:T' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

    //Función para exportar en excel los expedientes por liquidar
    public function excelLiquidacion(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;

        // Llamada a la funcion que retorna la query principal
        $query = $this->querySeguimiento($request);
        $query = $query->where('i.elegido', '=', 1)//Financiamiento elegido
        ->where('i.status','=',2)//Financiamiento aprobado
        ->where('contratos.status', '!=', 0)//Diferente a Cancelado
        ->where('contratos.status', '!=', 2)//Diferente a No Firmado
        ->where('expedientes.fecha_ingreso','!=',NULL)//Expediente ingresado
        ->where('expedientes.valor_escrituras','!=',0)//Con Valor de escrituras
        ->where('expedientes.fecha_infonavit','!=',NULL)//Fecha de infonavit capturado
        ->where('expedientes.liquidado','=',0);//Sin liquidar

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701)
                $contratos = $query;
        else
            $contratos = $query->where('expedientes.gestor_id','=',Auth::user()->id);

        //Filtros de busqueda
        switch($criterio){
            case 'c.nombre':{
                if($buscar != '')
                    $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }
            case 'lotes.fraccionamiento_id':{
                if($buscar != '')
                    $contratos = $contratos->where($criterio, '=', $buscar);
                if($b_etapa != '')
                    $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                if($b_manzana != '')
                    $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                if($b_lote != '')
                    $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
            }
            default:{
                if($buscar != '')
                    $contratos = $contratos->where($criterio,'=',$buscar);
                break;
            }
        }

        //Busqueda por empresa constructora
        if($request->b_empresa != '')
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();
        //Creación y retorno en excel
        return Excel::create('Expediente', function($excel) use ($contratos){
            $excel->sheet('Liquidacion', function($sheet) use ($contratos){

                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Asesor', 'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Modelo', 'Dirección',
                    'Avance obra', 'Firma contrato', 'Resultado avaluo', 'Aviso preventivo',
                    'Tipo de crédito', 'Institución de financiamiento', 'Monto autorizado', 'Fecha vigencia',
                    'Valor de la vivienda', 'Valor a escriturar','Crédito puente', 'Saldo','Inscripción Infonavit'
                ]);

                $sheet->cells('A1:T1', function ($cells) {
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
                    'K' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                    'T' => '$#,##0.00',
                ));

                $cont=1;

                foreach($contratos as $index => $contrato) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    if($contrato->avaluo_preventivo=='0000-01-01' || $contrato->avaluo_preventivo == NULL){
                        $contrato->resultado = 'No aplica';
                    }

                    if($contrato->aviso_prev=='0000-01-01' || $contrato->aviso_prev == NULL){
                        $contrato->aviso_prev = 'No aplica';
                    }
                    else{
                        $fecha2 = new Carbon($contrato->aviso_prev);
                        $contrato->aviso_prev = $fecha2->formatLocalized('%d de %B de %Y');
                    }

                    if($contrato->fecha_infonavit=='0000-01-01' || $contrato->fecha_infonavit == NULL){
                        $contrato->fecha_infonavit='No aplica';
                    }
                    else{
                        $fecha3 = new Carbon($contrato->fecha_infonavit);
                        $contrato->fecha_infonavit = $fecha3->formatLocalized('%d de %B de %Y');
                    }

                    $fecha1 = new Carbon($contrato->fecha_status);
                    $contrato->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $contrato->folio,
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->modelo,
                        $contrato->calle.' '.$contrato->numero,
                        $contrato->avance_lote,
                        $contrato->fecha_status,
                        $contrato->resultado,
                        $contrato->aviso_prev,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->credito_solic,
                        $contrato->fecha_vigencia,
                        $contrato->precio_venta,
                        $contrato->valor_escrituras,
                        $contrato->credito_puente,
                        $contrato->saldo,
                        $contrato->fecha_infonavit,
                    ]);
                }
                $num='A1:T' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

    //Función para exportar en excel los expedientes por firma de escritura
    public function excelProgramacion(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;
        // Llamada a la funcion que retorna la query principal
        $query = $this->querySeguimiento($request);
        $query = $query->where('i.elegido', '=', 1)//Financiamiento elegido
        ->where('i.status','=',2)//Financiamiento aprobado
        ->where('contratos.status', '!=', 0)//Diferente de Cancelado
        ->where('contratos.status', '!=', 2)//Diferente de No Firmado
        ->where('expedientes.fecha_ingreso','!=',NULL)//Expediente ingresado
        ->where('expedientes.valor_escrituras','!=',0)//Valor de escrituras capturado
        ->where('expedientes.fecha_infonavit','!=',NULL)//Fecha de infonavit capturado
        ->where('expedientes.liquidado','=',1)//Liquidado
        ->where('expedientes.postventa','!=',1);

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701)
            $contratos = $query;
        else
            $contratos = $query->where('expedientes.gestor_id','=',Auth::user()->id);

        //Filtros de busqueda
        switch($criterio){
            case 'c.nombre':{
                if($buscar != '')
                    $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                break;
            }
            case 'lotes.fraccionamiento_id':{
                    if($buscar != '')
                        $contratos = $contratos->where($criterio, '=', $buscar);
                    if($b_etapa != '')
                        $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_manzana != '')
                        $contratos = $contratos->where('lotes.manzana', '=', $b_manzana);
                    if($b_lote != '')
                        $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
            }
            default:{
                if($buscar != '')
                    $contratos = $contratos->where($criterio,'=',$buscar);
                break;
            }
        }
        //Busqueda por empresa constructora
        if($request->b_empresa != '')
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);

        $contratos = $contratos->orderBy('contratos.id','asc')->get();
        //Creación y retorno de excel.
        return Excel::create('Expediente', function($excel) use ($contratos){
            $excel->sheet('Programación de firma', function($sheet) use ($contratos){

                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Asesor', 'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Modelo','Dirección',
                    'Avance obra', 'Firma contrato', 'Resultado avaluo', 'Aviso preventivo',
                    'Tipo de crédito', 'Institución de financiamiento', 'Monto autorizado', 'Fecha vigencia',
                    'Valor de la vivienda', 'Valor a escriturar','Crédito puente', 'Saldo','Inscripción Infonavit','Liquidación',
                    'Firma de escrituras'
                ]);

                $sheet->cells('A1:W1', function ($cells) {
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
                    'K' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                    'T' => '$#,##0.00',
                ));

                $cont=1;

                foreach($contratos as $index => $contrato) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    if($contrato->avaluo_preventivo=='0000-01-01' || $contrato->avaluo_preventivo == NULL){
                        $contrato->resultado = 'No aplica';
                    }

                    if($contrato->aviso_prev=='0000-01-01' || $contrato->aviso_prev == NULL){
                        $contrato->aviso_prev = 'No aplica';
                    }
                    else{
                        $fecha2 = new Carbon($contrato->aviso_prev);
                        $contrato->aviso_prev = $fecha2->formatLocalized('%d de %B de %Y');
                    }

                    if($contrato->fecha_infonavit=='0000-01-01' || $contrato->fecha_infonavit == NULL){
                        $contrato->fecha_infonavit='No aplica';
                    }
                    else{
                        $fecha3 = new Carbon($contrato->fecha_infonavit);
                        $contrato->fecha_infonavit = $fecha3->formatLocalized('%d de %B de %Y');
                    }
                    if($contrato->fecha_firma_esc==null){
                        $contrato->fecha_firma_esc = 'Sin firmar';
                    }
                    else{
                        $fecha5 = new Carbon($contrato->fecha_firma_esc);
                        $contrato->fecha_firma_esc = $fecha5->formatLocalized('%d de %B de %Y');
                    }

                    $fecha1 = new Carbon($contrato->fecha_status);
                    $contrato->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');

                    $fecha4 = new Carbon($contrato->fecha_liquidacion);
                    $contrato->fecha_liquidacion = $fecha4->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $contrato->folio,
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->modelo,
                        $contrato->calle.' '.$contrato->numero,
                        $contrato->avance_lote,
                        $contrato->fecha_status,
                        $contrato->resultado,
                        $contrato->aviso_prev,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->credito_solic,
                        $contrato->fecha_vigencia,
                        $contrato->precio_venta,
                        $contrato->valor_escrituras,
                        $contrato->credito_puente,
                        $contrato->saldo,
                        $contrato->fecha_infonavit,
                        $contrato->fecha_liquidacion,
                        $contrato->fecha_firma_esc,
                    ]);
                }
                $num='A1:W' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');

    }

    //Función para registrar el envio del expediente
    public function sendExp(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();

        $contrato = Contrato::findOrFail($request->id);
        $contrato->send_exp = $fecha;
        $contrato->save();
        //Se guarda comentario indicando el envio.
        $obs = new Obs_exp_entregado();
        $obs->contrato_id = $request->id;
        $obs->observacion = 'Expediente enviado';
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    //Función para indicar que el expediente fue recibido.
    public function receivedExp(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $fecha = Carbon::now();
        $contrato = Contrato::findOrFail($request->id);
        $contrato->received_exp = $fecha;
        $contrato->save();
        //Se guarda comentario indicando la recepcion del expediente.
        $obs = new Obs_exp_entregado();
        $obs->contrato_id = $request->id;
        $obs->observacion = 'Expediente recibido';
        $obs->usuario = Auth::user()->usuario;
        $obs->save();

    }

    //Funcion que retorna las observaciones de un expediente
    public function getObsExpEntregados(Request $request){
        $obs = Obs_exp_entregado::where('contrato_id','=',$request->id)->orderBy('created_at','desc')->paginate(15);
        return ['observacion'=>$obs];
    }

    //Función para registrar una nueva observación.
    public function storeObsExpEntregado(Request $request){
        $obs = new Obs_exp_entregado();
        $obs->contrato_id = $request->id;
        $obs->observacion = $request->observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    //Funcion privada que retorna la query para los expedientes en seguimiento de tramite.
    private function querySeguimiento(Request $request){
        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->select(
                DB::RAW("DISTINCT(contratos.id) as folio"),
                //'contratos.id as folio',
                'contratos.saldo',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'c.celular',
                'clientes.email_institucional',
                'c.email',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.precio_venta',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'licencias.foto_acta',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'i.fecha_vigencia',
                'i.monto_credito as credito_solic',
                'i.cobrado',
                'i.segundo_credito',
                'contratos.avaluo_preventivo',
                'contratos.detenido',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'contratos.saldo',
                'lotes.regimen_condom',
                'lotes.credito_puente',
                'lotes.emp_constructora',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                'clientes.coacreditado',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'expedientes.valor_escrituras',
                'expedientes.fecha_ingreso',
                'expedientes.fecha_integracion',
                'expedientes.fecha_liquidacion',
                'expedientes.liquidado',
                'expedientes.postventa',
                'expedientes.infonavit',
                'expedientes.fovissste',
                'expedientes.total_liquidar',
                'expedientes.fecha_infonavit',
                'expedientes.fecha_firma_esc',
                'expedientes.notaria_id',
                'expedientes.notario',
                'expedientes.notaria',
                'expedientes.hora_firma',
                'expedientes.direccion_firma',
                'expedientes.notaria_id',
                'expedientes.notario',
                'expedientes.notaria',
                'expedientes.hora_firma',
                'expedientes.direccion_firma',
                'lotes.calle','lotes.numero','lotes.interior',
                'lotes.sublote'
            );

        return $query;
    }


}
