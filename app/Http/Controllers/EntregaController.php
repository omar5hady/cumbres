<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NotifyAdmin;
use App\Http\Controllers\NotificacionesAvisosController;
use App\Fraccionamiento;
use App\Solic_equipamiento;
use App\Obs_expediente;
use App\Entrega;
use App\Obs_entrega;
use App\Obs_exp;
use App\Contrato;
use App\Expediente;
use App\lote;
use App\Credito;
use App\Etapa;
use App\Ini_obra;
use App\User;
use Carbon\Carbon;
use NumerosEnLetras;
use Excel;
use Auth;
use DB;

/*  Controlador para entregas de vivienda.  */
class EntregaController extends Controller
{
    // Función para registrar la petición de entrega de una vivienda en el sistema
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try{
            DB::beginTransaction();
            // Se busca si existe una petición de entrega antes.
            $buscaEntrega = Entrega::select('id')->where('id','=',$request->id)->count();
            if($buscaEntrega==0){ // Si no se encuentra se crea el registro en la Base de datos.
                $entrega = new Entrega();
                $entrega->id = $request->id;
                $entrega->save();
            }
            // Se accede al registro del contrato.
            $contrato = Contrato::findOrFail($request->id);
            if($contrato->integracion == 1){ // Si el contrato ha sido integrado
                $expediente = Expediente::findOrFail($request->id);
                if($expediente->fecha_firma_esc != NULL) // Se verifica que se han firmado escrituras
                    $expediente->postventa = 1; // Se indica que el registro ha sido enviado al dpto. de Postventa.
                $expediente->save();
            }
            // Se accede al registro del crédito para obtener los datos de la venta.
            $credito = Credito::findOrFail($request->id);
            $lote = $credito->num_lote;
            $proyecto = $credito->fraccionamiento;
            $etapa = $credito->etapa;

            // Registro de observación sobre la entrega.
            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->id;
            $observacion->comentario = $request->comentario;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();
            // Registro de observacion para gestoria.
            $observacion_exp = new Obs_expediente();
            $observacion_exp->contrato_id = $request->id;
            $observacion_exp->observacion = 'Se solicito la entrega de la vivienda';
            $observacion_exp->usuario = Auth::user()->usuario;
            $observacion_exp->save();
            // Notificación para usuario de postventa.
            $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = 'Se ha solicitado la entrega del lote: '.$lote. ' del fraccionamiento: '.$proyecto.', etapa: '.$etapa;
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Nueva solicitud de entrega'
                    ]
                ];
                $aviso = new NotificacionesAvisosController();
                $usuarios = User::select('id')
                                ->whereIn('usuario',['ale.hernandez'])
                                ->get();

                foreach ($usuarios as $index => $user) {
                    $aviso->store($user->id,$msj);
                    User::findOrFail($user->id)->notify(new NotifyAdmin($arregloAceptado));
                }

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    //{{{{{{{{{{{{{{{{{{{ SECCION PARA ENTREGA DE VIVIENDAS }}}}}}}}}}}}}}}}}}}
        // Funcion para obtener los registros de contratos pendientes por entregar en excel.
        public function indexPendientesExcel(Request $request){
            $fecha = Carbon::now(); // Fecha actual
            $hoy =  $fecha->toDateString(); // Formato fecha
            $mytime = $fecha->toTimeString(); // Formato hora
            // Llamada a la función privada que retorna la query con los contratos pendientes por entregar.
            $contratos = $this->getPendientes($request);
                $contratos = $contratos
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('entregas.fecha_program','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                ->get();
            // Creación y retorno de excel.
            return Excel::create('Entregas de vivienda', function($excel) use ($contratos){
                    $excel->sheet('Entregas de vivienda', function($sheet) use ($contratos){

                        $sheet->row(1, [
                            '# Ref','Proyecto', 'Etapa', 'Manzana',
                            'Lote','Cliente','Celular', 'Paquete y/o Promocioón', 'Fecha de firma de escrituras',
                            'Fecha entrega programada', 'Hora entrega programada', 'avance'
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

                        foreach($contratos as $index => $entrega) {
                            $cont++;

                            setlocale(LC_TIME, 'es_MX.utf8');
                            $fecha_firma_esc = new Carbon($entrega->fecha_firma_esc);
                            $entrega->fecha_firma_esc = $fecha_firma_esc->formatLocalized('%d de %B de %Y');

                            $fecha_program = new Carbon($entrega->fecha_program);
                            $entrega->fecha_program = $fecha_program->formatLocalized('%d de %B de %Y');

                            $sheet->row($index+2, [
                                $entrega->folio,
                                $entrega->proyecto,
                                $entrega->etapa,
                                $entrega->manzana,
                                $entrega->num_lote,
                                $entrega->nombre_cliente,
                                $entrega->celular,
                                "Paquete: $entrega->paquete | Promoción: $entrega->promocion",
                                $entrega->fecha_firma_esc,
                                "$entrega->fecha_program",
                                "$entrega->hora_entrega_prog",
                                $entrega->avance_lote.'%'
                            ]);
                        }
                        $num='A1:L'.$cont;
                        $sheet->setBorder($num, 'thin');
                    });
                }
            )->download('xls');
        }
        // Funcion para obtener los registros de contratos pendientes por entregar en jSon.
        public function indexPendientes(Request $request){
            if(!$request->ajax())return redirect('/');
            $fecha = Carbon::now(); // Fecha actual
            $hoy =  $fecha->toDateString(); // Formato fecha
            $mytime = $fecha->toTimeString(); // Formato hora
            // Llamada a la función privada que retorna la query con los contratos pendientes por entregar.
            $contratos = $this->getPendientes($request);
            $contratos = $contratos->orderBy('licencias.avance','desc')->orderBy('lotes.fecha_entrega_obra','desc')->paginate(8);
            if(sizeOf($contratos)){
                foreach($contratos as $index => $contrato){
                    // Se obtiene el equipamiento solicitado por cada contrato.
                    $equipamiento = Solic_equipamiento::select('fecha_colocacion','fin_instalacion')
                            ->where('contrato_id','=',$contrato->folio)
                            ->orderBy('fecha_colocacion','desc')->get();
                    if(sizeof($equipamiento)){
                        // Se asigna la fecha de colocación y fecha en que se finalizo la instlación.
                        $contrato->fecha_colocacion = $equipamiento[0]->fecha_colocacion;
                        $contrato->fin_instalacion = $equipamiento[0]->fin_instalacion;
                    }
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
                ],'contratos' => $contratos, 'hora' => $mytime, 'hoy' => $hoy,
            ];
        }
        // Funcion privada que retorna la Query con los contratos pendientes por entregar.
        private function getPendientes(Request $request){
            $criterio = $request->criterio;
            $buscar = $request->buscar;
            $b_etapa = $request->b_etapa;
            $b_manzana = $request->b_manzana;
            $b_lote = $request->b_lote;

            $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                ->leftJoin('expedientes', 'contratos.id','expedientes.id')
                ->join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->select('contratos.id as folio',
                    'contratos.equipamiento',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    'c.celular',
                    'c.f_nacimiento','c.rfc',
                    'c.homoclave','c.direccion','c.colonia','c.cp',
                    'c.telefono','c.email','creditos.num_dep_economicos',
                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                    'clientes.nacionalidad','clientes.sexo','contratos.direccion_empresa',
                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                    'contratos.ext_empresa','contratos.colonia_empresa','etapas.carta_bienvenida', 'etapas.factibilidad',

                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.paquete',
                    'creditos.promocion',
                    'creditos.descripcion_paquete',
                    'creditos.descripcion_promocion',
                    'licencias.avance as avance_lote',
                    'licencias.visita_avaluo',
                    'licencias.foto_predial',
                    'licencias.foto_lic',
                    'licencias.num_licencia',
                    'contratos.fecha_status',
                    'contratos.status',
                    'contratos.equipamiento',
                    'expedientes.fecha_firma_esc',
                    'lotes.fecha_entrega_obra',
                    'lotes.id as loteId',
                    'lotes.sublote',
                    'entregas.fecha_program',
                    'entregas.hora_entrega_prog',
                    'entregas.fecha_entrega_real',
                    'entregas.hora_entrega_real',
                    'entregas.revision_previa',
                    DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                )
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->where('contratos.entregado', '=', 0);

            if($buscar != '')

            switch($criterio){
                case 'c.nombre':{ // Busqueda por cliente
                        $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'entregas.fecha_program':{ // Fecha programada de entrega
                        $contratos = $contratos->whereBetween($criterio, [$buscar, $b_etapa]);
                    break;
                }
                case 'contratos.id':{ // Numero de folio
                        $contratos = $contratos->where($criterio, '=', $buscar);
                    break;
                }
                case 'lotes.fraccionamiento_id':{ // Proyecto
                    $contratos = $contratos->where($criterio, '=', $buscar);
                    if($request->b_desde != '') // Fecha programada
                        $contratos = $contratos->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta]);
                    if($b_etapa != '') // Etapa
                        $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                    if($b_lote != '') // #Lote
                        $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
                    if($b_manzana != '') // Manzana
                        $contratos = $contratos->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                    break;
                }
            }

            return $contratos;

        }
    //{{{{{{{{{{{{{{{{{{{ SECCION PARA ENTREGA DE VIVIENDAS }}}}}}}}}}}}}}}}}}}

    // Función para retornar los comentarios aplicados a las entregas
    public function indexObservaciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $observacion = Obs_entrega::select('comentario','usuario','created_at')
                    ->where('entrega_id','=', $buscar)->orderBy('created_at','desc')->paginate(20);

        return [
            'pagination' => [
                'total'         => $observacion->total(),
                'current_page'  => $observacion->currentPage(),
                'per_page'      => $observacion->perPage(),
                'last_page'     => $observacion->lastPage(),
                'from'          => $observacion->firstItem(),
                'to'            => $observacion->lastItem(),
            ],
            'observacion' => $observacion
        ];
    }

    // Función para registrar un nuevo comentario a la entrega
    public function storeObservacion(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_entrega();
        $observacion->entrega_id = $request->entrega_id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    // Función para indicar la fecha de entrega en obra.
    public function setFechaObra(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            // Se accede al registro del lote
            $lote = Lote::findOrFail($request->lote_id);
            // Fecha de entrega en obra
            $lote->fecha_entrega_obra = $request->fecha_entrega_obra;
            $lote->save();
            // Se genera un comentario para el registro de la entrega.
            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->folio;
            $observacion->comentario = $request->comentario;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    // Función para indicar la fecha de entrega programada.
    public function setFechaProgramada(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $entrega = Entrega::findOrFail($request->folio);
        $entrega->fecha_program = $request->fecha_program;
        //En caso de ser reprogramada por motivo del contratista
        if($request->mot_program == 'Contratista'){
            $entrega->cont_reprogram += 1;
        }
        $entrega->save();

        $credito = Credito::findOrFail($request->folio);

        if($request->observacion != ''){
            // Se genera un nuevo comentario indicando la programación de entrega
            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->folio;
            $observacion->comentario = 'Programada: '.$request->observacion;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();
        }

        // Se envia notificación de entrega a las personas correspondientes
        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = 'Se ha progrmadado la entrega del lote '.$credito->num_lote.' proyecto: '.$credito->fraccionamiento.' etapa: '.$credito->etapa;
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Nueva entrega programada'
                    ]
                ];

                $aviso = new NotificacionesAvisosController();
                $usuarios = User::select('id')
                                ->whereIn('usuario',
                                    ['anny.ls','sajid.m', 'sofia.briones',
                                    'hugo.saavedra'])
                                ->get();

                foreach ($usuarios as $index => $user) {
                    $aviso->store($user->id,$msj);
                    User::findOrFail($user->id)->notify(new NotifyAdmin($arregloAceptado));
                }

    }
    // Función para indicar la hora de entrega programada.
    public function setHoraProgramada(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $entrega = Entrega::findOrFail($request->folio);
        $entrega->hora_entrega_prog = $request->hora_entrega_prog;
        $entrega->save();
    }

    // Función para registrar la finalizción de entrega de una vivienda.
    public function finalizarEntrega(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            // Se accede al registro de la entrega
            $entrega = Entrega::findOrFail($request->id);
            $entrega->fecha_entrega_real = $request->fecha_entrega_real;
            $entrega->hora_entrega_real = $request->hora_entrega_real;
            $entrega->cero_detalles = $request->cero_detalles;
            // Si la entrega se hizo en tiempo y forma como se programo
            if($entrega->fecha_program == $entrega->fecha_entrega_real)
                $entrega->puntualidad = 1;
            $entrega->status = 1;
            $entrega->save();
            // Acceso al registro del contrato.
            $contrato = Contrato::findOrFail($request->id);
            $contrato->entregado = 1;
            $contrato->save();

            if($request->comentario != ''){
                // Se genera un comentario para la entrega.
                $observacion = new Obs_entrega();
                $observacion->entrega_id = $request->id;
                $observacion->comentario = $request->comentario;
                $observacion->usuario = Auth::user()->usuario;
                $observacion->save();
            }

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    // Función que retorna los contratos que han sido entregados en jSon.
    public function indexEntregas(Request $request){
        if(!$request->ajax())return redirect('/');
        //Llamada a función privada que retorna la Query de contratos entregados.
        $contratos = $this->getEntregas($request);
        $contratos = $contratos->orderBy('licencias.avance','desc')
                                ->orderBy('lotes.fecha_entrega_obra','desc')
                                ->paginate(8);

        return [
            'pagination' => [
                'total'         => $contratos->total(),
                'current_page'  => $contratos->currentPage(),
                'per_page'      => $contratos->perPage(),
                'last_page'     => $contratos->lastPage(),
                'from'          => $contratos->firstItem(),
                'to'            => $contratos->lastItem(),
            ],'contratos' => $contratos,
        ];
    }

    // Función que retorna los contratos que han sido entregados en excel.
    public function excelEntregas(Request $request){

        //Llamada a función privada que retorna la Query de contratos entregados.
        $contratos = $this->getEntregas($request);
        $contratos = $contratos->orderBy('licencias.avance','desc')
                                ->orderBy('lotes.fecha_entrega_obra','desc')
                                ->get();

        // Creación y retorno de resultado en Excel.
        return Excel::create('Entregas', function($excel) use ($contratos){
            $excel->sheet('Entregas', function($sheet) use ($contratos){

                $sheet->row(1, [
                    '# Ref','Proyecto', 'Etapa', 'Manzana',
                    '# Lote', 'Calle', '# Oficial','Cliente','# Celular', 'Fecha de firma', 'Fecha entrega (Obra)', 'Paquete y/o Promoción',
                    'Equipamiento','Fecha de entrega','# Reprogramaciones'
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

                foreach($contratos as $index => $entrega) {
                    $cont++;

                    $paquete = 'Paquete: '.$entrega->paquete.' Promoción: '.$entrega->promocion;

                    if($entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 0)
                        $equipamiento='Equipamiento sin solicitarse';

                    elseif ($entrega->descripcion_paquete && !$entrega->descripcion_promocion && $entrega->equipamiento == 0)
                        $equipamiento='Equipamiento sin solicitarse';

                    elseif (!$entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 0)
                        $equipamiento='Equipamiento sin solicitarse';

                    elseif ($entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 1)
                        $equipamiento='En proceso de instalación';

                    elseif ($entrega->descripcion_paquete && !$entrega->descripcion_promocion && $entrega->equipamiento == 1)
                        $equipamiento='En proceso de instalación';

                    elseif (!$entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 1)
                        $equipamiento='En proceso de instalación';

                    elseif ($entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 2)
                        $equipamiento='Equipamiento instalado';

                    elseif ($entrega->descripcion_paquete && !$entrega->descripcion_promocion && $entrega->equipamiento == 2)
                        $equipamiento='Equipamiento instalado';

                    elseif (!$entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 2)
                        $equipamiento='Equipamiento instalado';

                    elseif (!$entrega->descripcion_paquete && !$entrega->descripcion_promocion) $equipamiento='Sin equipamiento';
                    else $equipamiento='Sin equipamiento';

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($entrega->fecha_firma_esc);
                    $entrega->fecha_firma_esc = $fecha1->formatLocalized('%d de %B de %Y');
                    $fecha2 = new Carbon($entrega->fecha_entrega_obra);
                    $entrega->fecha_entrega_obra = $fecha2->formatLocalized('%d de %B de %Y');
                    $fecha3 = new Carbon($entrega->fecha_entrega_real);
                    $entrega->fecha_entrega_real = $fecha3->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $entrega->folio,
                        $entrega->proyecto,
                        $entrega->etapa,
                        $entrega->manzana,
                        $entrega->num_lote,
                        $entrega->calle,
                        $entrega->numero,
                        $entrega->nombre_cliente,
                        $entrega->celular,
                        $entrega->fecha_firma_esc,
                        $entrega->fecha_entrega_obra,
                        $paquete,
                        $equipamiento,
                        $entrega->fecha_entrega_real,
                        $entrega->cont_reprogram

                    ]);
                }
                $num='A1:O' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

    // Función privada que retorna la query con contratos entregados.
    private function getEntregas(Request $request){
        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        // Query principal
        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
            ->join('expedientes','contratos.id','expedientes.id')
            ->join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->select('contratos.id as folio',
                'contratos.equipamiento',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                'c.celular',
                'c.f_nacimiento','c.rfc',
                'c.homoclave','c.direccion','c.colonia','c.cp',
                'c.telefono','c.email','creditos.num_dep_economicos',
                'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                'clientes.nacionalidad','clientes.sexo','contratos.direccion_empresa',
                'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                'contratos.ext_empresa','contratos.colonia_empresa','etapas.carta_bienvenida', 'etapas.factibilidad',

                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.paquete',
                'creditos.promocion',
                'creditos.descripcion_paquete',
                'creditos.descripcion_promocion',
                'licencias.avance as avance_lote',
                'licencias.visita_avaluo',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'contratos.fecha_status',
                'contratos.status',
                'contratos.equipamiento',
                'expedientes.fecha_firma_esc',
                'lotes.fecha_entrega_obra',
                'lotes.id as loteId',
                'entregas.fecha_program',
                'entregas.hora_entrega_prog',
                'entregas.fecha_entrega_real',
                'entregas.hora_entrega_real',
                'entregas.cont_reprogram',
                'entregas.garantia_file',
                DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
            )
            ->where('contratos.status', '!=', 0)
            ->where('contratos.status', '!=', 2)
            ->where('contratos.entregado', '=', 1);

            if($buscar != ''){ // Filtro de busqueda
                switch($criterio){
                    case 'c.nombre':{ // Nombre de cliente.
                            $contratos = $contratos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                        break;
                    }
                    case 'entregas.fecha_entrega_real':{ // Fecha de entrega
                            $contratos = $contratos->whereBetween($criterio, [$buscar, $b_etapa]);
                        break;
                    }
                    case 'contratos.id':{ // # de Folio
                            $contratos = $contratos->where($criterio, '=', $buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{ // Proyecto
                        $contratos = $contratos->where($criterio, '=', $buscar);
                        if($b_etapa != '') // Etapa
                            $contratos = $contratos->where('lotes.etapa_id', '=', $b_etapa);
                        if($b_lote != '') // # lote
                            $contratos = $contratos->where('lotes.num_lote', '=', $b_lote);
                        if($b_manzana != '') // Manzana
                            $contratos = $contratos->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                        break;
                    }
                }
            }

            return $contratos;
    }

    // Función para generar la carta para cuota de mantenimiento.
    public function cartaCuotaMantenimiento($id){
        // Query para obtener los datos necesarios en la carta
        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio',
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'c.celular',
                        'c.f_nacimiento','c.rfc',
                        'c.homoclave','c.direccion','c.colonia','c.cp',
                        'c.telefono','c.email','creditos.num_dep_economicos',
                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                        'clientes.nacionalidad','clientes.sexo',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'etapas.num_cuenta_admin',
                        'etapas.clabe_admin',
                        'etapas.sucursal_admin',
                        'etapas.titular_admin',
                        'etapas.banco_admin',
                        'etapas.costo_mantenimiento',
                        'fraccionamientos.email_administracion',
                        'fraccionamientos.logo_fracc',

                        'lotes.id as loteId',
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.id','=',$id)
                    ->get();
            // Formato de letra para el costo.
            $contratos[0]->costo_mantenimiento_letra = NumerosEnLetras::convertir($contratos[0]->costo_mantenimiento, 'Pesos', true, 'Centavos');
            // Llamada a la vista blade con el formato para la carta de mantenimiento.
            $pdf = \PDF::loadview('pdf.DocsPostVenta.CartaServicios', ['contratos' => $contratos]);
            return $pdf->stream('carta_cuota_mantenimiento.pdf');
    }

    // Función para generar la carta de recepción.
    public function cartaRecepcion($id){
        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio',
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'fraccionamientos.logo_fracc',
                        'fraccionamientos.delegacion',

                        'lotes.id as loteId',
                        'lotes.calle',
                        'lotes.numero'
                    )
                    // ->where('contratos.entregado', '=', 1)
                    ->where('contratos.id','=',$id)
                    ->get();

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $now= Carbon::now();
                    $contratos[0]->fecha_hoy = $now->formatLocalized('%d de %B de %Y');

            $contratos[0]->costo_mantenimiento_letra = NumerosEnLetras::convertir($contratos[0]->costo_mantenimiento, 'Pesos', true, 'Centavos');

            $pdf = \PDF::loadview('pdf.DocsPostVenta.CartaRecepcion', ['contratos' => $contratos]);
            return $pdf->stream('carta_cuota_mantenimiento.pdf');
    }

    // Función para imprimir la poliza de garantia.
    public function polizaDeGarantia(Request $request,$id){
        // Query principal para obtener los datos necesarios.
        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
        ->join('expedientes','contratos.id','expedientes.id')
        ->join('creditos', 'contratos.id', '=', 'creditos.id')
        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
        ->join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
        ->join('licencias', 'lotes.id', '=', 'licencias.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->select('contratos.id as folio',
            'contratos.equipamiento',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
            'c.celular',
            'c.f_nacimiento','c.rfc',
            'c.homoclave','c.direccion','c.colonia','c.cp',
            'c.telefono','c.email','creditos.num_dep_economicos',
            'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
            'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
            'clientes.nacionalidad','clientes.sexo','contratos.direccion_empresa',
            'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
            'contratos.ext_empresa','contratos.colonia_empresa',

            'creditos.fraccionamiento as proyecto',
            'creditos.etapa',
            'creditos.manzana',
            'creditos.num_lote',
            'creditos.paquete',
            'creditos.promocion',
            'creditos.descripcion_paquete',
            'creditos.descripcion_promocion',
            'licencias.avance as avance_lote',
            'licencias.visita_avaluo',
            'contratos.fecha_status',
            'contratos.status',
            'contratos.equipamiento',
            'expedientes.fecha_firma_esc',
            'lotes.fecha_entrega_obra',
            'lotes.id as loteId',
            'lotes.calle',
            'lotes.numero',
            'fraccionamientos.ciudad as ciudadFraccionamiento',
            'fraccionamientos.delegacion',
            'entregas.fecha_program',
            'entregas.hora_entrega_prog',
            'entregas.fecha_entrega_real',
            'entregas.hora_entrega_real',
            'fraccionamientos.logo_fracc',
            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
        )
        ->where('contratos.id','=',$id)
        ->orderBy('licencias.avance','desc')
        ->orderBy('lotes.fecha_entrega_obra','desc')
        ->get();
        // Llamada a la vista blade con el formato.
        $pdf = \PDF::loadview('pdf.DocsPostVenta.PolizaDeGarantia', ['contratos' => $contratos, 'tiempo' => $request->tiempo]);
        return $pdf->stream('poliza_de_garantia.pdf');
    }

    // Función para imprimir la carta de recepción de alarma.
    public function cartaAlarma(Request $request){
        // Query principal para obtener los datos necesarios.
        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
        ->join('creditos', 'contratos.id', '=', 'creditos.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->select('contratos.id as folio',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente")
        )
        ->where('contratos.id','=',$request->id)
        ->get();
        // Llamada a la vista blade.
        $pdf = \PDF::loadview('pdf.DocsPostVenta.CartaAlarma', ['alarma' => $contratos]);
        // Retorno de la carta generada.
        return $pdf->stream('carta_alarma.pdf');
    }

    // Función que retorna la fecha de instalación para equipamientos.
    public function select_ultimaFecha_instalacion(Request $request){
        if(!$request->ajax())return redirect('/');
        $contrato = $request->id;
        // Query principal.
        $fecha_ultima = Entrega::join('contratos','entregas.id','contratos.id')
        ->leftjoin('solic_equipamientos','contratos.id','=','solic_equipamientos.contrato_id')
        ->select('solic_equipamientos.fin_instalacion', 'solic_equipamientos.fecha_colocacion')
        ->orderBy('solic_equipamientos.fin_instalacion','DESC')
        ->where('solic_equipamientos.contrato_id','=',$contrato)->get();
        return ['fecha_ultima' => $fecha_ultima];
    }

    // Función para registrar los datos necesarios para la administración de la privada
    public function setDatosCuenta (Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $datoCuentas = Etapa::findOrFail($request->id);
        $datoCuentas->num_cuenta_admin = $request->num_cuenta_admin;
        $datoCuentas->clabe_admin = $request->clabe_admin;
        $datoCuentas->sucursal_admin = $request->sucursal_admin;
        $datoCuentas->titular_admin = $request->titular_admin;
        $datoCuentas->banco_admin = $request->banco_admin;
        $datoCuentas->save();
    }

    // Funcion para actualizar el correo de la administración.
    public function actualizarCorreoAdmin(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $correo = Fraccionamiento::findOrFail($request->id);
        $correo->email_administracion = $request->correo;
        $correo->save();
    }

    // Función que retorna los datos de una entrega.
    public function getDatosLoteEntregado(Request $request){
        if(!$request->ajax() )return redirect('/');
        $datosLote = Entrega::join('contratos','entregas.id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio', 'creditos.modelo','creditos.fraccionamiento',
                        'creditos.etapa','creditos.manzana','creditos.num_lote',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'c.celular',
                        DB::raw("CONCAT(lotes.calle,' Num.',lotes.numero) AS direccion"),
                        'lotes.manzana', 'lotes.aviso','lotes.id as lote_id',
                        DB::raw('DATEDIFF(current_date,entregas.fecha_entrega_real) as diferencia'
                        )
                    )
                    ->where('lotes.id','=',$request->lote)->get();

        $datosContratista = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
                    ->select('contratistas.id','contratistas.nombre')
                    ->where('ini_obras.clave','=',$datosLote[0]->aviso)->get();

        return ['datosLote' => $datosLote,
                'datosContratista' => $datosContratista
                ];
    }

    // Función para subir archivo fiscal para ventas.
    public function formSubmitPoliza(Request $request, $id){

        $contrato = Entrega::findOrFail($id);
        if( $contrato->garantia_file != NULL){
            $pathAnterior = public_path() . '/files/entregas/polizas/' . $contrato->garantia_file;
            File::delete($pathAnterior);
        }

        $fileName = $request->archivo->getClientOriginalName();
        $moved =  $request->archivo->move(public_path('/files/entregas/polizas/'), $id.$fileName);

        if($moved){
            $contrato->garantia_file = $id.$fileName;
            $contrato->save(); //Insert
        }

    	return response()->json(['success'=>'You have successfully upload file.']);
    }

    // Función que descarga el archivo fiscal de una venta.
    public function downloadFilePoliza($fileName)
    {
        $pathtoFile = public_path() . '/files/entregas/polizas/' . $fileName;
        return response()->file($pathtoFile);
    }

}
