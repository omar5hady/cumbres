<?php

namespace App\Http\Controllers;
use App\Http\Controllers\NotificacionesAvisosController;
use App\Notifications\NotifyAdmin;
use Illuminate\Http\Request;
use App\Lote;
use App\Fraccionamiento;
use App\Modelo;
use App\Credito_puente;
use App\Lote_puente;
use App\Obs_puente;
use App\Doc_puente;
use App\Precio_puente;
use App\Puente_checklist;
use App\Cat_documento;
use App\Pago_puente;
use App\Base_presupuestal;
use App\User;
use App\Avance_urbanizacion;
use Carbon\Carbon;
use DB;
use Auth;
use File;

// Controlador para créditos puente.
class CreditoPuenteController extends Controller
{
    // Función para retornar los lotes sin credito puente
    public function indexSinCredito(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $lotes = $this->getSinCredito($request);

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
    // Función privada para retornar los lotes sin credito puente, segun criterios de busqueda
    private function getSinCredito($request)
    {
        $lotes = Lote::join('fraccionamientos as f', 'lotes.fraccionamiento_id', '=', 'f.id')
            ->join('etapas as e', 'lotes.etapa_id', '=', 'e.id')
            ->join('modelos as m', 'lotes.modelo_id', '=', 'm.id')
            ->select(
                'lotes.id',
                'lotes.manzana',
                'lotes.num_lote',
                'lotes.sublote',
                'lotes.habilitado',
                'lotes.credito_puente',
                'lotes.terreno',
                'lotes.emp_terreno',
                'lotes.emp_constructora',
                'lotes.sublote',
                'lotes.calle',
                'lotes.numero',
                'lotes.interior',
                'm.nombre as modelo',
                'e.num_etapa',
                'f.nombre as proyecto'
            );
            //->where('lotes.contrato', '=', 0);
        //->where('lotes.habilitado','=',1);

        if ($request->puente == '') {
            $lotes = $lotes->where('lotes.credito_puente', '=', NULL);
        } else {
            $lotes = $lotes->where('lotes.credito_puente', '=', $request->puente);
        }

        if ($request->proyecto != '')
            $lotes = $lotes->where('lotes.fraccionamiento_id', '=', $request->proyecto);

        if ($request->etapa != '')
            $lotes = $lotes->where('lotes.etapa_id', '=', $request->etapa);

        if ($request->modelo != '')
            $lotes = $lotes->where('lotes.modelo_id', '=', $request->modelo);

        if ($request->manzana != '')
            $lotes = $lotes->where('lotes.manzana', 'like', '%' . $request->manzana . '%');

        if ($request->lote != '')
            $lotes = $lotes->where('lotes.num_lote', '=', $request->lote);

        if ($request->emp_terreno != '')
            $lotes = $lotes->where('lotes.emp_terreno', '=', $request->emp_terreno);

        if ($request->emp_constructora != '')
            $lotes = $lotes->where('lotes.emp_constructora', '=', $request->emp_constructora);

        $lotes = $lotes->paginate(40);

        return $lotes;
    }

    // Función para retornar los lotes seleccionados
    public function getLotes(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $lotes = Lote::join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
            ->join('modelos', 'lotes.modelo_id', '=', 'modelos.id')
            ->select(
                'lotes.id',
                'lotes.num_lote',
                'lotes.fraccionamiento_id',
                'lotes.etapa_id',
                'lotes.manzana',
                'modelos.nombre as modelo',
                'lotes.modelo_id',
                'etapas.num_etapa',
                'fraccionamientos.nombre as proyecto'
            )
            ->whereIn('lotes.id', $request->id)
            ->orderBy('lotes.num_lote')
            ->get();

        return ['lotes' => $lotes];
    }

    // Función para retornar los prototipos de casa por fraccionamiento
    public function getModelosPuente(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $modelos = Modelo::select('id', 'nombre')->where('fraccionamiento_id', '=', $request->id)
            ->where('nombre', '!=', 'Por Asignar')->orderBy('nombre', 'asc')->get();

        return ['modelos' => $modelos];
    }

    // Función para registrar la solicitud para el crédito puente.
    public function storeSolicitud(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $lotes = $request->lotes;
        $modelos = $request->modelos;
        //Se obtienen el checklist de documentos necesarios para la solicitud.
        $docs = Cat_documento::get();

        try {
            DB::beginTransaction();
            // Registro en la tabla Creditos Puente
            $puente = new Credito_puente();
            $puente->banco = $request->banco;
            $puente->interes = $request->interes;
            $puente->apertura = $request->apertura;
            $puente->total = $request->total;
            $puente->fraccionamiento = $request->fraccionamiento;
            $puente->folio = $request->banco . '-' . $request->cantidad. '-'.$puente->id;
            $puente->save();
            // Se registra comentario para la solicitud.
            $this->nuevaObservacion($puente->id,'Se crea la solicitud del Crédito Puente');
            $puente->folio = $request->banco . '-' . $request->cantidad. '-'.$puente->id;
            $puente->save();

            $id = $puente->id;

            // Se generan los registros de precios para cada modelo en la solicitud
            foreach ($modelos as $index => $m) {
                $precio = new Precio_puente();
                $precio->solicitud_id = $id;
                $precio->modelo_id = $m['id'];
                $precio->precio = $m['precio'];
                $precio->save();
            }

            // Se almacena cada registro del lote en el credito puente
            // Modelo, precio y estatus en proceso.
            foreach ($lotes as $index => $l) {
                $lote = Lote::findOrFail($l['id']);
                $lote->puente_id = $id;
                $lote->credito_puente = 'EN PROCESO';
                $lote->save();

                $lote_puente = new Lote_puente();
                $lote_puente->solicitud_id = $id;
                $lote_puente->lote_id = $l['id'];
                $lote_puente->modelo_id = $l['modelo_id'];
                $lote_puente->precio_p = $l['precio'];
                $lote_puente->save();
            }

            //Se crea el checklist para el crédito puente
            foreach ($docs as $index => $d) {
                $doc = new Puente_checklist();
                $doc->solicitud_id = $id;
                $doc->documento_id = $d['id'];
                $doc->save();
            }

            // Se envia notificación a los involucrados en el proceso.
            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
                        $msj = 'Se ha solicitado un nuevo Credito Puente, folio: '. $puente->folio;

                        $fecha = Carbon::now();
                        $notif = [
                            'notificacion' => [
                                'usuario' => $imagenUsuario[0]->usuario,
                                'foto' => $imagenUsuario[0]->foto_user,
                                'fecha' => $fecha,
                                'msj' => $msj,
                                'titulo' => 'Solic. Crédito Puente'
                            ]
                        ];


                        $aviso = new NotificacionesAvisosController();
                        $user_proyectos = User::select('id')
                                            ->whereIn('usuario',['alemunoz','shady',
                                                                    'cp.martin',
                                                                    // 'javis.mdz',
                                                                    'fede.mon',
                                                                    'dani_puente',
                                                                    'ing_david'
                                                                ])
                                            ->get();

                        $arquitecto = Fraccionamiento::select('arquitecto_id')->where('id','=',$puente->fraccionamiento)->get();
                        if(sizeOf($arquitecto)){
                            if($arquitecto[0]->arquitecto_id!= NULL){
                                $aviso->store($arquitecto[0]->arquitecto_id,$msj);
                                User::findOrFail($arquitecto[0]->arquitecto_id)->notify(new NotifyAdmin($notif));
                            }

                        }

                        if(sizeof($user_proyectos))
                        foreach ($user_proyectos as $index => $user) {
                            $aviso->store($user->id,$msj);
                            User::findOrFail($user->id)->notify(new NotifyAdmin($notif));
                        }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // Función para cancelar la solicitud del crédito puente.
    public function cancelarCredito(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        try {
            DB::beginTransaction();
            // Se cambia a estatus 5 el crédito puente para indicar como cancelado.
            $puente = Credito_puente::findOrFail($request->id);
            $puente->status = 5;
            $puente->interes =
            $puente->save();

            // Se buscan todos los lotes que perteneces a la solicitud a cancelar
            $lotes = Lote::select('id')->where('puente_id','=',$request->id)->get();

            // Se reestablece el lote para ser seleccionado para tramite de credito puente.
            foreach ($lotes as $index => $l) {
                $lote = Lote::findOrFail($l->id);
                $lote->puente_id = NULL;
                $lote->credito_puente = NULL;
                $lote->save();
            }

            // Se notifica la cancelación a las personas involucradas.
            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
                        $msj = 'Se ha cancelado el Credito Puente, folio: '. $puente->folio;

                        $fecha = Carbon::now();
                        $notif = [
                            'notificacion' => [
                                'usuario' => $imagenUsuario[0]->usuario,
                                'foto' => $imagenUsuario[0]->foto_user,
                                'fecha' => $fecha,
                                'msj' => $msj,
                                'titulo' => 'CANCELADO'
                            ]
                        ];

                        $aviso = new NotificacionesAvisosController();
                        $user_proyectos = User::select('id')
                                            ->whereIn('usuario',['alemunoz','shady',
                                                                    'cp.martin','javis.mdz',
                                                                    'fede.mon','ing_david',
                                                                    'eli_hdz','bd_raul',
                                                                    'Herlindo'
                                                                ])
                                            ->get();
                        foreach ($user_proyectos as $index => $user) {
                            $aviso->store($user->id,$msj);
                            User::findOrFail($user->id)->notify(new NotifyAdmin($notif));
                        }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // Función para obtener las solicitudes creadas
    public function indexCreditos(Request $request)
    {
        // Llamada a la función privada que retorna los creditos puente
        $creditos = $this->getCreditosPuente($request);
        $creditos = $creditos->paginate(10);

        foreach ($creditos as $key => $credito) {
            // por cada solicitud se obtienen sus lotes correspondientes.
            $credito->lotes = Lote_puente::where('solicitud_id', '=', $credito->id)->count();

            // Para los creditos que incluyen una fecha de interes, se da formato y calcula los dias transcurridos
            if($credito->fecha_sig_int != NULL){
                $fecha = Carbon::now();
                $fechaFin = new Carbon($credito->fecha_sig_int);

                $credito->diasInt = $fechaFin->diffInDays($fecha,false);
            }

            //Se busca la base presupuestal para ese crédito
            $base = Base_presupuestal::select('id')->where('credito_id','=',$credito->id)->get();
            if(sizeof($base))
                $credito->base_p = 1;
            else
                $credito->base_p = 0;

        }

        return [
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

    // Función para obtener un resumen de créditos puente
    public function resumenCreditos(Request $request){
        // Llamada a la función privada para obtener las solicitudes.
        $creditos = $this->getCreditosPuente($request);
        $creditos = $creditos->paginate(10);

        foreach ($creditos as $key => $credito) {
            $credito->abono = $credito->cargo = $credito->interes = 0;
            // Conteo de la cantidad de lotes en la solicitud
            $credito->lotes = Lote_puente::where('solicitud_id', '=', $credito->id)->count();
            // Se calculan los montos totales por solicitud
            $pago = $this->calculatePagos($credito->id);
            if($pago->abonoTotal != NULL){
                $credito->abono = $pago->abonoTotal;
                $credito->cargo = $pago->cargoTotal;
                $credito->totalInteres = $pago->interesTotal;
            }
        }

        return [
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

    // Función privada para obtener los abonos, cargos e interes.
    private function calculatePagos($credito){
        $pagos = Pago_puente::select(DB::raw("SUM(pagos_puentes.abono) as abonoTotal"),
                        DB::raw("SUM(pagos_puentes.cargo) as cargoTotal"),
                        DB::raw("SUM(pagos_puentes.monto_interes) as interesTotal")
                    )
                    ->where('credito_puente_id', '=', $credito)
                    ->where('pendiente','=',0)
                    ->first();

        return $pagos;
    }

    // Función privada para obtener los créditos puente
    private function getCreditosPuente(Request $request){
        $creditos = Credito_puente::join('fraccionamientos', 'creditos_puente.fraccionamiento', '=', 'fraccionamientos.id')
            ->join('instituciones_financiamiento', 'creditos_puente.banco', '=', 'instituciones_financiamiento.nombre')
            ->select(
                'creditos_puente.*',
                'instituciones_financiamiento.lic',
                'fraccionamientos.nombre as proyecto',
                'fraccionamientos.arquitecto_id'
            );

        if ($request->fraccionamiento != '')
            $creditos = $creditos->where('creditos_puente.fraccionamiento', '=', $request->fraccionamiento);

        // if(Auth::user()->rol_id == 3)
        //     $creditos = $creditos->where('fraccionamientos.arquitecto_id', '=', Auth::user()->id);

        if ($request->folio != '')
            $creditos = $creditos->where('creditos_puente.folio', '=', $request->folio);

        if ($request->status != '')
            $creditos = $creditos->where('creditos_puente.status', '=', $request->status);

        if($request->banco != '')
            $creditos = $creditos->where('creditos_puente.banco', '=', $request->banco);

        $creditos = $creditos->orderBy('creditos_puente.status', 'asc')
            ->orderBy('creditos_puente.id', 'desc');

        return $creditos;

    }

    // Función para obtener los avances de lotes que figuran con un credito puente.
    public function indexCreditosAvances(Request $request){
        // Llamada a la función privada para obtener los creditos puente
        $creditos = $this->getCreditosPuente($request);
        $creditos = $creditos->paginate(10);

        // Se recorre el arreglo de creditos puente
        foreach ($creditos as $key => $credito) {
            //Se obtienen el total de lotes por solicitud
            $credito->lotes = Lote_puente::where('solicitud_id', '=', $credito->id)->count();
            // Se obtienen los avances  de cada lote en la solicitud.
            $lotes = Lote_puente::join('licencias','lotes_puente.lote_id','=','licencias.id')
            ->select('licencias.id','licencias.avance')->where('lotes_puente.solicitud_id', '=', $credito->id);
            $conteo = $lotes;
            $conteo = $conteo->count();
            $lotes = $lotes->get();
            $sumaAvance = 0;
            $promedioUrb = 0;
            foreach ($lotes as $key => $lote) {
                //Se suma todos los avances de cada lote por solicitud
                $sumaAvance += $lote->avance;
                // Se obtiene el total del avance de urbanización
                $avanceUrbanizacion = Avance_urbanizacion::select(
                    DB::raw("SUM(avances_urbanizacion.avance) as totalAvance"))
                ->where('lote_id','=',$lote->id)->get();
                // Se calcula el promedio de avance de urbanización.
                if($avanceUrbanizacion[0]->totalAvance != 0){
                    $conteoUrb = Avance_urbanizacion::where('lote_id','=',$lote->id)->count();
                    $promedioUrb += ($avanceUrbanizacion[0]->totalAvance / $conteoUrb)*100;
                }
            }
            // Se calcula el promedio total de avances.
            $credito->avance = $sumaAvance/$conteo;
            $credito->avanceUrb = $promedioUrb/$conteo;
        }

        return [
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

    // Función para obtener los lotes libres de crédito puente
    public function selectLotes(Request $request)
    {
        // Llamada a la función privada.
        $lotes = $this->getSinCredito($request);
        return ['lotes' => $lotes];
    }

    // Función para obtener los precios de cada modelo en la solicitud del credito puente.
    public function getPreciosModelo(Request $request)
    {
        $modelos = Precio_puente::join('creditos_puente', 'precios_puente.solicitud_id', '=', 'creditos_puente.id')
            ->join('modelos', 'precios_puente.modelo_id', '=', 'modelos.id')
            ->select('precios_puente.id', 'precios_puente.precio', 'precios_puente.modelo_id', 'modelos.nombre as modelo','precios_puente.precio_c')
            ->where('creditos_puente.id', '=', $request->id)
            ->orderBy('precios_puente.precio', 'desc')
            ->orderBy('modelos.nombre', 'asc')
            ->get();


        if(sizeof($modelos)){
            foreach($modelos as $index => $modelo){
                $modelo->total = Lote_puente::join('lotes', 'lotes_puente.lote_id', '=', 'lotes.id')
                ->where('lotes_puente.solicitud_id','=',$request->id)
                ->where('lotes.modelo_id','=',$modelo->modelo_id)->count();
            }
        }

        return ['modelos' => $modelos];
    }

    // Función privada para retornar los lotes que pertenecen a una solicitud de crédito puente
    private function lotesPuente(Request $request){
        $lotes = Lote_puente::join('lotes', 'lotes_puente.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('modelos', 'lotes_puente.modelo_id', '=', 'modelos.id')
            ->join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
            ->select(
                'lotes_puente.id',
                'lotes_puente.modelo_id',
                'lotes_puente.precio_p',
                'lotes_puente.modeloAnt1',
                'lotes_puente.modeloAnt2',
                'lotes_puente.precio1',
                'lotes_puente.precio2',
                'lotes_puente.precio_c',
                'lotes_puente.saldo',
                'lotes_puente.abonado',
                'lotes_puente.cobrado',
                'lotes_puente.liberado',
                'lotes.num_lote',
                'lotes.manzana',
                'lotes.etapa_servicios',
                'lotes_puente.lote_id',
                'lotes.emp_constructora',
                'modelos.nombre as modelo',
                'fraccionamientos.nombre as proyecto',
                'etapas.num_etapa',
                'etapas.factibilidad',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'licencias.avance'
            )
            ->where('lotes_puente.solicitud_id', '=', $request->id)
            ->orderBy('lotes.etapa_id', 'asc')
            ->orderBy('lotes.manzana', 'asc')
            ->orderBy('lotes.num_lote', 'asc')
            ->get();

            return $lotes;
    }

    // Función para calcular el avance de urbanización para todos los lotes en la solicitud.
    public function lotesAvance(Request $request){
        // Llamada a la función privada para obtener los lotes.
        $lotes = $this->lotesPuente($request);
        foreach ($lotes as $key => $lote) {
            // Por cada lote se obtiene la suma de avane de urbanización
            $avanceUrbanizacion = Avance_urbanizacion::select(
                DB::raw("SUM(avances_urbanizacion.avance) as totalAvance"))
                ->where('lote_id','=',$lote->id)->get();
            $lote->avanceUrb = $avanceUrbanizacion[0]->totalAvance;
            $lote->conteoUrb = Avance_urbanizacion::where('lote_id','=',$lote->id)->count();
        }
        return ['lotes' => $lotes];
    }

    // Función para retornar todos los lotes en la solicitud.
    public function getLotesPuente(Request $request)
    {
        // Llamada a la función privada.
        $lotes = $this->lotesPuente($request);
        return ['lotes' => $lotes];
    }

    // Función para retornar el Checklist de la solicitud.
    public function getChecklist(Request $request)
    {
        $checklist = Puente_checklist::join('cat_documentos', 'puente_checklist.documento_id', '=', 'cat_documentos.id')
            ->select(
                'cat_documentos.documento',
                'cat_documentos.categoria',
                'puente_checklist.solicitud_id',
                'puente_checklist.id',
                'puente_checklist.documento_id',
                'puente_checklist.id',
                'puente_checklist.listo'
            )
            ->where('puente_checklist.solicitud_id', '=', $request->id)
            ->orderBy('cat_documentos.categoria', 'asc')
            ->orderBy('puente_checklist.id', 'asc')
            ->get();
        // Conteo de todas las partidas en el checklist que ya se han completado.
        $listos = Puente_checklist::where('puente_checklist.solicitud_id', '=', $request->id)
            ->where('puente_checklist.listo', '=', 1)->count();

        return [
            'checklist' => $checklist,
            'listos' => $listos,
            'total' => $checklist->count()
        ];
    }

    // Función para indicar si una partida del checklist se ha compleatado o no
    public function cambiarChk(Request $request)
    {
        $chk = Puente_checklist::findOrFail($request->id);
        $chk->listo = $request->valor;
        $chk->save();
    }

    // Función para eliminar una partida del checklist que no aplique
    public function deleteChk(Request $request)
    {
        $chk = Puente_checklist::findOrFail($request->id);
        $chk->delete();
    }

    // Función para agregar un lote más a la solicitud del crédito puente.
    public function agregarLote(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $l = $request->lote;
        $id = $request->solicitud_id;

        try {
            DB::beginTransaction();
            // Se cambia el estado del credito puente del lote a EN PROCESO
            $datosLote = Lote::findOrFail($l);
            $datosLote->puente_id = $id;
            $datosLote->credito_puente = 'EN PROCESO';

            // Se obtiene el precio del modelo para ese lote
            $precio = Precio_puente::select('precio')
                ->where('modelo_id', '=', $datosLote->modelo_id)
                ->where('solicitud_id', '=', $id)->first();

            // En caso de no tener registrado el precio, se inicializa en 0
            if ($precio == null)
                $precio = 0;

            // Se registra el lote en el credito puente
            $lote_puente = new Lote_puente();
            $lote_puente->solicitud_id = $id;
            $lote_puente->lote_id = $l;
            $lote_puente->modelo_id = $datosLote->modelo_id;
            $lote_puente->precio_p = $precio->precio;
            $lote_puente->save();
            // Se obtiene el total de lotes para la solicitud
            $totalPuente = Lote_puente::select('id')->where('solicitud_id', '=', $id)->count();
            // Se actualiza el crédito puente
            $credito = Credito_puente::findOrFail($id);
            $credito->total += $precio->precio;
            $credito->folio = $credito->banco . '-' . $totalPuente;
            $credito->save();
            $datosLote->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // Función para eliminar el lote de la solicitud.
    public function eliminarLote(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $l = $request->lote;
        $lp = $request->lp;
        $id = $request->solicitud_id;

        try {
            DB::beginTransaction();
            // Se restablece el lote indicando que no tiene credito puente
            $datosLote = Lote::findOrFail($l);
            $datosLote->puente_id = NULL;
            $datosLote->credito_puente = NULL;
            $datosLote->save();

            //Se accede al registro del lote en el credito puente
            $lote_puente = Lote_puente::findOrFail($lp);
            // Se calcula el numero de lotes en la solicitud
            $totalPuente = Lote_puente::select('id')->where('solicitud_id', '=', $id)->count();
            $totalPuente = (int)$totalPuente - 1;

            // Se actualiza el crédito puente
            $credito = Credito_puente::findOrFail($id);
            $credito->total -= $lote_puente->precio_p;
            $credito->folio = $credito->banco . '-' . $totalPuente;
            $credito->save();
            // Se elimina el registro del lote.
            $lote_puente->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // Función para cambiar el precio de modelo para la solicitud del crédito puente
    public function actualizarPrecio(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        try {
            DB::beginTransaction();
            // Se accede al registro del precio
            $precio = Precio_puente::findOrFail($request->id);
            // Se buscan todos los lotes que corresponden al modelo.
            $lotes = Lote_puente::select('id')
                ->where('modelo_id', '=', $precio->modelo_id)
                ->where('solicitud_id', '=', $precio->solicitud_id)
                ->get();

            // Precio antes de aprobar por banco
            if($request->tipo == 'precio'){
                // Se acutaliza el precio en la tabla de Precios_puente.
                $precio->precio = $request->precio;
                $precio->save();

                if (sizeof($lotes))
                    // Se actualiza el precio para cada uno de los lotes.
                    foreach ($lotes as $index => $l) {
                        $lote = Lote_puente::findOrFail($l->id);
                        $lote->precio_p = $precio->precio;
                        $lote->save();
                    }
            }
            else{
                // Se actualiza el precio para cada uno de los lotes
                $precio->precio_c = $request->precio;
                $precio->save();

                if (sizeof($lotes))
                    foreach ($lotes as $index => $l) {
                        $lote = Lote_puente::findOrFail($l->id);
                        $lote->precio_c = $precio->precio_c;
                        $lote->saldo = $precio->precio_c - $lote->abonado;
                        $lote->save();
                    }

            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // Función para actualizar la solicitud del crédito puente
    public function actualizarSolicitud(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        // Se obtiene el total de lotes ingresados para la solicitud.
        $totalPuente = Lote_puente::select('id')->where('solicitud_id', '=', $request->id)->count();
        $id = $request->id;

        $datos = $request->cabecera;
        // Se accede al registro del crédito puente
        $credito = Credito_puente::findOrFail($request->id);
        $bancoAnt = $credito->banco;
        // Se actualiza el banco
        $credito->banco = $datos['banco'];
        // Se cambia el folio por el nuevo banco y el total de lotes
        $credito->folio = $datos['banco'] . '-' . $totalPuente . '-' . $request->id;
        // Se aplica cuando el banco es diferente al anterior
        if($credito->banco != $bancoAnt){
            // Se agrega un comentario a la solicitud indicando el cambio de institución bancaria
            $this->nuevaObservacion($id, "Se ha cambiado el banco del credito puente");
            // Se envia notificación a los involucrados
            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
                        $msj = 'Se ha cambiado el banco del credito puente folio: '. $credito->folio;
                        $fecha = Carbon::now();
                        $notif = [
                            'notificacion' => [
                                'usuario' => $imagenUsuario[0]->usuario,
                                'foto' => $imagenUsuario[0]->foto_user,
                                'fecha' => $fecha,
                                'msj' => $msj,
                                'titulo' => 'Cambio. Crédito Puente'
                            ]
                        ];

                        $aviso = new NotificacionesAvisosController();
                        $user_proyectos = User::select('id')
                                            ->whereIn('usuario',['alemunoz','shady',
                                                                    'cp.martin',
                                                                    // 'javis.mdz',
                                                                    // 'fede.mon',
                                                                    'ing_david'
                                                                ])
                                            ->get();

                        $arquitecto = Fraccionamiento::select('arquitecto_id')->where('id','=',$credito->fraccionamiento)->get();
                        if(sizeOf($arquitecto)){
                            if($arquitecto[0]->arquitecto_id!= NULL){
                                $aviso->store($arquitecto[0]->arquitecto_id,$msj);
                                User::findOrFail($arquitecto[0]->arquitecto_id)->notify(new NotifyAdmin($notif));
                            }

                        }

                        if(sizeof($user_proyectos))
                        foreach ($user_proyectos as $index => $user) {
                            $aviso->store($user->id,$msj);
                            User::findOrFail($user->id)->notify(new NotifyAdmin($notif));
                        }
        }
        // Se actualiza la información del crédito puente
        $credito->interes = $datos['interes'];
        $credito->total = $request->total;
        $credito->apertura = $datos['apertura'];
        $credito->save();
    }

    // Función para obtener los comentarios del crédito puente.
    public function getObs(Request $request)
    {
        return ['obs' => Obs_puente::where('puente_id', '=', $request->id)->orderBy('id', 'desc')->get()];
    }

    // Función para crear una observación de la solicitud.
    public function storeObs(Request $request)
    {
        // llamada a la función que crea la observación
        $this->nuevaObservacion($request->id,$request->observacion);
    }

    // Función general para almacenar una observación
    public function nuevaObservacion($id,$observacion){
        $obs = new Obs_puente();
        $obs->puente_id = $id;
        $obs->observacion = $observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    // Función que retorna los planos integrados a la solicitud del crédito puente.
    public function getPlanos(Request $request)
    {
        // Planos de edificación
        $edificacion = Doc_puente::select('id', 'descripcion', 'clasificacion', 'archivo', 'created_at', 'fecha_entrega', 'notas', 'user_alta', 'user_confirm', 'fecha_confirm')
            ->where('puente_id', '=', $request->id)
            ->where('clasificacion', '=', 2)->get();
        // Planos de urbanización
        $urbanizacion = Doc_puente::select('id', 'descripcion', 'clasificacion', 'archivo', 'created_at', 'fecha_entrega', 'notas', 'user_alta', 'user_confirm', 'fecha_confirm')
            ->where('puente_id', '=', $request->id)
            ->where('clasificacion', '=', 1)->get();

        return [
            'urbanizacion' => $urbanizacion,
            'edificacion' => $edificacion
        ];
    }

    // Función para obtener las partidas del Checklist que no se encuentren en la solicitud.
    public function getChkSinSolic(Request $request)
    {
        // Se obtienen las partidas que estan actualmente en la solicitud.
        $chkList = Puente_checklist::select('documento_id')->where('solicitud_id', '=', $request->id)->get();
        $listado = [];

        foreach ($chkList as $index => $c) {
            array_push($listado, $c->documento_id);
        }
        // Se buscan las partidas que no esten en la solicitud.
        $chk = Cat_documento::select('id', 'documento', 'categoria')
            ->whereNotIn('id', $listado)
            ->get();
        return ['chk' => $chk];
    }

    // Función para registrar una nueva partida al checklist de la solicitud.
    public function addDocChk(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $chkList = new Puente_checklist();
        $chkList->solicitud_id = $request->id;
        $chkList->documento_id = $request->documento;
        $chkList->save();
    }

    // Función para obtener los modelos presupuestales para la solicitud
    public function getModelosBase(Request $request)
    {
        // Se obtiene la base presupuestal del crédito.
        $base = Base_presupuestal::select('id')->where('credito_id','=',$request->id)->get();

        $t_adquisicion_terreno = $t_estudios_lic = $t_proyectos_disen = $t_edificacion = $t_urbanizacion_infra =
            $t_promocion_publi = $t_gastos_venta = $t_gastos_admin = $t_gastos_notariales =
            $t_gastos_fin_com = $t_int_cpuente_com = $t_cont = $t_venta = 0;


            $modelos = Modelo::join('lotes_puente as lp', 'modelos.id', '=', 'lp.modelo_id')
                ->join('bases_presupuestales as bp', 'modelos.id', '=', 'bp.modelo_id')
                ->select('modelos.nombre', 'bp.*', 'modelos.id')
                ->where('solicitud_id', '=', $request->id);
                if(sizeof($base))
                    $modelos = $modelos->where('bp.credito_id', '=', $request->id);
                else
                    $modelos = $modelos->where('bp.activo', '=', 1);
                $modelos = $modelos->distinct()->get();

        if (sizeof($modelos))
            // Por cada modelo se obtiene el precio base segun lo capturado en la solicitud.
            foreach ($modelos as $index => $m) {
                $lotes = Lote_puente::select('id', 'precio_p')->where('modelo_id', '=', $m->id)
                    ->where('solicitud_id', '=', $request->id);

                $lotes2 = $lotes;
                // Se obtienen los lotes en la solicitud.
                $lotes2 = $lotes2->get();
                $m->precio_puente = $lotes2[0]->precio_p;
                // Se calcula el total de lotes en la solicitud y el total de lotes por modelo.
                $t_cont += $m->cont = $lotes->count();
                // % de incremento
                $m->inc = round($m->precio_puente / $m->valor_venta, 2);
                // Total de valor de venta
                $t_venta += ($m->precio_puente * $m->cont);
                // Calculos para formatos
                $t_adquisicion_terreno += $m->adquisicion_terreno = round($m->cont * ($m->int_pago_terreno + $m->valor_terreno + $m->escritura_gcc + $m->adicional_terreno) * $m->inc, 2);
                $t_estudios_lic += $m->estudios_lic = round($m->cont * ($m->permisos * $m->inc) * 0.7, 2);
                $t_proyectos_disen += $m->proyectos_disen = round($m->cont * ($m->permisos * $m->inc) * 0.3, 2);
                $t_edificacion += $m->edificacion = round($m->cont * ($m->presupuesto_edif + $m->laboratorio + $m->partida_inflacionaria) * $m->inc, 2);
                $t_urbanizacion_infra += $m->urbanizacion_infra = round($m->cont * ($m->presupuesto_urb + $m->equipamiento + $m->fianzas) * $m->inc, 2);
                $t_promocion_publi += $m->promocion_publi = round($m->cont * ($m->gastos_comerc * $m->inc), 2);
                $t_gastos_venta += $m->gastos_venta = round($m->cont * ($m->comicion_venta * $m->inc), 2);
                $t_gastos_admin += $m->gastos_admin = round($m->cont * ($m->gastos_ind_op * $m->inc), 2);
                $t_gastos_notariales += $m->gastos_notariales = round($m->cont * ($m->gastos_esc * $m->inc), 2);
                $t_gastos_fin_com += $m->gastos_fin_com = round($m->cont * ($m->comision_int * $m->inc), 2);
                $t_int_cpuente_com += $m->int_cpuente_com = round($m->cont * ($m->insc_conjunto + $m->int_nafin + $m->int_cpuente) * $m->inc, 2);
            }

        return [
            'modelos' => $modelos,
            't_adquisicion_terreno' => $t_adquisicion_terreno,
            't_estudios_lic' => $t_estudios_lic,
            't_proyectos_disen' => $t_proyectos_disen,
            't_edificacion' => $t_edificacion,
            't_urbanizacion_infra' => $t_urbanizacion_infra,
            't_promocion_publi' => $t_promocion_publi,
            't_gastos_venta' => $t_gastos_venta,
            't_gastos_admin' => $t_gastos_admin,
            't_gastos_notariales' => $t_gastos_notariales,
            't_gastos_fin_com' => $t_gastos_fin_com,
            't_int_cpuente_com' => $t_int_cpuente_com,
            't_cont' => $t_cont,
            't_venta' => $t_venta
        ];
    }

    // Función para registrar el documento de la solicitud.
    public function saveDoc(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $puente = new Doc_puente();
        $puente->puente_id = $request->id;
        $puente->descripcion = $request->descripcion;
        $puente->clasificacion = $request->clasificacion;
        $puente->notas = $request->notas;
        $puente->fecha_entrega = $request->fecha_entrega;
        $puente->user_alta = Auth::user()->usuario;
        $puente->save();
    }

    // Función para indicar la entrega de la solicitud
    public function confirmarEntregaDoc(Request $request){
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $puente = Doc_puente::findOrFail($request->id);
        $puente->user_confirm = Auth::user()->usuario;
        $puente->fecha_confirm = new Carbon();
        $puente->save();
    }

    // Función para indicar el ingreso del expediente tecnico .
    public function ingresarExpTecnico(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        // Se accese al registro del crédito puente
        $credito = Credito_puente::findOrFail($request->id);
        // Se cambia el estatus a 1 para indicar que el credito ya esta ingresado en el banco.
        $credito->status = 1;
        // Se almacena la fecha en que se ingresa
        $credito->fecha_integracion = Carbon::now();
        $credito->save();
        // Registra un comentario sobre el expediente integrado.
        $obs = new Obs_puente();
        $obs->puente_id = $request->id;
        $obs->observacion = 'Expediente integrado.';
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    // Función para registrar la respuesta del banco para la solicitud del crédito
    public function resBanco(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $credito = Credito_puente::findOrFail($request->id);
        // Se crea un nuevo comentario para almacenar la respuesta del banco.
        $obs = new Obs_puente();
        $obs->puente_id = $request->id;
        $obs->usuario = Auth::user()->usuario;

        // Solicitud rechazada
        if ($request->resultado == 0) {
            // Se cambia a estatus 2, para indicar el estatus de rechazado
            $credito->status = 2;
            // Se almacena la fecha de la respuesta.
            $credito->fecha_banco = Carbon::now();
            // Se guarda el motivo indicado por el usuario.
            $credito->motivo_rechazo = $request->comentario;
            // Se accede a los lotes de la solicitud
            $lotes = Lote::select('id')->where('puente_id','=',$request->id)->get();
            // Se liberan los lotes para una nueva solicitud.
            foreach ($lotes as $index => $l) {
                $lote = Lote::findOrFail($l->id);
                $lote->puente_id = NULL;
                $lote->credito_puente = NULL;
                $lote->save();
            }
            $obs->observacion = 'Solicitud rechazada por el banco.';
        // Solicitud aprobada.
        } else {
            // Estatus 3 para indicar el estatus de aprobado.
            $credito->status = 3;
            // Se almacena la fecha de la respuesta.
            $credito->fecha_banco = Carbon::now();
            $credito->motivo_rechazo = $request->comentario;
            // Se guarda el monto de crédito otorgado.
            $credito->credito_otorgado = $request->monto_aprob;
            // En caso de ser crédito bancario BBVA
            if($credito->banco == 'BBVA Bancomer'){
                // Se indica el numero de cuenta
                $credito->num_cuenta = '012970963023154101';
            }
            // Se accede a los lotes de la solicitud
            $lotes = Lote::select('id')->where('puente_id', '=', $request->id)->get();
            $lotesPuente = Lote_puente::select('id')->where('solicitud_id', '=', $request->id)->get();

            // Se actualiza el folio del crédito puente a todos los lotes.
            foreach ($lotes as $index => $lote) {
                $l = Lote::findOrFail($lote->id);
                $l->credito_puente = $credito->folio;
                $l->save();
            }
            // Se calcula el precio del lote para el crédito puente
            foreach ($lotesPuente as $index => $lote) {
                $l = Lote_puente::findOrFail($lote->id);
                $l->precio_c = $l->precio_P * .65;
                $l->saldo = $l->precio_P * .65;
                $l->save();
            }
            $obs->observacion = 'Solicitud aprobada por el banco.';
        }
        $obs->save();
        $credito->save();
    }

    // Función para guardar el numero de cuenta para el crédito puente
    public function numCuenta(Request $request)
    {
        $numCuenta = $request->numCuenta;
        $id = $request->id;

        $credito = Credito_puente::findOrFail($id);
        $credito->num_cuenta = $numCuenta;
        $credito->save();
    }

    // Función para obtener el estado de cuenta del crédito puente.
    public function getEdoCuenta(Request $request)
    {
        // Variable para almacenar todo los cargos al crédito puente.
        $prestado = 0;
        // Se obtienen todos los pagos al crédito puente
        $pagos = Pago_puente::where('credito_puente_id', '=', $request->id)
            ->where('pendiente','=',0)
            ->orderBy('fecha', 'asc')
            ->get();
        // Total de cargos al crédito puente
        $totalPrestado = Pago_puente::select(DB::raw("SUM(pagos_puentes.cargo) as total"))
            ->where('credito_puente_id', '=', $request->id)
            ->where('pendiente','=',0)
            ->orderBy('fecha', 'asc')
            ->first();
        // Se almacena el total de los cargos en la variable.
        if($totalPrestado->total != NULL)
            $prestado = $totalPrestado->total;
        // Se obtienen todos los depositos pendientes por aprobar al crédito puente
        $depCreditos = Pago_puente::join('dep_creditos','pagos_puentes.deposito_id','=','dep_creditos.id')
            ->join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
            ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
            ->join('lotes_puente','creditos.lote_id','=','lotes_puente.lote_id')
            ->select('pagos_puentes.*','lotes_puente.id as lotePuenteId')
            ->where('pagos_puentes.credito_puente_id', '=', $request->id)
            ->where('pagos_puentes.pendiente','=',1)
            ->orderBy('pagos_puentes.fecha', 'asc')
            ->get();

        $saldo = 0;
        // Se obtiene las fechas de los ultimos movimientos abonados y cargados.
        $ultimoAbono = Pago_puente::select('fecha')->where('abono', '!=', 0)->orderBy('fecha', 'desc')->first();
        $ultimoCargo = Pago_puente::select('fecha')->where('cargo', '!=', 0)->orderBy('fecha', 'desc')->first();

        if ($ultimoAbono) {
            $fecha = $ultimoAbono->fecha;
        } elseif ($ultimoCargo) {
            $fecha = $ultimoCargo->fecha;
        } else {
            $fecha = Carbon::now();
            $fecha = $fecha->formatLocalized('%Y-%m-%d');
        }

        // Se recorre el arreglo de pagos realizados al crédito puente.
        foreach ($pagos as $index => $pago) {
            // Se calcula el saldo a la fecha del movimiento.
            $pago->saldo = 0;
            if ($index == 0) {
                $saldo = $pago->saldo = $pago->cargo - $pago->abono;
            } else {
                $saldo = $pago->saldo = $pagos[$index - 1]->saldo + $pago->cargo - $pago->abono;
            }
        }

        $credito = Credito_puente::findOrFail($request->id);

        return ['pagos' => $pagos, 'ultimoAbono' => $fecha,
                'saldo' => $saldo, 'depCreditos' => $depCreditos,
                'prestado' => $prestado,
                'fecha_sig_int' => $credito->fecha_sig_int
            ];
    }

    // Función que retorna la TIIE a la fecha requerida
    public function tiie(Request $request)
    {
        return [$this->getTiie($request->fecha)];
    }

    // Función que recibe la fecha y retorna la tiie por la API de Banxico.
    public function getTiie($fecha)
    {
        //$fecha = Carbon::now();
        $fechaCarbon = new Carbon($fecha);
        $fechaCarbon = $fechaCarbon->formatLocalized('%Y-%m-%d');
        $token = getenv("BANCO_TOKEN");
        $query = 'https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43783/datos/' . $fechaCarbon . '/' . $fechaCarbon . '?token=' . $token;

        $datos = json_decode(file_get_contents($query), true);

        return $datos['bmx']['series'][0]['datos'];
    }

    public function calcularInteres(Request $request)
    {
        $fechaIni = Carbon::parse($request->fechaIni);
        $fechaFin = Carbon::parse($request->fechaFin);

        $tiie = round(($request->tiie + $request->interes), 5);
        $tiie = round(($tiie / 100), 5);
        $saldo = $request->saldo;

        $diasTransc = $fechaFin->diffInDays($fechaIni);

        $intereses = ($saldo * $tiie) / 360;
        $intereses = round(($intereses * $diasTransc), 2);

        return ['interes' => $intereses, 'tiie' => $tiie, 'dias' => $diasTransc];
    }

    /// Calcular intereses correspondientes a un abono en crédito BANCREA /////////
    public function calcularInteresPagos(Request $request){
        $cargos = Pago_puente::select('id','fecha_interes','saldo','porc_interes')
                ->where('tipo', '=', 0)
                ->where('saldo','>',0)
                ->where('credito_puente_id', '=', $request->id)
                ->get();

        $interes = 0;
        $abono = $request->pago;

        // Se calcula el interes correspondiente a los pagos pendientes.
        foreach ($cargos as $key => $cargo) {
            if($abono > 0){
                $fechaIni = Carbon::parse($cargo->fecha_interes);
                $fechaFin = Carbon::parse($request->fecha);

                $tiie = round(($cargo->porc_interes / 100), 5);
                $cargo->diasTransc = $fechaFin->diffInDays($fechaIni);
                $intereses = ($cargo->saldo * $tiie) / 360;
                $interes += round(($intereses * $cargo->diasTransc), 2);

                $abono = $abono - $cargo->saldo;
            }
        }

        return ['interes'=>$interes];
    }
    /// Calcular interes mensual en crédito BANCREA ////////
    public function getInteresePeriodo(Request $request){
        // Se obtienne todos los cargos para el crédito puente.
        $cargos = Pago_puente::select('id','concepto','fecha_interes','saldo','porc_interes as tasa')
                ->where('tipo', '=', 0)
                ->where('saldo','>',0)
                ->where('credito_puente_id', '=', $request->id)
                ->get();

        // Fecha de finalizacion del periodo
        $fechaFin = Carbon::parse($request->fecha_sig_int);
        $intereses = 0;
        // Se recorren los cargos
        foreach ($cargos as $key => $cargo) {
            // Cada cargo tiene su fecha de interes inicial
            $fechaIni = Carbon::parse($cargo->fecha_interes);
            $cargo->interes = 0;
            // Se calcula el tiie correspondiente al cargo
            $tiie = round(($cargo->tasa / 100), 6);
            // Dias transcurridos
            $cargo->diasTransc = $fechaFin->diffInDays($fechaIni);
            // Monto inters diario
            $cargo->interes = ($cargo->saldo * $tiie) / 360;
            // Calculo de monto de interes.
            $cargo->interes = round(($cargo->interes * $cargo->diasTransc), 2);

            $intereses += $cargo->interes;
        }

        return ['interes'=>$intereses, 'cargos'=>$cargos];
    }
    /// Función para registrar el pago de intereses en crédito BANCREA ////////
    public function storeIntereses(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $cargos = $request->datos;
        try {
            DB::beginTransaction();
            // SE REGISTRA EL MOVIMIENTO EN LA BASE DE PAGOS
            $pago = new Pago_puente();
            $pago->credito_puente_id = $request->id;
            $pago->fecha = $request->fecha;
            $pago->concepto = $request->concepto;
            $pago->tipo = $request->tipo;
            $pago->saldo = 0;
            $pago->porc_interes = $request->interes + $request->tiie;
            $pago->fecha_interes = $request->fecha;
            $pago->monto_interes = $request->cantidad;
            $pago->save();

            $new_porcInt = $pago->porc_interes;

            $fecha = new Carbon($request->fecha);

            $primerCargo = Pago_puente::select('fecha')->where('credito_puente_id','=',$request->id)
            ->where('tipo','=',0)
            ->orderBy('fecha','asc')->first();
            $fecha_inicial = new Carbon($primerCargo->fecha);
            $dia_ini = $fecha_inicial->day;
            $dia_fin = $fecha->day;
            $diff = $dia_fin - $dia_ini;

            if($diff != 0)
                $fecha = $fecha->subDays($diff);
            //ACCIONES AL CREDITO BANCARIO GENERAL
            $credito = Credito_puente::findOrFail($request->id);

            $fecha = $fecha->addMonths(1);
            $band = true;
            while ($band == true) {
                if ($fecha->isWeekend())
                    $fecha = $fecha->addDays(1);
                else
                    $band = false;
            }
            $credito->fecha_sig_int = $fecha->format('Y-m-j');
            $credito->save();

            foreach ($cargos as $key => $cargo) {
                $pago = Pago_puente::findOrFail($cargo['id']);
                $pago->fecha_interes = $request->fecha;
                $pago->porc_interes = $new_porcInt;
                $pago->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    /// Función para registrar el pago de intereses en crédito BBVA ////////
    public function storeInteresesBBVA(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        try {
            DB::beginTransaction();
            // SE REGISTRA EL MOVIMIENTO EN LA BASE DE PAGOS
            $pago = new Pago_puente();
            $pago->credito_puente_id = $request->id;
            $pago->fecha = $request->fecha;
            $pago->concepto = $request->concepto;
            $pago->tipo = $request->tipo;
            $pago->saldo = 0;
            $pago->porc_interes = $request->interes + $request->tiie;
            $pago->fecha_interes = $request->fecha;
            $pago->monto_interes = $request->cantidad;
            $pago->save();

            //ACCIONES AL CREDITO BANCARIO GENERAL
            $credito = Credito_puente::findOrFail($request->id);
            $fecha = new Carbon($credito->fecha_sig_int);

            $fecha = $fecha->addMonths(1);
            $credito->fecha_sig_int = $fecha->format('Y-m-j');
            $credito->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    /// Función para guardar cargos en crédito BANCREA ////////
    public function storeCargo(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');

        try {
            DB::beginTransaction();
            // SE REGISTRA EL MOVIMIENTO EN LA BASE DE PAGOS
            $pago = new Pago_puente();
            $pago->credito_puente_id = $request->id;
            $pago->fecha = $request->fecha;
            $pago->concepto = $request->concepto;
            $pago->cargo = $request->monto;
            $pago->fecha_interes = $request->fecha;
            $pago->tipo = $request->tipo;
            $pago->saldo = $request->monto;
            $pago->porc_interes = $request->interes + $request->tiie;
            $pago->save();

            // CALCULO EL MONTO PRESTADO POR EL BANCO HASTA EL MOMENTO
            $cargos = Pago_puente::select(DB::raw("SUM(pagos_puentes.cargo) as total"))
                //->where('concepto', '!=', 'PREPUENTE')
                ->where('credito_puente_id', '=', $request->id)
                ->first();

            if($cargos->total == null) $cargos->total = 0;

            //VERIFICO SI ES EL PRIMER CARGO
            $conteo =  Pago_puente::select('id')
                ->where('tipo','=',0)
                ->where('credito_puente_id', '=', $request->id)
                ->count();

            //ACCIONES AL CREDITO BANCARIO GENERAL
            $credito = Credito_puente::findOrFail($request->id);
            //ACTUALIZO EL MONTO PRESTADO TOTAL AL MOMENTO
            $credito->cobrado = $cargos->total;
            //SI ES EL PRIMER CARGO SE CALCULA EL SIGUIENTE MES A PAGAR DE LOS INTERESES
            if ($conteo == 1) {
                $fecha = new Carbon($request->fecha);
                if($credito->banco == 'Bancrea'){
                    $fecha = $fecha->addMonths(1);
                    $band = true;

                    while ($band == true) {
                        if ($fecha->isWeekend())
                            $fecha = $fecha->addDays(1);
                        else
                            $band = false;
                    }
                }
                if($credito->banco == 'BBVA Bancomer'){
                    if($fecha->day >= 20)
                        $fecha = $fecha->addMonths(1);
                    $fecha->day = 21;
                }
                $credito->fecha_sig_int = $fecha->format('Y-m-d');
            }

            $credito->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /// Función para guardar abonos en crédito BANCREA ////////
    public function storeAbono(Request $request)
    {
        // SE REGISTRA EL MOVIMIENTO EN LA BASE DE PAGOS
        $pago = new Pago_puente();
        $pago->credito_puente_id = $request->id;
        $pago->fecha = $request->fecha;
        $pago->concepto = $request->concepto;
        $pago->abono = $request->monto;
        $pago->tipo = $request->tipo;
        $pago->saldo = 0;
        $pago->monto_interes = $request->interes;
        $pago->save();

        $auxPago = $request->monto;

        // OBTENGO TODOS LOS CARGOS CON SALDO PENDIENTE AL MOMENTO
        $cargos = Pago_puente::select('id','saldo')
        ->where('saldo', '>', '0')
        ->where('credito_puente_id', '=', $request->id)
        ->where('fecha','<=',$request->fecha)
        ->orderBy('fecha','asc')
        ->get();

        if($request->pagoLote == 1){
            $lote = Lote_puente::findOrFail($request->lotePuenteId);
            $lote->saldo -= $pago->abono;
            $lote->abonado += $pago->abono;
            if($lote->saldo == 0){
                $lote->liberado = 1;
            }
            $lote->save();
        }

        // Se le abona el monto a los cargos pendientes
        foreach ($cargos as $key => $c) {
            if($auxPago > 0){
                $cargo = Pago_puente::findOrFail($c->id);
                $cargo->fecha_interes = $request->fecha;
                if($cargo->saldo > $auxPago){
                    $cargo->saldo -= $auxPago;
                    $auxPago = 0;
                }
                else{
                    $auxPago -= $cargo->saldo;
                    $cargo->saldo = 0;
                }
                $cargo->save();
            }

        }
    }

    /// Función para guardar abonos en crédito BBVA ////////
    public function storeAbonoBBVA(Request $request)
    {
        // SE REGISTRA EL MOVIMIENTO EN LA BASE DE PAGOS
        $pago = new Pago_puente();
        $pago->credito_puente_id = $request->id;
        $pago->fecha = $request->fecha;
        $pago->concepto = $request->concepto;
        $pago->abono = $request->monto;
        $pago->tipo = $request->tipo;
        $pago->saldo = 0;
        $pago->monto_interes = $request->interes;
        $pago->save();

        if($request->pagoLote == 1){
            $lote = Lote_puente::findOrFail($request->lotePuenteId);
            $lote->saldo -= $pago->abono;
            $lote->abonado += $pago->abono;
            if($lote->saldo == 0){
                $lote->liberado = 1;
            }
            $lote->save();
        }

        $credito = Credito_puente::findOrFail($request->id);

        if($request->interes > 0){
            //ACCIONES AL CREDITO BANCARIO GENERAL
            $fecha = new Carbon($credito->fecha_sig_int);
            $fecha = $fecha->addMonths(1);
            $credito->fecha_sig_int = $fecha->format('Y-m-j');
        }

        $lotes = Lote_puente::select('liberado')
                ->where('liberado','=',0)
                ->where('solicitud_id','=',$request->id)
                ->get();

        if(sizeof($lotes) == 0)
            $credito->status = 4;

        $credito->save();

    }

    /// Función para guardar abonos en crédito BANCREA ////////
    public function storePago(Request $request)
    {
        try {
            DB::beginTransaction();
            // SE REGISTRA EL MOVIMIENTO EN LA BASE DE PAGOS
            $pago = Pago_puente::findOrFail($request->pagoId);
            $pago->credito_puente_id = $request->id;
            $pago->fecha = $request->fecha;
            $pago->monto_interes = $request->interes;
            $pago->pendiente = 0;
            $pago->save();

            $auxPago = $pago->abono;

            // OBTENGO TODOS LOS CARGOS CON SALDO PENDIENTE AL MOMENTO
            $cargos = Pago_puente::select('id','saldo')
            ->where('saldo', '>', '0')
            ->where('credito_puente_id', '=', $request->id)
            ->where('fecha','<=',$request->fecha)
            ->orderBy('fecha','asc')
            ->get();

            $lote = Lote_puente::findOrFail($request->lotePuenteId);
            $lote->saldo -= $pago->abono;
            $lote->abonado += $pago->abono;
            if($lote->saldo == 0){
                $lote->liberado = 1;
            }
            $lote->save();

            // Se le abona el monto a los cargos pendientes
            foreach ($cargos as $key => $c) {
                //if($auxPago == 0) break;
                $cargo = Pago_puente::findOrFail($c->id);
                $cargo->fecha_interes = $request->fecha;
                if($cargo->saldo > $auxPago){
                    $cargo->saldo -= $auxPago;
                    $auxPago = 0;
                }
                else{
                    $auxPago -= $cargo->saldo;
                    $cargo->saldo = 0;
                }
                $cargo->save();
            }
        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    // Función para obtener los créditos puentes integrados.
    public function selectCreditosPuente(Request $request){
        $creditos = Credito_puente::select('id','folio','fecha_integracion')
                    ->where('fecha_integracion','=',NULL);
                    if($request->fraccionamiento != '')
                        $creditos = $creditos->where('fraccionamiento','=',$request->fraccionamiento);
                    $creditos = $creditos->get();

        return $creditos;
    }

    ///////////////////// FUNCIÓN PARA CARGAR COMPROBANTES DE PAGOS
    public function formSubmit(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $pago = Pago_puente::findOrFail($request->id);

        //$fileName = time().$request->archivo->getClientOriginalName().'.'.$request->archivo->getClientOriginalExtension();
        $fileName = time().$request->archivo->getClientOriginalName();
        // Comprobante de pago
        if($request->tipo == 1){
            $pathAnterior = public_path() . '/files/comprobantes/pagos/' . $pago->doc_pago;
            File::delete($pathAnterior);

            $moved =  $request->archivo->move(public_path('/files/comprobantes/pagos'), $fileName);

            if($moved){
               $pago->doc_pago = $fileName;
               $pago->save(); //Insert
           }
        }
        // Comprobante pago de intereses.
        if($request->tipo == 2){
            $pathAnterior = public_path() . '/files/comprobantes/interes/' . $pago->doc_interes;
            File::delete($pathAnterior);

            $moved =  $request->archivo->move(public_path('/files/comprobantes/interes'), $fileName);

            if($moved){
               $pago->doc_interes = $fileName;
               $pago->save(); //Insert
           }
        }

        return response()->json(['success'=>'You have successfully upload file.']);
    }

    // Función para descargar los comprobantes de pago.
    public function downloadFile($tipo,$fileName){
        if($tipo == 1)
            $pathtoFile = public_path().'/files/comprobantes/pagos/'.$fileName;
        else
            $pathtoFile = public_path().'/files/comprobantes/interes/'.$fileName;
        return response()->file($pathtoFile);
    }

    // Función para actualizar el folio del crédito puente de manera manual.
    public function actualizarFolio(Request $request){
        $credito = Credito_puente::findOrFail($request->id);
        // Se buscan los lotes en el crédito puente.
        $lotes = Lote::select('id')->where('puente_id', '=', $request->id)->get();
        $credito->folio = $request->folio;
        $credito->save();
        // Por cada lote se actualiza el folio.
        foreach ($lotes as $index => $lote) {
            $l = Lote::findOrFail($lote->id);
            $l->credito_puente = $credito->folio;
            $l->save();
        }
    }

    // Función para determinar la fecha de firma del crédito puente.
    public function insertFechaFirma(Request $request){
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');

        $fecha = $request->fecha;
        $id = $request->id;
        $tiie = $request->tiie;
        // Se inserta la fecha y el tiie en la fecha seleccionada.
        $credito = Credito_puente::findOrFail($id);
        $credito->fecha_firma = $fecha;
        $credito->tiie_firma = $tiie;
        $credito->save();
    }

    // Función para obtener los intereses del crédito puente BBVA
    public function getInteresBBVA(Request $request){
        // Se busca la información del crédito puente.
        $credito = Credito_puente::select('id','cobrado','banco','tiie_firma','interes','status','fecha_sig_int')
                ->where('tiie_firma','>',0)
                ->where('status','=',3)
                ->where('cobrado','>',0)
                ->where('banco','=','BBVA Bancomer')
                ->where('id','=',$request->id)
                ->first();
                // Se calcula el porcentaje de interes.
                $credito->porcInteres = ($credito->interes + $credito->tiie_firma)/100;
                // Se obtiene el primer pago para el crédito
                $primerPago = Pago_puente::select('fecha')->where('credito_puente_id','=',$credito->id)
                        ->orderBy('fecha','asc')->first();

                // Fechas para el primer pago, fecha de pago del interes y pago de interes anterior.
                $fechaPrimerCargo = new Carbon($primerPago->fecha);
                $fechaInteres = new Carbon($credito->fecha_sig_int);
                $fechaAnt = new Carbon($credito->fecha_sig_int);
                $fechaAnt = $fechaAnt->subMonths(1);
                // Último dia del mes
                $ultimoDiaMes = new Carbon($credito->fecha_sig_int);
                $ultimoDiaMes = $ultimoDiaMes->endOfMonth();


                if($fechaAnt->lt($fechaPrimerCargo)){
                    $diff = $fechaPrimerCargo->diffInDays($fechaInteres);
                    $fechaIniInt = $primerPago->fecha;
                }
                else{
                    $diff = $fechaAnt->diffInDays($fechaInteres);
                    $fechaIniInt = $fechaAnt->format('Y-m-d');
                }

                $credito->fecha = collect([]);

                for ($i=1; $i <= $diff; $i++) {

                    $fecha = new Carbon($fechaIniInt);
                    $fecha->addDays($i-1);

                    $pagos = Pago_puente::select(DB::raw("SUM(pagos_puentes.abono) as abonos"),DB::raw("SUM(pagos_puentes.cargo) as cargos"))
                        ->where('pagos_puentes.credito_puente_id','=',$credito->id)
                        ->whereBetween('fecha',[$fechaPrimerCargo, $fecha])
                        ->first();

                    $saldo = $pagos->cargos - $pagos->abonos;
                    $interes = ($credito->porcInteres/360)*$saldo;

                    $credito->fecha->push(
                        [
                            'id' => $i,
                            'fecha' => $fecha->format('Y-m-d'),
                            'abonos' => $pagos->abonos,
                            'cargos' => $pagos->cargos,
                            'saldo' => $saldo,
                            'interes' => $credito->interes + $credito->tiie_firma,
                            'monto_interes' => $interes
                        ]
                    );

                    $credito->sumaInteres += $interes;
                }

                $interesesPagados = Pago_puente::select(DB::raw("SUM(pagos_puentes.monto_interes) as interes"))
                        ->where('pagos_puentes.credito_puente_id','=',$credito->id)
                        ->whereBetween('fecha',[$credito->fecha_sig_int, $ultimoDiaMes->format('Y-m-d')])
                        ->first();

                if(  $interesesPagados->interes == NULL )
                {
                    $interesesPagados->interes = 0;
                }


        return['pagos'=> $credito->fecha, 'interes' => $credito->sumaInteres, 'pagado'=> $interesesPagados->interes];
    }


}
