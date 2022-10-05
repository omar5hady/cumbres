<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AvanceController;
use Illuminate\Http\Request;
use App\Notifications\NotifyAdmin;
use App\Precio_etapa;
use App\Sobreprecio_modelo;
use App\Precio_modelo;
use App\Lote;
use App\Lote_promocion;
use App\Modelo;
use App\Etapa;
use App\Fraccionamiento;
use App\Licencia;
use App\Partida;
use App\Avance;
use App\Promocion;
use App\Renta;
use App\Contrato;
use App\Apartado;
use App\User;
use App\Credito;
use App\Vendedor;
use App\Expediente;
use App\lotes_individuales;
use App\Credito_puente;
use App\Lote_puente;
use App\Precio_puente;
use Carbon\Carbon;
use App\Specification;
use App\SpecificationLote;
use Session;
use Excel;
use File;
use DB;
use Auth;

class LoteController extends Controller
{

    private function searchContrato($lote_id, $tipo){
        $resp = 0;
        if($tipo == 0)
            $resp = sizeof(
                Contrato::join('creditos','contratos.id','=','creditos.id')
                ->select('contratos.id')->where('creditos.lote_id','=',$lote_id)
                ->whereIn('contratos.status',[1,3])
                ->get()
            );
        else{
            $resp = sizeof(
                Renta::select('id')
                ->where('lote_id','=',$lote_id)
                ->where('status','=',1)
                ->get()
            );
        }

        return $resp;
    }

    //Función privada que retorna la query necesaria para obtener los lotes registrados
    private function getLotes(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $b_modelo = $request->bmodelo;
        $b_lote = $request->blote;
        $criterio = $request->criterio;
        $b_puente = $request->b_puente;
        //Query principal
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','etapas.num_etapa',
                    'modelos.tipo',
                    'lotes.*','licencias.*','modelos.nombre as modelo','empresas.nombre as empresa'
                  );

                if($buscar != '' && $criterio!= 'lotes.fraccionamiento_id')//Busqueda principal por criterio
                    $lotes = $lotes->where($criterio, 'like', '%'. $buscar . '%');
                elseif($buscar != '' && $criterio == 'lotes.fraccionamiento_id')
                    $lotes = $lotes->where($criterio, '=', $buscar);
                if($b_puente != '')//Busqueda por crédito puente
                    $lotes = $lotes->where('lotes.credito_puente','=',$b_puente);
                if($b_modelo != '')//Busqueda por modelo
                    $lotes = $lotes->where('modelos.id', '=', $b_modelo );
                if($buscar2!='')//Busqueda por etapa de servicio
                    $lotes = $lotes->where('lotes.etapa_servicios', 'like', '%'. $buscar2 . '%');
                if($buscar3 != '')//Busqueda por manzana
                    $lotes = $lotes->where('lotes.manzana', '=',$buscar3);
                if($b_lote != '')//Busqueda por numero de lote
                    $lotes = $lotes->where('lotes.num_lote', '=',$b_lote);
                if($request->b_empresa != '')//Busqueda por empresa constructora
                    $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);
                if($request->b_empresa2 != '')//Busqueda por empresa de terreno
                    $lotes= $lotes->where('lotes.emp_terreno','=',$request->b_empresa2);
                if($request->b_etapa != '')//Busqueda por etapa de venta
                    $lotes = $lotes->where('lotes.etapa_id', '=', $request->b_etapa);

              return $lotes;

    }

    public function indexLotesRentas(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $rentas = $this->getLotes($request);
        if($request->b_status != '')
            $rentas = $rentas->where('lotes.contrato','=',$request->b_status);
        if($request->b_direccion != '')
            $rentas = $rentas->where(DB::raw("CONCAT(lotes.calle,' No.',lotes.numero)"), 'like', '%'. $request->b_direccion . '%');
        //Filtros necesarios para obtener lotes habilitados para rentas
        $rentas = $rentas->where('lotes.habilitado','=', 1)
                        ->where('lotes.casa_renta','=', 1)
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(12);

        return $rentas;
    }

    public function enteradoEsp(Request $request){
        $lote = Lote::findOrFail($request->id);
        $lote->cambio_esp = 0;
        $lote->save();
    }

    public function index(Request $request) //Index para modulo asignar modelo
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        //Llamada a la funcion privada que retorna los lotes.
        //se asignan a variable para lotes, rentas y deshabilitadas
        $lotes = $this->getLotes($request);
        $rentas = $this->getLotes($request);
        $deshabilitadas = $this->getLotes($request);
        //Filtros necesarios para obtener lotes habilitados
        $lotes = $lotes->where('lotes.habilitado','=', 1)
                        ->where('lotes.casa_renta','=', 0)
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')
                        ->orderBy('lotes.etapa_servicios','ASC')->paginate(25);
        //Se recorre el resultado de lotes obtenidos para determinar el estado actual del lote (Vendido, Individualizado o disponible.)
        foreach ($lotes as $key => $lote) {
            $lote->status = 0;
            /* Joining the tables and selecting the columns. */
            $expediente = Expediente::join('contratos','expedientes.id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select('expedientes.liquidado','i.tipo_credito')
                        ->where('expedientes.liquidado','=',1)
                        ->where('contratos.status','=',3)
                        ->where('i.tipo_credito','=','Crédito Directo')
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->orWhere('expedientes.fecha_firma_esc','!=',NULL)
                        ->where('contratos.status','=',3)
                        ->where('i.tipo_credito','!=','Crédito Directo')
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->get();

            if(sizeof($expediente)){
                $lote->status = 2;
            }
            else{
                $contrato = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->select('creditos.id')
                    ->where('contratos.status','=',3)
                    ->where('creditos.lote_id','=',$lote->id)
                    ->get();
                    if(sizeof($contrato))
                        $lote->status = 1;

            }
        }
        //Filtros necesarios para obtener lotes habilitados para rentas
        /* A query builder that is returning a paginated result. */
        $rentas = $rentas->where('lotes.habilitado','=', 1)
                        ->where('lotes.casa_renta','=', 1)
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')
                        ->orderBy('lotes.etapa_servicios','ASC')->paginate(25);

        //Filtros necesarios para obtener lotes deshabilitados
        $deshabilitadas = $deshabilitadas->where('lotes.habilitado','=', 0)
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')
                        ->orderBy('lotes.etapa_servicios','ASC')->paginate(25);

        return [
            'lotes' => $lotes,
            'rentas' => $rentas,
            'deshabilitadas' => $deshabilitadas,
        ];
    }

    public function index2(Request $request) // index para modulo de lotes
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        //Llamada a la funcion privada que retorna los lotes.
        $lotes = $this->getLotes($request);
        $lotes = $lotes->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('etapas.num_etapa','DESC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(25);

        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes
        ];
    }

    //funcion para registrar un nuevo lote.
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //Se obtiene el ID para etapa Sin asignar perteneciente al fraccionamiento
        $etapa= Etapa::select('id')
                ->where('num_etapa','=', 'Sin Asignar')
                ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                ->get();
        //Se obtiene el ID para modelo Por asignar perteneciente al fraccionamiento
        $modelo= Modelo::select('id')
                ->where('nombre','=', 'Por Asignar')
                ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                ->get();

            try {
                DB::beginTransaction();
                //Se crea el nuevo registro del lote
                $lote = new Lote();
                $lote->fraccionamiento_id = $request->fraccionamiento_id;
                $lote->etapa_id = $etapa[0]->id;
                $lote->manzana = $request->manzana;
                $lote->num_lote = $request->num_lote;
                $lote->sublote = $request->sublote;
                $lote->modelo_id = $modelo[0]->id;
                $lote->empresa_id = $request->empresa_id;
                $lote->calle = $request->calle;
                $lote->numero = $request->numero;
                $lote->interior = $request->interior;
                $lote->terreno = $request->terreno;
                $lote->construccion = $request->construccion;
                $lote->clv_catastral = $request->clv_catastral;
                $lote->etapa_servicios = $request ->etapa_servicios;
                $lote->arquitecto_id = 1;
                $lote->indivisos = $request->indivisos;
                $lote->save();
                //Se crea el nuevo registro para la tabla licencias
                $licencia = new Licencia();
                $licencia->id = $lote->id;
                $licencia->perito_dro = $lote->arquitecto_id;
                $licencia->colindancias = $request->colindancias;

                $licencia->num_escritura = $request->num_escritura;
                $licencia->date_escritura = $request->date_escritura;
                $licencia->num_notario = $request->num_notario;
                $licencia->distrito_notario = $request->distrito_notario;
                $licencia->folio_registro = $request->folio_registro;
                $licencia->save();

                DB::commit();


            } catch (Exception $e) {
                DB::rollBack();
            }


    }

    //Funcion para actualizar el registro del lote
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lote = Lote::findOrFail($request->id);
        $lote->fraccionamiento_id = $request->fraccionamiento_id;
        $lote->etapa_id = $request->etapa_id;
        $lote->manzana = $request->manzana;
        $lote->num_lote = $request->num_lote;
        $lote->sublote = $request->sublote;
        $lote->empresa_id = 1;
        $lote->calle = $request->calle;
        $lote->numero = $request->numero;
        $lote->interior = $request->interior;
        $lote->terreno = $request->terreno;
        $lote->construccion = $request->construccion;
        $lote->clv_catastral = $request->clv_catastral;
        $lote->etapa_servicios = $request ->etapa_servicios;
        $lote->indivisos = $request->indivisos;
        $lote->save();

        $licencia = Licencia::findOrFail($request->id);
        $licencia->colindancias = $request->colindancias;
        $licencia->num_escritura = $request->num_escritura;
        $licencia->date_escritura = $request->date_escritura;
        $licencia->num_notario = $request->num_notario;
        $licencia->distrito_notario = $request->distrito_notario;
        $licencia->folio_registro = $request->folio_registro;
        $licencia->save();
    }

    //Función para actualizar el registro del lote para el modulo Asignar modelo
    public function update2(Request $request)
    {
        $siembra = '';
        $terrenoExcedente = 0;
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

       $etapa= Etapa::select('num_etapa')
       ->where('id','=', $request->etapa_id)
       ->get();

       $modeloOld = Lote::join('modelos','lotes.modelo_id','modelos.id')
       ->select('lotes.modelo_id','modelos.nombre')
       ->where('lotes.id','=',$request->id)
       ->get();

       $terrenoModelo = Modelo::select('terreno', 'nombre')
       ->where('id','=',$request->modelo_id)
       ->get();

        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lote = Lote::findOrFail($request->id);
        $lote->fraccionamiento_id = $request->fraccionamiento_id;
        $lote->etapa_id = $request->etapa_id;
        $lote->manzana = $request->manzana;
        $lote->num_lote = $request->num_lote;
        $lote->sublote = $request->sublote;
        $lote->modelo_id = $request->modelo_id;
        if($request->modelo_id != $modeloOld[0]->modelo_id){//Si se cambia el modelo actual al lote
            $siembra = Carbon::today()->format('ymd');
            $lote->siembra=$siembra;

            $licencia = Licencia::findOrFail($request->id);
            $licencia->cambios = 1;
            if($modeloOld[0]->nombre != "Por Asignar"){//Si el modelo anterior es diferente a Por asignar
                $licencia->modelo_ant = $modeloOld[0]->nombre;
                //Se llama a la funcion que actualiza los datos para el crédito puente
                $this->actPuenteLote($request->id, $modeloOld[0]->nombre, $request->modelo_id);
            }
            $licencia->save();
        }
        $lote->empresa_id = 1;
        $lote->calle = $request->calle;
        $lote->numero = $request->numero;
        $lote->interior = $request->interior;
        $lote->terreno = $request->terreno;
        $lote->construccion = $request->construccion;
        $lote->credito_puente = $request->credito_puente;
        $lote->lote_comercial = $request->lote_comercial;
        $lote->casa_muestra = $request->casa_muestra;
        $lote->comentarios = $request->comentarios;
        $lote->regimen_condom = $request->regimen;
        $lote->fecha_termino_ventas = $request->fecha_termino_ventas;
        $lote->extra = $request->extra;
        $lote->extra_ext = $request->extra_ext;
        //Si se habilita el lote para venta
        if($request->habilitado == 1){
            $this->setEspecificaciones($lote->id,$lote->modelo_id);
            if($request->casa_renta == 1){//En caso de ser lote para renta
                $lote->casa_renta = $request->casa_renta;
                $lote->precio_renta = $request->precio_renta;
                $lote->apartado = 1;
                $lote->contrato = $this->searchContrato($lote->id,1);

                $lote->condiciones = 'Usada: Renta';
            }
            else{
                if($terrenoModelo[0]->nombre != "Terreno"){//Para lotes con construcción (Casas o departamento)
                    $precioTerreno = Precio_etapa::select('precio_excedente','id')//Se obtiene el precio por excedente
                    ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                    ->where('etapa_id','=',$request->etapa_id)->get();
                    //Se obtiene el precio base del modelo de casa
                    $precioBase = Precio_modelo::select('precio_modelo')
                    ->where('modelo_id','=',$request->modelo_id)
                    ->where('precio_etapa_id', '=', $precioTerreno[0]->id)
                    ->get();
                    //Se obtienen los sobreprecios asignados al lote
                    $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                    ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
                    ->where('sobreprecios_modelos.lote_id','=',$request->id)->get();
                    //Se calcula el terreno excedente
                    $terrenoExcedente = ($lote->terreno - $terrenoModelo[0]->terreno);
                    if($terrenoExcedente > 0)//En caso de tener excedente se calcula el monto y se asigna al lote
                        $lote->excedente_terreno = $terrenoExcedente * $precioTerreno[0]->precio_excedente;
                    //Se asigna el precio base del lote
                    $lote->precio_base = $precioBase[0]->precio_modelo;
                    //En caso de tener sobreprecios se asignan al lote
                    if($sobreprecios[0]->sobreprecios != NULL)
                        $lote->sobreprecio = $sobreprecios[0]->sobreprecios;
                    else
                        $lote->sobreprecio = 0;
                }
                else{
                    //Se obtiene el costo por m2 para terreno
                    $preciom2 = lotes_individuales::select('costom2','id')
                            ->where('etapa_id','=',$request->etapa_id)->get();
                    //Se asigna el precio calculado el precio por m2 multuplicado por el tamaño del terreno.
                    $lote->precio_base = $preciom2[0]->costom2 * $lote->terreno;
                }
                $lote->contrato = $this->searchContrato($lote->id,0);
                $lote->casa_renta = $request->casa_renta;
            }
        }
        else{
            $lote->excedente_terreno = 0;
            $lote->sobreprecio=0;
            $lote->precio_base=0;
        }
        $lote->habilitado = $request->habilitado;

        if($lote->casa_muestra == 1)
            $lote->condiciones = 'Usada: Casa muestra';


        $lote->save();
    }

    //Función privada que actualiza los datos del credito puente para el lote seleccionado.
    private function actPuenteLote($lote, $modeloAnt, $modelo_id)
    {
        //Se obtiene el identificado del registro en la tabla lotes_puente ligado al lote
        $lotePuente = Lote_puente::select('id')->where('lote_id','=',$lote)->get();

        if(sizeof($lotePuente)){
            //Se accede al registro
            $auxLotePuente = Lote_puente::findOrFail($lotePuente[0]->id);
            $precio = 0;
            //Se obtiene el precio del modelo
            $precioModelo = Precio_puente::select('precio')->where('solicitud_id','=',$auxLotePuente->solicitud_id)->where('modelo_id','=',$modelo_id)->get();
            if(sizeof($precioModelo))//Si se encuentra el precio registrado, se asigna a la variable.
                $precio = $precioModelo[0]->precio;
            $auxLotePuente->modeloAnt2 = $auxLotePuente->modeloAnt1;
            $auxLotePuente->precio2 = $auxLotePuente->precio1;
            $auxLotePuente->modeloAnt1 = $modeloAnt;
            $auxLotePuente->precio1 = $auxLotePuente->precio_p;
            $auxLotePuente->modelo_id = $modelo_id;
            $auxLotePuente->precio_p = $precio;
            $auxLotePuente->save();
            //Se actualiza el registro general del crédito puente.
            $creditoPuente = Credito_puente::findOrFail($auxLotePuente->solicitud_id);
            $creditoPuente->total = $creditoPuente->total - $auxLotePuente->precio1 + $auxLotePuente->precio_p;
            $creditoPuente->save();
        }
    }

    private function setEspecificaciones($lote, $modelo){
        $anterior = SpecificationLote::select('id')->where('lote_id','=',$lote)->get();
        if(sizeof($anterior))
            foreach($anterior as $e){
                $spe = SpecificationLote::findOrFail($e->id);
                $spe->delete();
            }

        $baseEspecificaciones = Specification::where('modelo_id','=',$modelo)->get();
        if(sizeof($baseEspecificaciones)){
            foreach($baseEspecificaciones as $base){
                $es = new SpecificationLote();
                $es->lote_id = $lote;
                $es->general = $base->general;
                $es->subconcepto = $base->subconcepto;
                $es->descripcion = $base->descripcion;
                $es->save();
            }
        }
    }

    //Función para asignar modelos en masa.
    public function asignarMod(Request $request) // EN MASA
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->id == 26516)return redirect('/');

        try {
            DB::beginTransaction();
            //FindOrFail se utiliza para buscar lo que recibe de argumento
            $lote = Lote::findOrFail($request->id);
            $lote->etapa_id = $request->etapa_id;
            $lote->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    //Función para enviar aviso de construcción.
    public function enviarAviso(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $aviso = $request->aviso;
        $id = $request->id;

        try {
            DB::beginTransaction();
            //FindOrFail se utiliza para buscar lo que recibe de argumento
            $lote = Lote::findOrFail($id);
            $lote->fecha_ini = $request ->fecha_ini;
            $lote->fecha_fin = $request ->fecha_fin;
            $lote->fecha_termino_ventas = $request ->fecha_termino_ventas;
            $lote->ini_obra = 1;
            $lote->arquitecto_id = $request ->arquitecto_id;
            $lote->ehl_solicitado = Carbon::today()->format('ymd');
            $lote->num_inicio = $request->num_inicio;
            $lote->save();

            $n_avance = new AvanceController();
            //Se llama la funcion que crea los registros para avances de urbanización
            $n_avance->storeUrbanizcion($lote->id);
            //Se accede al registro de licencias
            $lic = Licencia::findOrFail($request->id);
            if($lic->avance == 0) //En caso de no tener ningun avance se inicializa en 1
                $lic->avance = 1;
            $lic->save();

            //Se crea notificación avisando del inicio de construccion.
            if($aviso != '0'){
                $loteIni = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->select('fraccionamientos.nombre as proyecto','lotes.fecha_fin','lotes.fecha_ini')
                ->where('lotes.id','=',$id)->get();

                setlocale(LC_TIME, 'es_MX.utf8');
                $fecha_fin = new Carbon($loteIni[0]->fecha_fin);
                $loteIni[0]->fecha_fin = $fecha_fin->formatLocalized('%d-%m-%Y');
                $fecha_ini = new Carbon($loteIni[0]->fecha_ini);
                $loteIni[0]->fecha_ini = $fecha_ini->formatLocalized('%d-%m-%Y');

                $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = "Se han enviado ". $aviso ." lotes del Proyecto ". $loteIni[0]->proyecto . " a inicio de obra (" . $loteIni[0]->fecha_ini . " - " . $loteIni[0]->fecha_fin . ") ";
                $iniciosObra = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Inicio de obra pendiente'
                    ]
                ];

                $users = User::select('id')->where('rol_id','=','5')->orWhere('id','=',3)->get();

                foreach($users as $notificar){
                    User::findOrFail($notificar->id)->notify(new NotifyAdmin($iniciosObra));
                }
            }

            //Aqui se deberia hacer toda la asignacion para la tabla avances
            //Se crean las partidas necesarias para los avances de obra del lote.
            $partidas = Partida::select('id','partida')
                ->where('modelo_id','=',$lote->modelo_id)->get();

                foreach($partidas as $index => $partida) {
                    $n_avance->store($lote->id, $partida->id);
                }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    //Función que actualiza el numero de inicio de construcción.
    public function actNumInicio(Request $request){
        $lote = Lote::findOrFail($request->id);
        $lote->num_inicio = $request->num_inicio;
        $lote->save();
    }

    //Función para eliminar el registro de un lote.
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $lote = Lote::findOrFail($request->id);
        $lote->delete();
    }

    //Función para importar nuevos registros de lotes desde un archivo Excel.
    public function import(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));

        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                if($request->paso == 1){
                    //Se obtiene el id de la etapa Sin asignar para el fraccionamiento elegido
                    $etapa= Etapa::select('id')
                    ->where('num_etapa','=', 'Sin Asignar')
                    ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                    ->get();
                    //Se obtiene el id del modelo Por asignar para el fraccionamiento elegido
                    $modelo= Modelo::select('id','construccion')
                    ->where('nombre','=', 'Por Asignar')
                    ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                    ->get();
                    //Se calcula el id para la licencia
                    if(Licencia::count() > 0){
                        $lotes =Licencia::select('id')->get();
                        $id = $lotes->last()->id + 1;
                    }
                    else{
                        $id = 1;
                    }
                }


                $path = $request->file->getRealPath();
                //Se obtiene la información del archivo.
                $data = Excel::load($path, function($reader) {
                })->get();

                if(!empty($data) && $data->count()){
                    if($request->paso == 1){
                        //Se reocrren los registros encontrados
                        foreach ($data as $key => $value) {
                            //Se inicializan las variables para las empresas
                            $emp_terreno = 'Grupo Constructor Cumbres';
                            $emp_constructora = 'Grupo Constructor Cumbres';

                            if($value->empresa_terreno == 2)
                                $emp_terreno = 'CONCRETANIA';
                            if($value->empresa_constructora == 2)
                                $emp_constructora = 'CONCRETANIA';
                            //Si el numero de lote en el archivo no esta vacio
                            if($value->num_lote != '' || $value->num_lote != 0)
                            {   //Se inserta el registro en un arreglo.
                                $insert[] = [
                                    'id' => $id,
                                    'fraccionamiento_id' => $request->fraccionamiento_id,
                                    'etapa_id' => $etapa[0]->id,
                                    'manzana' => $value->manzana,
                                    'num_lote' => $value->num_lote,
                                    'sublote' => $value->duplex,
                                    'modelo_id' => $modelo[0]->id,
                                    'empresa_id' => 1,
                                    'calle' => $value->calle,
                                    'numero' => $value->numero_oficial,
                                    'interior' => $value->interior,
                                    'terreno' => $value->superficie_terreno,
                                    'construccion' => $modelo[0]->construccion,
                                    'clv_catastral' =>$value->clave_catastral,
                                    'etapa_servicios' =>$value->etapa_servicios,
                                    'arquitecto_id' => 1,
                                    'emp_constructora' =>$emp_constructora,
                                    'emp_terreno' =>$emp_terreno,
                                    'indivisos' => $value->indivisos
                                ];
                                //Y se crea otro arreglo para las licencias
                                $insert2[]  = [
                                    'id' => $id++,
                                    'perito_dro' => 1
                                ];
                            }
                        }
                        // Si arreglo se lleno correctamente
                        if(!empty($insert)){
                            //Se insertan los registros en las tablas de lotes y licencias.
                            $insertData = DB::table('lotes')->insert($insert);
                            $insertData2 = DB::table('licencias')->insert($insert2);
                            if ($insertData) {
                                Session::flash('success', 'Your Data has successfully imported');
                            }else {
                                Session::flash('error', 'Error inserting the data..');
                                return back();
                            }
                        }
                    }
                    if($request->paso == 2){
                        // return $data;
                        foreach ($data as $key => $value) {

                            $proyecto = Fraccionamiento::select('id')->where('nombre','=',$value->proyecto)->first();
                            $etapa = Etapa::select('id')->where('fraccionamiento_id','=',$proyecto->id)->where('num_etapa','=',$value->etapa)->first();
                            $lote = Lote::select('id')
                                ->where('fraccionamiento_id','=',$proyecto->id)
                                ->where('etapa_id','=',$etapa->id)
                                ->where('manzana','=',$value->manzana)
                                ->where('num_lote','=',$value->lote);
                                if($value->sublote != 'NULL')
                                    $lote = $lote->where('sublote','=',$value->sublote);
                                else{
                                    $lote = $lote->where('sublote','=',NULL);
                                }
                                $lote = $lote->first();

                            if($lote){
                                $lt = Licencia::findOrFail($lote->id);
                                $lot = Lote::findOrFail($lote->id);
                                $lt->folio_registro = $value->folio;
                                $lot->clv_catastral = $value->predial;
                                $lt->save();
                                $lot->save();
                            }
                        }
                    }

                }
                return back();
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }
    //Función que retorna los modelos por etapa.
    public function select_modelos_etapa(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar1 = $request->buscar1;
        $buscar2 = $request->buscar2;
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->join('modelos','lotes.modelo_id','=','modelos.id')
        ->select('modelos.nombre', 'lotes.modelo_id', 'lotes.fraccionamiento_id',  'lotes.etapa_id')
        ->distinct()
        ->where('lotes.fraccionamiento_id', '=', $buscar1)
        ->where('lotes.etapa_id', '=', $buscar2)->get();
        return['lotes' => $lotes];
    }
    //Función que retorna las manzanas por etapa
    public function select_manzanas_etapa (Request $request){
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar1 = $request->buscar1;
        $manzana = Lote::select('lotes.manzana')->distinct()
                        ->where('lotes.fraccionamiento_id','=', $buscar)
                        ->where('lotes.etapa_id','=', $buscar1)
                        ->orderBy('lotes.manzana','asc')
                        ->get();

        return ['manzana' => $manzana];

    }
    //Función que retorna los lotes por manzana.
    public function select_lote_manzana (Request $request){
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar1 = $request->buscar1;
        $buscar2 = $request->buscar2;
        $lote_manzana = Lote::select ('lotes.num_lote','lotes.id')
                             ->where('lotes.fraccionamiento_id','=', $buscar)
                             ->where('lotes.etapa_id','=', $buscar1)
                             ->where('lotes.manzana','=', $buscar2);

            if($request->puente == 1)
                $lote_manzana = $lote_manzana->where('lotes.credito_puente','=', NULL);

        $lote_manzana = $lote_manzana->get();

        return ['lote_manzana' => $lote_manzana];
    }

    //Función que retorna los lotes disponibles para inicio de construcción.
    public function indexIniObra(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;

        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                  'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                  'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                  'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','lotes.fecha_termino_ventas')
                  ->where('modelos.nombre','!=','Terreno')
                  ->where('lotes.ini_obra', '=', '0')
                  ->where('modelos.nombre','!=','Por Asignar');

                if($buscar != '')//Busqueda general
                    $lotes = $lotes->where($criterio, '=',$buscar);
                if($buscar2 != '')//Busqueda por etapa
                    $lotes = $lotes->where('lotes.etapa_id', '=',$buscar2 );
                if($buscar3 != '')//Busqueda por manzan
                    $lotes = $lotes->where('lotes.manzana', 'like', '%'. $buscar3 . '%');
                if($request->b_empresa != '')//Busqueda por empresa constructora
                    $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);

        $lotes = $lotes->orderBy('fraccionamientos.nombre','lotes.id')->paginate(15);

        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes
        ];
    }

    //Función que retorna la query para obtener el historial de inicios de construccion
    public function getHistorialInicios(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $fecha = $request->fecha;
        $fecha2 = $request->fecha2;
        $criterio = $request->criterio;
        //Query principal
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                  'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                  'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id','lotes.num_inicio',
                  'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                  'lotes.fecha_termino_ventas', 'lotes.ehl_solicitado')
            ->where('modelos.nombre','!=','Por Asignar')
            ->where('lotes.ini_obra','!=','0');

            if($buscar != '')//Busqueda general
                $lotes = $lotes->where($criterio, '=',$buscar);
            if($buscar2 != '')//Busqueda por etapa
                $lotes = $lotes->where('lotes.etapa_id', '=',$buscar2 );
            if($buscar3 != '')//Busqueda por manzana
                $lotes = $lotes->where('lotes.manzana', 'like', '%'. $buscar3 . '%');
            if($fecha != '' && $fecha2 != '')//Busqueda por fecha de solicitud
                $lotes = $lotes->whereBetween('lotes.ehl_solicitado', [$fecha, $fecha2]);
            if($request->b_inicio != '')//Busqueda por número de inicio
                $lotes = $lotes->where('lotes.num_inicio', '=',$request->b_inicio);
            if($request->b_empresa != '')//Busqueda por empresa constructora
                $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);

        $lotes = $lotes->orderBy('lotes.ehl_solicitado','desc')
                        ->orderBy('fraccionamientos.nombre','lotes.id');

        return $lotes;
    }
    //Función que retorna los registros de lotes enviados a inicio de obra
    public function indexHistorialIniObra(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        //llamada a la funcion que retorna la query necesaria
        $lotes = $this->getHistorialInicios($request);
        $lotes = $lotes->paginate(15);

        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes
        ];
    }
    //Función que retorna los registros de lotes enviados a inicio de obra en excel
    public function excelIniciosObra(Request $request){
        //llamada a la funcion que retorna la query necesaria
        $lotes = $this->getHistorialInicios($request);
        $lotes = $lotes->get();
        //Creación y retorno de los registros encontrados en Excel.
        return Excel::create('Inicios de Obra' , function($excel) use ($lotes){
            $excel->sheet('inicios', function($sheet) use ($lotes){

                $sheet->row(1, [
                    'Fraccionamiento', 'Etapa', 'Modelo', 'Inicio', 'Termino'
                ]);

                $sheet->cells('A1:E1', function ($cells) {
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

                foreach($lotes as $index => $lote) {
                    $cont++;
                    $sheet->row($index+2, [
                        $lote->proyecto,
                        $lote->etapas,
                        $lote->modelo,
                        $lote->ehl_solicitado,
                        $lote->fecha_termino_ventas,
                    ]);
                }
                $num='A1:E' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }

        )->download('xls');

    }

    //Función que retorna un reporte de lotes en excel por fraccionamiento.
    public function excelLotes (Request $request, $fraccionamiento_id)
    {

        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->join('modelos','lotes.modelo_id','=','modelos.id')
        ->join('empresas','lotes.empresa_id','=','empresas.id')
        ->select('fraccionamientos.nombre as proyecto','lotes.etapa_servicios','lotes.manzana','lotes.num_lote',
        'lotes.sublote','lotes.calle','lotes.numero as numero_oficial','lotes.interior','lotes.terreno',
        'lotes.clv_catastral','lotes.id')
        ->where('lotes.fraccionamiento_id', 'like', '%'. $fraccionamiento_id. '%')
        ->get();

        return Excel::create('Lotes de '. $lotes[0]->proyecto , function($excel) use ($lotes){
            $excel->sheet('lotes', function($sheet) use ($lotes){

                $sheet->row(1, [
                    'Etapa de servicios', 'Fraccionamiento', 'Manzana', 'Num. Lote', 'Duplex', 'Calle',
                    'Num. Oficial', 'Interior', 'Superficie de terreno','Clave catastral'
                ]);


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

                $sheet->setColumnFormat(array(
                    'J' => '0'
                ));

                foreach($lotes as $index => $lote) {
                    $cont++;

                    $sheet->row($index+2, [
                        $lote->etapa_servicios,
                        $lote->proyecto,
                        $lote->manzana,
                        $lote->num_lote,
                        $lote->sublote,
                        $lote->calle,
                        $lote->numero_oficial,
                        $lote->interior,
                        $lote->terreno,
                        $lote->clv_catastral,
                    ]);
                }
                $num='A1:J' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }

        )->download('xls');
    }
    //Función que retorna el inventario de lotes habilitados para venta o renta.
    public function indexLotesDisponibles (Request $request)
    {
        if(!$request->ajax())return redirect('/');
        //Llamada a la función que retorna los lotes habilitados.
        $lotes = $this->getInventario($request);
        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 4)
            $lotes = $lotes->paginate(25);
        else
            $lotes = $lotes->paginate(8);

        $tipo = 2;
        //En caso de ser solicitada la petición por un asesor.
        if(Auth::user()->rol_id == 2)
        {
            $vendedor = Vendedor::findOrFail(Auth::user()->id);
            $tipo = $vendedor->tipo;
        }
        if(sizeof($lotes))
        //Se recorren los registros de lotes encontrados
        foreach($lotes as $index => $lote) {
            //Se calcula el precio base en caso de tener un ajuste en precio.
            $lote->precio_base = $lote->precio_base + $lote->ajuste;
            $lote->tipo = $tipo;//Se asigna el tipo para diferenciar de vendedor
            //Se calcula el precio de venta
            $lote->precio_venta= $lote->sobreprecio + $lote->precio_base + $lote->excedente_terreno + $lote->obra_extra;
            $promocion=[];
            //Se busca promoción activa para el lote
            $promocion = Lote_promocion::join('promociones','lotes_promocion.promocion_id','=','promociones.id')
                ->select('promociones.nombre','promociones.v_ini','promociones.v_fin','promociones.id')
                ->where('lotes_promocion.lote_id','=',$lote->id)
                ->where('promociones.v_ini','<=',Carbon::today()->format('ymd'))
                ->where('promociones.v_fin','>=',Carbon::today()->format('ymd'))->get();
            if(sizeof($promocion) > 0){
                $lote->v_iniPromo = $promocion[0]->v_ini;
                $lote->v_finPromo = $promocion[0]->v_fin;
                $lote->promocion = $promocion[0]->nombre;
            }
            else
                $lote->promocion = 'Sin Promoción';
        }

        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes
        ];

    }
    //Función privada que retorna la query para el inventario de loes
    private function getInventario(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        $rolId =  $request->rolId;
        $b_modelo = $request->b_modelo ;
        $b_lote = $request->b_lote;
        $b_apartado = $request->b_apartado;


        //Query para inventario de lotes para los gerentes y administradores
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
                        'lotes.casa_renta', 'lotes.precio_renta', 'modelos.archivo',
                        'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance','lotes.extra','lotes.extra_ext',
                        'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste','lotes.excedente_terreno','lotes.apartado','lotes.obra_extra','lotes.fecha_termino_ventas',
                        'personal.nombre as c_nombre', 'personal.apellidos as c_apellidos', 'lotes.emp_constructora', 'lotes.emp_terreno', 'licencias.archivo_esp',
                        'v.nombre as v_nombre', 'apartados.fecha_apartado');
        //Query para inventario de lotes para asesores
        $queryVendedores = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                    'modelos.nombre as modelo','lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                    'lotes.casa_renta', 'lotes.precio_renta',
                    'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                    'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance','lotes.extra','lotes.extra_ext',
                    'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste', 'lotes.emp_constructora', 'lotes.emp_terreno','licencias.archivo_esp',
                    'lotes.excedente_terreno','lotes.apartado','lotes.obra_extra','lotes.fecha_termino_ventas');

        if($request->tipo <= 2){//Filtro para casas o departamentos
           $query = $query->where('fraccionamientos.tipo_proyecto','=',$request->tipo)->where('lotes.casa_renta','=',0);
           $queryVendedores = $queryVendedores->where('fraccionamientos.tipo_proyecto','=',$request->tipo)->where('lotes.casa_renta','=',0);
            if(Auth::user()->rol_id == 2){
                $vendedor = Vendedor::findOrFail(Auth::user()->id);
                if($vendedor->tipo == 1)
                    $queryVendedores = $queryVendedores->where('lotes.casa_muestra','=',0);
            }
        }
        if($request->tipo == 3){//Filtrado para rentas
            $query = $query->where('lotes.casa_renta','=',1);
            $queryVendedores = $queryVendedores->where('lotes.casa_renta','=',1);
        }

        //Busqueda para gerentes y adminstradores
        if($rolId == 1 || $rolId == 4 || $rolId == 6 || $rolId == 8 || $rolId == 11 || $rolId == 7 ){
            //Lotes habilitados y sin contrato de venta
            $lotes = $query->where('lotes.habilitado','=',1)->where('lotes.contrato','=',0);

            if($b_apartado != ''){
                if($b_apartado == 0)//Los sin apartar
                    $lotes = $lotes->where('lotes.apartado','=',$b_apartado);
                elseif($b_apartado == 1)//Lotes apartados
                    $lotes  = $lotes->where('lotes.apartado','!=',0);
            }

        }
        else{//Busqueda para asesores
            $lotes = $queryVendedores
                        ->where('lotes.habilitado','=',1)
                        ->where('lotes.apartado','=',0)
                        ->where('lotes.contrato','=',0);
        }

        if($buscar != ''){//Busqueda general
            ($criterio != 'modelos.nombre') ? $lotes = $lotes->where($criterio, '=', $buscar)
                :   $lotes = $lotes->where($criterio, 'like', '%'.$buscar.'%') ;
        }
        if($b_modelo != '')//Busqueda por modelo
            $lotes = $lotes->where('modelos.id', '=', $b_modelo );
        if($buscar2 != '')//Busqueda por etapa
            $lotes = $lotes->where('lotes.etapa_id', '=', $buscar2);
        if($buscar3 != '')//Busqueda por manzana
            $lotes = $lotes->where('lotes.manzana', 'like', '%'.$buscar3.'%');
        if($b_lote != '')//Busqueda por numero de lote
            $lotes = $lotes->where('lotes.num_lote', 'like', '%'.$b_lote.'%');
        if($request->casa_muestra != '')//Busqueda para casas muestra
            $lotes = $lotes->where('lotes.casa_muestra','=',$request->casa_muestra);
        if($request->b_empresa != '')//Busqueda por empresa constructora
            $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);

        $lotes = $lotes->orderBy('fraccionamientos.nombre','DESC')
                    ->orderBy('etapas.num_etapa','ASC')
                    ->orderBy('lotes.manzana','ASC')
                    ->orderBy('lotes.num_lote','ASC');

        return $lotes;

    }
    //Función que retorna las etapas con lotes disponibles para venta
    public function select_etapas_disp(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $fraccionamiento = $request->buscar;
        $lotes_etapas = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
                    ->select('etapas.num_etapa as etapa')
                    ->where('lotes.habilitado','=',1)
                    ->where('lotes.apartado','=',0)
                    ->where('lotes.contrato','=',0)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orWhere('lotes.habilitado','=',1)
                    ->where('apartados.vendedor_id','=',Auth::user()->id)
                    ->where('lotes.contrato','=',0)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orderBy('etapas.num_etapa','DESC')
                    ->distinct()
                    ->get();
        return ['lotes_etapas' => $lotes_etapas];
    }
    //Función que retorna las manzanas con lotes disponibles para venta
    public function select_manzanas_disp(Request $request)
    {
        if(!$request->ajax())return redirect('/');

        $etapa = $request->buscar;
        $lotes_manzanas = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
                    ->select('lotes.manzana')
                    ->where('lotes.habilitado','=',1)
                    ->where('lotes.apartado','=',0)
                    ->where('lotes.contrato','=',0)
                    ->where('etapas.num_etapa','=',$etapa)
                    ->orWhere('lotes.habilitado','=',1)
                    ->where('apartados.vendedor_id','=',Auth::user()->id)
                    ->where('lotes.contrato','=',0)
                    ->where('etapas.num_etapa','=',$etapa)
                    ->distinct()
                    ->orderBy('lotes.manzana','DESC')
                    ->get();
        return ['lotes_manzanas' => $lotes_manzanas];
    }
    //Función que retorna los lotes disponibles para venta
    public function select_lotes_disp(Request $request)
    {
        if(!$request->ajax())return redirect('/');

        $manzana = $request->buscar;
        $etapa = $request->buscar2;
        $fraccionamiento = $request->buscar3;
        if(Auth::user()->id == 2){
            $lotes_disp = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
                    ->select('lotes.num_lote','lotes.id', 'lotes.sublote')
                    ->where('lotes.habilitado','=',1)
                    ->where('lotes.apartado','=',0)
                    ->where('lotes.contrato','=',0)
                    ->where('lotes.casa_renta','=',0)
                    ->where('etapas.num_etapa', 'like', '%'. $etapa .'%' )
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orWhere('lotes.habilitado','=',1)
                    ->where('apartados.vendedor_id','=',Auth::user()->id)
                    ->where('lotes.contrato','=',0)
                    ->where('lotes.casa_renta','=',0)
                    ->where('etapas.num_etapa', 'like', '%'. $etapa .'%' )
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orderBy('lotes.num_lote','ASC')
                    ->get();
        }
        else{
            $lotes_disp = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
                    ->select('lotes.num_lote','lotes.id','lotes.sublote')
                    ->where('lotes.habilitado','=',1)
                    ->where('lotes.contrato','=',0)
                    ->where('lotes.casa_renta','=',0)
                    ->where('etapas.num_etapa', 'like', '%'. $etapa .'%' )
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)

                    ->orderBy('lotes.num_lote','ASC')
                    ->get();
        }

        return ['lotes_disp' => $lotes_disp];
    }
    //Función que retorna la información de lote seleccionado para venta.
    public function select_datos_lotes_disp(Request $request){

        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        //Query principal
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
        ->join('licencias','lotes.id','=','licencias.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->join('modelos','lotes.modelo_id','=','modelos.id')
        ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                    'modelos.nombre as modelo','lotes.calle','lotes.numero','lotes.interior','lotes.terreno','lotes.obra_extra',
                    'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                    'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance','lotes.extra','lotes.extra_ext',
                    'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste','lotes.excedente_terreno','lotes.apartado','modelos.terreno as terreno_modelo')
                    ->where('lotes.id','=',$buscar)
                    ->orderBy('fraccionamientos.nombre','DESC')
                    ->orderBy('lotes.etapa_servicios','DESC')->get();
        //Se recorre el arreglo de lotes
        foreach($lotes as $index => $lote) {
            $lote->precio_base = $lote->precio_base + $lote->ajuste;//Se calcula el precio base de venta
            //Se calcula el precio de venta final
            $lote->precio_venta= $lote->sobreprecio + $lote->precio_base + $lote->excedente_terreno + $lote->obra_extra;
            $promocion=[];
            //Se busca promoción activa
            $promocion = Lote_promocion::join('promociones','lotes_promocion.promocion_id','=','promociones.id')
                ->select('promociones.*','promociones.v_ini','promociones.v_fin','promociones.id',
                        'promociones.descuento','promociones.descripcion','promociones.desc_equipamiento')
                ->where('lotes_promocion.lote_id','=',$lote->id)
                ->where('promociones.v_fin','>=',Carbon::today()->format('ymd'))->get();
            if(sizeof($promocion) > 0){
                $lote->v_iniPromo = $promocion[0]->v_ini;
                $lote->v_finPromo = $promocion[0]->v_fin;
                $lote->promocion = $promocion[0]->nombre;
                $lote->descripcionPromo = $promocion[0]->descripcion;
                $lote->descuentoPromo = $promocion[0]->descuento;
                $lote->desc_equipamiento = $promocion[0]->desc_equipamiento;
            }
            else{
                $lote->promocion = 'Sin Promoción';
                $lote->descripcionPromo = NULL;
                $lote->descuentoPromo = 0;
                $lote->desc_equipamiento = NULL;
                }
            //Se calcula tamaño de terreno excedente.
            $lote->terreno_tam_excedente = $lote->terreno - $lote->terreno_modelo;

        }

        return ['lotes' => $lotes];

    }
    //Función que retorna el inventario de lotes habilitados para venta o renta en excel.
    public function exportExcelLotesDisp(Request $request)
    {   //Llamada a la función que retorna los lotes habilitados.
        $lotes = $this->getInventario($request);
        $lotes = $lotes->get();
            //Creación y retorno en excel de lotes habilitados para venta
            if($request->tipo == 1){
                return Excel::create('Relacion lotes disponibles',
                    function($excel) use ($lotes){
                        $excel->sheet('lotes', function($sheet) use ($lotes){

                            $sheet->row(1, [
                                'Proyecto', 'Etapa' ,'Manzana', '# Lote', '% Avance', 'Modelo', 'Calle',
                                '# Oficial', 'Terreno', 'Construccion','Precio base','Terreno excedente','Obra extra',
                                'Sobreprecios','Precio venta','Promocion','Fecha de termino', 'Canal de venta'
                            ]);


                            $sheet->cells('A1:R1', function ($cells) {
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

                            $sheet->setColumnFormat(array(
                                'K' => '$#,##0.00',
                                'L' => '$#,##0.00',
                                'M' => '$#,##0.00',
                                'N' => '$#,##0.00',
                                'O' => '$#,##0.00',
                            ));



                            foreach($lotes as $index => $lote) {
                                if($lote->fecha_termino_ventas == NULL){
                                    $lote->fecha_termino_ventas = 'Por definir';
                                }else{
                                setlocale(LC_TIME,'es_MX.utf8');
                                $mesAño = new Carbon($lote->fecha_termino_ventas);
                                $lote->fecha_termino_ventas = $mesAño->formatLocalized('%B %Y');
                                }
                                if($lote->casa_muestra == 1){
                                    $casaMuestra = 'Casa muestra';


                                }else{
                                    $casaMuestra = '';
                                }

                                $lote->precio_base = $lote->precio_base + $lote->ajuste;
                                $lote->precio_venta= $lote->sobreprecio + $lote->precio_base + $lote->excedente_terreno + $lote->obra_extra;
                                $promocion=[];
                                $promocion = Lote_promocion::join('promociones','lotes_promocion.promocion_id','=','promociones.id')
                                    ->select('promociones.nombre','promociones.v_ini','promociones.v_fin','promociones.id')
                                    ->where('lotes_promocion.lote_id','=',$lote->id)
                                    ->where('promociones.v_fin','>=',Carbon::today()->format('ymd'))->get();
                                if(sizeof($promocion) > 0){
                                    // $lote->v_iniPromo = $promocion[0]->v_ini;
                                    // $lote->v_finPromo = $promocion[0]->v_fin;
                                    $lote->promocion = $promocion[0]->nombre;
                                }
                                else
                                    $lote->promocion = 'Sin Promoción';


                                $cont++;
                                if($lote->sublote !=''){
                                    $loteConSublote = $lote->num_lote.'-'.$lote->sublote;
                                } else{
                                    $loteConSublote = $lote->num_lote;
                                }
                                if($lote->interior !=''){
                                    $loteConInterior = $lote->numero.'-'.$lote->interior;
                                } else{
                                    $loteConInterior = $lote->numero;
                                }

                                $sheet->row($index+2, [
                                    $lote->proyecto,
                                    $lote->etapa,
                                    $lote->manzana,
                                    $loteConSublote,
                                    $lote->avance,
                                    $lote->modelo,
                                    $lote->calle,
                                    $loteConInterior,
                                    $lote->terreno,
                                    $lote->construccion,
                                    $lote->precio_base,
                                    $lote->excedente_terreno,
                                    $lote->obra_extra,
                                    $lote->sobreprecio,
                                    $lote->precio_venta,
                                    $lote->promocion,
                                    $lote->fecha_termino_ventas,
                                    $lote->comentarios,
                                    $casaMuestra

                                ]);
                            }


                            $num='A1:R' . $cont;
                            $sheet->setBorder($num, 'thin');
                            $sheet->cells('S1:S'.$cont, function($cells) {


                                $cells->setFontColor('#ff4040');

                            });
                        });
                    }
                )->download('xls');
            }
            //Creación y retorno en excel de departamentos habilitados para venta
            elseif($request->tipo == 2){
                return Excel::create('Relacion Departamentos disponibles',
                    function($excel) use ($lotes){
                        $excel->sheet('Departamentos', function($sheet) use ($lotes){

                            $sheet->row(1, [
                                'Proyecto', 'Etapa' ,'Nivel', '# Departamento', '% Avance', 'Modelo', 'Calle',
                                '# Oficial', 'Construccion','Precio venta','Promocion','Fecha de termino', 'Canal de venta'
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

                            $sheet->setColumnFormat(array(
                                'J' => '$#,##0.00',
                            ));



                            foreach($lotes as $index => $lote) {
                                if($lote->fecha_termino_ventas == NULL){
                                    $lote->fecha_termino_ventas = 'Por definir';
                                }else{
                                setlocale(LC_TIME,'es_MX.utf8');
                                $mesAño = new Carbon($lote->fecha_termino_ventas);
                                $lote->fecha_termino_ventas = $mesAño->formatLocalized('%B %Y');
                                }
                                if($lote->casa_muestra == 1){
                                    $casaMuestra = 'Departamento muestra';


                                }else{
                                    $casaMuestra = '';
                                }

                                $lote->precio_base = $lote->precio_base + $lote->ajuste;
                                $lote->precio_venta= $lote->sobreprecio + $lote->precio_base + $lote->excedente_terreno + $lote->obra_extra;
                                $promocion=[];
                                $promocion = Lote_promocion::join('promociones','lotes_promocion.promocion_id','=','promociones.id')
                                    ->select('promociones.nombre','promociones.v_ini','promociones.v_fin','promociones.id')
                                    ->where('lotes_promocion.lote_id','=',$lote->id)
                                    ->where('promociones.v_fin','>=',Carbon::today()->format('ymd'))->get();
                                if(sizeof($promocion) > 0){
                                    // $lote->v_iniPromo = $promocion[0]->v_ini;
                                    // $lote->v_finPromo = $promocion[0]->v_fin;
                                    $lote->promocion = $promocion[0]->nombre;
                                }
                                else
                                    $lote->promocion = 'Sin Promoción';


                                $cont++;
                                if($lote->sublote !=''){
                                    $loteConSublote = $lote->num_lote.'-'.$lote->sublote;
                                } else{
                                    $loteConSublote = $lote->num_lote;
                                }
                                if($lote->interior !=''){
                                    $loteConInterior = $lote->numero.'-'.$lote->interior;
                                } else{
                                    $loteConInterior = $lote->numero;
                                }

                                $sheet->row($index+2, [
                                    $lote->proyecto,
                                    $lote->etapa,
                                    $lote->manzana,
                                    $loteConSublote,
                                    $lote->avance,
                                    $lote->modelo,
                                    $lote->calle,
                                    $loteConInterior,
                                    $lote->construccion,
                                    $lote->precio_venta,
                                    $lote->promocion,
                                    $lote->fecha_termino_ventas,
                                    $lote->comentarios,
                                    $casaMuestra
                                ]);
                            }


                            $num='A1:M' . $cont;
                            $sheet->setBorder($num, 'thin');
                            $sheet->cells('N1:N'.$cont, function($cells) {


                                $cells->setFontColor('#ff4040');

                            });
                        });
                    }
                )->download('xls');

            }
            //Creación y retorno en excel de lotes habilitados para renta
            else{
                return Excel::create('Relacion lotes en renta',
                    function($excel) use ($lotes){
                        $excel->sheet('lotes', function($sheet) use ($lotes){

                            $sheet->row(1, [
                                'Proyecto', 'Etapa' ,'Manzana', '# Lote', '% Avance', 'Modelo', 'Calle',
                                '# Oficial', 'Terreno', 'Construccion','Precio renta','Canal de venta'
                            ]);

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
                            $sheet->setColumnFormat(array(
                                'K' => '$#,##0.00',
                            ));

                            foreach($lotes as $index => $lote) {

                                $cont++;
                                if($lote->sublote !=''){
                                    $loteConSublote = $lote->num_lote.'-'.$lote->sublote;
                                } else{
                                    $loteConSublote = $lote->num_lote;
                                }
                                if($lote->interior !=''){
                                    $loteConInterior = $lote->numero.'-'.$lote->interior;
                                } else{
                                    $loteConInterior = $lote->numero;
                                }

                                $sheet->row($index+2, [
                                    $lote->proyecto,
                                    $lote->etapa,
                                    $lote->manzana,
                                    $loteConSublote,
                                    $lote->avance,
                                    $lote->modelo,
                                    $lote->calle,
                                    $loteConInterior,
                                    $lote->terreno,
                                    $lote->construccion,
                                    $lote->precio_renta,
                                    $lote->comentarios

                                ]);
                            }

                            $num='A1:L' . $cont;
                            $sheet->setBorder($num, 'thin');
                        });
                    }
                )->download('xls');
            }


    }
    //Función que retorna los lotes habilitados con su precio base
    public function LotesConPrecioBase(Request $request){
        if(!$request->ajax())return redirect('/');
        //Llamada a la función privada que retorna la query necesaria
        $lotes = $this->getPreciosBase($request);
        $lotes = $lotes->paginate(8);

        if(sizeof($lotes)){
            //Se recorren los resultados obtenidos
            foreach ($lotes as $index => $lote) {
                //Se verifica si el lote cuenta con un contrato firmado
                $contrato = Contrato    ::  join('creditos','contratos.id','=','creditos.id')
                                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                            ->join('expedientes','contratos.id','=','expedientes.id')
                                            ->select('contratos.id')
                                            ->where('creditos.lote_id','=',$lote->id)
                                            ->where('inst_seleccionadas.elegido','=',1)
                                            ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                            ->where('contratos.status','=',3)
                                            ->where('expedientes.liquidado','=',1)
                                            ->get();

                if(sizeof($contrato))
                    $lote->firmado = 1;
            }

        }


        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes
        ];
    }

    public function excelPrecioBase(Request $request){
        //Llamada a la función privada que retorna la query necesaria
        $lotes = $this->getPreciosBase($request);
            $lotes = $lotes->get();
        //Creación y retorno de los resultados obtenidos en excel.
        return Excel::create('lotes', function($excel) use ($lotes){
                $excel->sheet('lotes', function($sheet) use ($lotes){

                    $sheet->row(1, [
                        'Proyecto', 'Etapa', 'Manzana', 'Lote', 'Modelo', 'Avance', 'Status',
                        'Precio base', 'Ajuste'
                    ]);


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

                    $sheet->setColumnFormat(array(
                        'H' => '$#,##0.00',
                        'I' => '$#,##0.00',
                    ));


                    $cont=1;

                    foreach($lotes as $index => $lote) {
                        $cont++;

                        if($lote->contrato == 0)
                            $lote->status = 'Disponible';
                        elseif($lote->contrato == 1 && $lote->firmado == 0)
                            $lote->status = 'Vendida';
                        else
                            $lote->status = 'Individualizada';

                        $sheet->row($index+2, [
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->modelo,
                            $lote->avance.'%',
                            $lote->status,
                            $lote->precio_base,
                            $lote->ajuste,
                        ]);
                    }
                    $num='A1:I' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }

            )->download('xls');
    }

    //Función privada que retorna la query para los lotes con precio base
    private function getPreciosBase(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $modelo = $request->modelo;
        $criterio = $request->criterio;
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->select('lotes.id','lotes.num_lote','lotes.sublote','lotes.precio_base',
                        'lotes.manzana','licencias.avance',
                        'lotes.contrato','lotes.firmado', 'lotes.fecha_termino_ventas',
                        'lotes.ajuste','fraccionamientos.nombre as proyecto',
                        'etapas.num_etapa','modelos.nombre as modelo');
            if($buscar != '')//Busqueda general
                $lotes = $lotes->where($criterio, 'like', '%'. $buscar . '%');
            if($b_etapa != '')//Busqueda por etapa
                $lotes = $lotes->where('etapas.num_etapa', 'like', '%'. $b_etapa . '%');
            if($b_manzana !='')//Busqueda por manzana
                $lotes = $lotes->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
            if($b_lote !='')//Busqueda por numero de lote
                $lotes = $lotes->where('lotes.num_lote', '=', $b_lote);
            if($modelo != '')//Busqueda por modelo
                $lotes = $lotes->where('modelos.nombre', 'like', '%'. $modelo . '%');
        $lotes=$lotes
            ->where('lotes.precio_base','>','0')
            ->orderBy('fraccionamientos.nombre','ASC')
            ->orderBy('etapas.num_etapa','ASC')
            ->orderBy('lotes.num_lote','ASC');

        return $lotes;
    }
    //Función para actualizar el monto de ajuste de precio para un lote
    public function updateAjuste(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $ajuste = Lote::findOrFail($request->id);
        $cambio = $ajuste->ajuste;
        $ajuste->ajuste = $request->ajuste;
        $ajuste->save();
        //Se busca una simulación de crédito ligado al lote sin contrato
        $creditos = Credito::select('id')
        ->where('contrato','=',0)
        ->where('lote_id','=',$request->id)->get();
        //se recorren los resultados obtenidos
        foreach($creditos as $creditosid){
            //Se actualiza la simulación de crédito.
            $credito = Credito::findOrFail($creditosid->id);
            $credito->precio_venta = $credito->precio_venta + $request->ajuste - $cambio;
            $credito->precio_base = $credito->precio_base + $request->ajuste - $cambio;
            $credito->save();
        }
    }

    //Función que retorna los lotes para el modulo asignar modelo
    public function exportExcelAsignarModelo(Request $request){
        //llamadas a la funcion que retorna las query necesarisa
        $lotes = $this->getLotes($request);//Lotes habilitados para ventas
        $rentas = $this->getLotes($request);//Lotes habilitados para rentas
        $deshabilitadas = $this->getLotes($request);//Lotes Deshabilitados
        $lotes = $lotes->where('lotes.habilitado','=', 1)
                        ->where('lotes.casa_renta','=', 0)
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')
                        ->orderBy('lotes.etapa_servicios','ASC')->get();
        $rentas = $rentas->where('lotes.habilitado','=', 1)
                        ->where('lotes.casa_renta','=', 1)
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')
                        ->orderBy('lotes.etapa_servicios','ASC')->get();
        $deshabilitadas = $deshabilitadas->where('lotes.habilitado','=', 0)
                        ->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')
                        ->orderBy('lotes.etapa_servicios','ASC')->get();

        if(sizeOf($lotes)){
            //Se recorren los resultados obtenidos
            foreach($lotes as $index => $lote){
                $lote->indiv = 0;
                //Calculo para precio de venta
                $lote->precio_venta = $lote->precio_base + $lote->obra_extra + $lote->excedente_terreno + $lote->sobreprecio + $lote->ajuste;
                //Se obtiene contrato ligado al lote
                $contrato = Contrato::join('creditos','creditos.id','=','contratos.id')
                                        ->select('creditos.precio_venta')
                                        ->where('creditos.lote_id','=',$lote->id)
                                        ->where('contratos.status','=',3)
                                        ->get();
                //Se obtiene expedinte ligado al lote
                $expedientes = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                            ->join('creditos','creditos.id','=','contratos.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                            ->where('creditos.lote_id','=',$lote->id)
                                            ->where('inst_seleccionadas.elegido','=',1)
                                            ->where('expedientes.liquidado','=',1)
                                            ->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                            ->where('creditos.lote_id','=',$lote->id)
                                            ->where('inst_seleccionadas.elegido','=',1)
                                            ->where('expedientes.fecha_firma_esc','!=',NULL)->get();

                if(sizeOf($expedientes))//EN caso de existir expediente
                    $lote->indiv = 1;//Se indica estatus de individualizado
                if(sizeof($contrato))//En caso de tener un contrato firmado
                    $lote->precio_venta = $contrato[0]->precio_venta;//Se asigna el precio con el que se cerro el contrato
            }
        }
        //Creación y retorno de los resultados en excel.
        return Excel::create('lotes', function($excel) use ($lotes,$rentas,$deshabilitadas){
            $excel->sheet('Habilitados venta', function($sheet) use ($lotes){

                $sheet->row(1, [
                    'Proyecto', 'Etapa comercial', 'Etapa de servicio', 'Manzana', 'Lote', 'Modelo',
                    'Calle','Numero','Terreno', 'Construcción', 'Credito puente', 'Avance','Casa en Venta','Status','Precio de Venta','Canal de ventas',
                     'Emp Constructora', 'Emp Terreno'
                ]);

                $sheet->cells('A1:R1', function ($cells) {
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
                    'O' => '$#,##0.00',
                ));


                $cont=1;

                foreach($lotes as $index => $lote) {
                    $cont++;

                    $status = 'Disponible';

                    if($lote->contrato == 1){
                        $status = 'Vendida';
                    }

                    if($lote->indiv == 1){
                        $status = 'Individualizada';
                    }

                    if($lote->sublote !=''){
                        $loteConSublote = $lote->num_lote.'-'.$lote->sublote;
                    } else{
                        $loteConSublote = $lote->num_lote;
                    }
                    if($lote->interior !=''){
                        $loteConInterior = $lote->numero.'-'.$lote->interior;
                    } else{
                        $loteConInterior = $lote->numero;
                    }
                    if($lote->casa_muestra==0 && $lote->lote_comercial==0 && $lote->habilitado==1){
                        $casaenventa = 'Habilitada';
                    }else{
                        $casaenventa = 'Deshabilitada';
                    }
                    $sheet->row($index+2, [
                        $lote->proyecto,
                        $lote->etapas,
                        $lote->etapa_servicios,
                        $lote->manzana,
                        $loteConSublote,
                        $lote->modelo,
                        $lote->calle,
                        $loteConInterior,
                        $lote->terreno,
                        $lote->construccion,
                        $lote->credito_puente,
                        $lote->avance.'%',
                        $casaenventa,
                        $status,
                        $lote->precio_venta,
                        $lote->comentarios,
                        $lote->emp_constructora,
                        $lote->emp_terreno,

                    ]);
                }
                $num='A1:R' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            $excel->sheet('Habilitados renta', function($sheet) use ($rentas){

                $sheet->row(1, [
                    'Proyecto', 'Etapa comercial', 'Etapa de servicio', 'Manzana', 'Lote', 'Modelo',
                    'Calle','Numero','Terreno', 'Construcción',  'Avance','Canal de ventas'
                ]);


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

                foreach($rentas as $index => $lote) {
                    $cont++;


                    if($lote->sublote !=''){
                        $loteConSublote = $lote->num_lote.'-'.$lote->sublote;
                    } else{
                        $loteConSublote = $lote->num_lote;
                    }
                    if($lote->interior !=''){
                        $loteConInterior = $lote->numero.'-'.$lote->interior;
                    } else{
                        $loteConInterior = $lote->numero;
                    }

                    $sheet->row($index+2, [
                        $lote->proyecto,
                        $lote->etapas,
                        $lote->etapa_servicios,
                        $lote->manzana,
                        $loteConSublote,
                        $lote->modelo,
                        $lote->calle,
                        $loteConInterior,
                        $lote->terreno,
                        $lote->construccion,
                        $lote->avance.'%',
                        $lote->comentarios,

                    ]);
                }
                $num='A1:L' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            $excel->sheet('Deshabilitados', function($sheet) use ($deshabilitadas){

                $sheet->row(1, [
                    'Proyecto', 'Etapa comercial', 'Etapa de servicio', 'Manzana', 'Lote', 'Modelo',
                    'Calle','Numero','Terreno', 'Construcción', 'Credito puente', 'Avance','Casa en Venta','Status','Canal de ventas'
                ]);


                $sheet->cells('A1:O1', function ($cells) {
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

                foreach($deshabilitadas as $index => $lote) {
                    $cont++;

                    $status = 'Disponible';

                    if($lote->contrato == 1){
                        $status = 'Vendida';
                    }

                    if($lote->indiv == 1){
                        $status = 'Individualizada';
                    }

                    if($lote->sublote !=''){
                        $loteConSublote = $lote->num_lote.'-'.$lote->sublote;
                    } else{
                        $loteConSublote = $lote->num_lote;
                    }
                    if($lote->interior !=''){
                        $loteConInterior = $lote->numero.'-'.$lote->interior;
                    } else{
                        $loteConInterior = $lote->numero;
                    }
                    if($lote->casa_muestra==0 && $lote->lote_comercial==0 && $lote->habilitado==1){
                        $casaenventa = 'Habilitada';
                    }else{
                        $casaenventa = 'Deshabilitada';
                    }
                    $sheet->row($index+2, [
                        $lote->proyecto,
                        $lote->etapas,
                        $lote->etapa_servicios,
                        $lote->manzana,
                        $loteConSublote,
                        $lote->modelo,
                        $lote->calle,
                        $loteConInterior,
                        $lote->terreno,
                        $lote->construccion,
                        $lote->credito_puente,
                        $lote->avance.'%',
                        $casaenventa,
                        $status,
                        $lote->comentarios,

                    ]);
                }
                $num='A1:O' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }

        )->download('xls');
    }
    //Función que retorna las etapas para lotes que ya fueron entregados al cliente
    public function select_etapas_entregados(Request $request)
    {
        if(!$request->ajax())return redirect('/');

        $fraccionamiento = $request->buscar;
        $lotes_etapas = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('creditos','lotes.id','=','creditos.lote_id')
                    ->join('contratos','creditos.id','=','contratos.id')
                    ->select('etapas.num_etapa as etapa')
                    ->where('contratos.entregado','=',1)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orderBy('etapas.num_etapa','DESC')
                    ->distinct()
                    ->get();
        return ['lotes_etapas' => $lotes_etapas];
    }
    //Función que retorna  las manzanas para lotes que ya fueron entregados al cliente
    public function select_manzanas_entregados(Request $request)
    {
        if(!$request->ajax())return redirect('/');

        $etapa = $request->buscar;
        $lotes_manzanas = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('creditos','lotes.id','=','creditos.lote_id')
                    ->join('contratos','creditos.id','=','contratos.id')
                    ->select('lotes.manzana')
                    ->where('contratos.entregado','=',1)
                    ->where('etapas.num_etapa','=',$etapa)
                    ->distinct()
                    ->orderBy('lotes.manzana','DESC')
                    ->get();
        return ['lotes_manzanas' => $lotes_manzanas];
    }

    //Función que retorna los lotes que ya fueron entregados al cliente
    public function select_lotes_entregados(Request $request)
    {
        if(!$request->ajax())return redirect('/');

        $manzana = $request->buscar;
        $etapa = $request->buscar2;
        $fraccionamiento = $request->buscar3;

            $lotes_entregados = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('creditos','lotes.id','=','creditos.lote_id')
                    ->join('contratos','creditos.id','=','contratos.id')
                    ->select('lotes.num_lote','lotes.id')
                    ->where('contratos.entregado','=',1)
                    ->where('etapas.num_etapa', '=',  $etapa )
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orderBy('lotes.num_lote','ASC')
                    ->get();

        return ['lotes_entregados' => $lotes_entregados];
    }
    //Función para asignar empresa constructora y empresa de terreno a un lote
    public function asignarEmpresa(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento

        if($request->paso == 1){
            $lote = Lote::findOrFail($request->id);
            $lote->emp_constructora = $request->emp_constructora;
            $lote->emp_terreno = $request->emp_terreno;
            $lote->save();
        }
        else{
            $datos = $request->datosEscritura;
            $licencia = Licencia::findOrFail($request->id);
            $licencia->num_escritura = $datos['num_escritura'];
            $licencia->date_escritura = $datos['date_escritura'];
            $licencia->num_notario = $datos['num_notario'];
            $licencia->distrito_notario = $datos['distrito_notario'];
            $licencia->folio_registro = $datos['folio_registro'];
            $licencia->save();
        }

    }
    //Función que retorna las empresas constructoras
    public function selectEmpresaConstructora(Request $request){
        if(!$request->ajax())return redirect('/');
        $empresas = ['Grupo Constructor Cumbres','CONCRETANIA','SER CUMBRES'];

        return ['empresas'=>$empresas];
    }
    //Función para subir el archivo para colindancias de departamentos
    public function formSubmitColindancias(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $lote = Lote::findOrFail($request->id);

        //$fileName = time().$request->archivo->getClientOriginalName().'.'.$request->archivo->getClientOriginalExtension();
        $fileName = time().$request->archivo->getClientOriginalName();

            $pathAnterior = public_path() . '/files/lotes/colindancias/' . $lote->colindancias;
            File::delete($pathAnterior);

            $moved =  $request->archivo->move(public_path('/files/lotes/colindancias'), $fileName);

            if($moved){
                $lote->colindancias = $fileName;
                $lote->save(); //Insert
            }

            return response()->json(['success'=>'You have successfully upload file.']);
    }
    //Función para descargar el archivo de colindancias
    public function downloadFile($fileName){

        $pathtoFile = public_path().'/files/lotes/colindancias/'.$fileName;
        return response()->file($pathtoFile);
    }

}
