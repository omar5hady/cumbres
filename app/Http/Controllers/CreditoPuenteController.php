<?php

namespace App\Http\Controllers;

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

use App\Notifications\NotifyAdmin;
use App\Http\Controllers\NotificacionesAvisosController;

class CreditoPuenteController extends Controller
{
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

    public function getModelosPuente(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $modelos = Modelo::select('id', 'nombre')->where('fraccionamiento_id', '=', $request->id)
            ->where('nombre', '!=', 'Por Asignar')->orderBy('nombre', 'asc')->get();

        return ['modelos' => $modelos];
    }

    public function storeSolicitud(Request $request)
    {

        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');

        $lotes = $request->lotes;
        $modelos = $request->modelos;

        $docs = Cat_documento::get();

        try {
            DB::beginTransaction();
            $puente = new Credito_puente();
            $puente->banco = $request->banco;
            $puente->interes = $request->interes;
            $puente->apertura = $request->apertura;
            $puente->total = $request->total;
            $puente->fraccionamiento = $request->fraccionamiento;
            $puente->folio = $request->banco . '-' . $request->cantidad. '-'.$puente->id;
            $puente->save();

            $this->nuevaObservacion($puente->id,'Se crea la solicitud del Crédito Puente');

            $puente->folio = $request->banco . '-' . $request->cantidad. '-'.$puente->id;
            $puente->save();

            $id = $puente->id;

            foreach ($modelos as $index => $m) {
                $precio = new Precio_puente();
                $precio->solicitud_id = $id;
                $precio->modelo_id = $m['id'];
                $precio->precio = $m['precio'];
                $precio->save();
            }

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

            foreach ($docs as $index => $d) {
                $doc = new Puente_checklist();
                $doc->solicitud_id = $id;
                $doc->documento_id = $d['id'];
                $doc->save();
            }

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
                                                                    // 'fede.mon',
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

    public function cancelarCredito(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');

        try {
            DB::beginTransaction();
            $puente = Credito_puente::findOrFail($request->id);
            $puente->status = 5;
            $puente->interes = 
            $puente->save();

            $lotes = Lote::select('id')->where('puente_id','=',$request->id)->get();

            foreach ($lotes as $index => $l) {
                $lote = Lote::findOrFail($l->id);
                $lote->puente_id = NULL;
                $lote->credito_puente = NULL;
                $lote->save();
            }

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

    public function indexCreditos(Request $request)
    {
        $creditos = $this->getCreditosPuente($request);
        $creditos = $creditos->paginate(10);

        foreach ($creditos as $key => $credito) {
            $credito->lotes = Lote_puente::where('solicitud_id', '=', $credito->id)->count();

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

    public function resumenCreditos(Request $request){
        $creditos = $this->getCreditosPuente($request);
        $creditos = $creditos->paginate(10);

        foreach ($creditos as $key => $credito) {
            $credito->abono = $credito->cargo = $credito->interes = 0;
            $credito->lotes = Lote_puente::where('solicitud_id', '=', $credito->id)->count();
            
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

        if(Auth::user()->rol_id == 3)
            $creditos = $creditos->where('fraccionamientos.arquitecto_id', '=', Auth::user()->id);

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

    public function indexCreditosAvances(Request $request){
        $creditos = $this->getCreditosPuente($request);
        $creditos = $creditos->paginate(10);

        foreach ($creditos as $key => $credito) {
            $credito->lotes = Lote_puente::where('solicitud_id', '=', $credito->id)->count();
            $lotes = Lote_puente::join('licencias','lotes_puente.lote_id','=','licencias.id')
            ->select('licencias.id','licencias.avance')->where('lotes_puente.solicitud_id', '=', $credito->id);
            $conteo = $lotes;
            $conteo = $conteo->count();
            $lotes = $lotes->get();
            $sumaAvance = 0;
            $promedioUrb = 0;
            foreach ($lotes as $key => $lote) {
                $sumaAvance += $lote->avance;
                $avanceUrbanizacion = Avance_urbanizacion::select(
                    DB::raw("SUM(avances_urbanizacion.avance) as totalAvance"))
                ->where('lote_id','=',$lote->id)->get();
                
                if($avanceUrbanizacion[0]->totalAvance != 0){
                    $conteoUrb = Avance_urbanizacion::where('lote_id','=',$lote->id)->count();
                    $promedioUrb += ($avanceUrbanizacion[0]->totalAvance / $conteoUrb)*100;
                }
            }
            $credito->avance = $sumaAvance/$conteo;
            $credito->avanceUrb = $promedioUrb/$conteo;
            // $credito->conteo = $conteo;
            // $credito->lotes = $lotes;
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

    public function selectLotes(Request $request)
    {
        $lotes = $this->getSinCredito($request);
        return ['lotes' => $lotes];
    }

    public function getPreciosModelo(Request $request)
    {
        $modelos = Precio_puente::join('creditos_puente', 'precios_puente.solicitud_id', '=', 'creditos_puente.id')
            ->join('modelos', 'precios_puente.modelo_id', '=', 'modelos.id')
            ->select('precios_puente.id', 'precios_puente.precio', 'precios_puente.modelo_id', 'modelos.nombre as modelo','precios_puente.precio_c')
            ->where('creditos_puente.id', '=', $request->id)
            ->orderBy('precios_puente.precio', 'desc')
            ->orderBy('modelos.nombre', 'asc')
            ->get();

        return ['modelos' => $modelos];
    }

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

    public function lotesAvance(Request $request){
        $lotes = $this->lotesPuente($request);

        foreach ($lotes as $key => $lote) {

            $avanceUrbanizacion = Avance_urbanizacion::select(
                DB::raw("SUM(avances_urbanizacion.avance) as totalAvance"))
            ->where('lote_id','=',$lote->id)->get();
            $lote->avanceUrb = $avanceUrbanizacion[0]->totalAvance;
            $lote->conteoUrb = Avance_urbanizacion::where('lote_id','=',$lote->id)->count();
            
        }

        return ['lotes' => $lotes];
    }

    public function getLotesPuente(Request $request)
    {
        $lotes = $this->lotesPuente($request);
        return ['lotes' => $lotes];
    }

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

        $listos = Puente_checklist::where('puente_checklist.solicitud_id', '=', $request->id)
            ->where('puente_checklist.listo', '=', 1)->count();

        return [
            'checklist' => $checklist,
            'listos' => $listos,
            'total' => $checklist->count()
        ];
    }

    public function cambiarChk(Request $request)
    {
        $chk = Puente_checklist::findOrFail($request->id);
        $chk->listo = $request->valor;
        $chk->save();
    }

    public function deleteChk(Request $request)
    {
        $chk = Puente_checklist::findOrFail($request->id);
        $chk->delete();
    }

    public function agregarLote(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $l = $request->lote;
        $id = $request->solicitud_id;

        try {
            DB::beginTransaction();
            $datosLote = Lote::findOrFail($l);
            $datosLote->puente_id = $id;
            $datosLote->credito_puente = 'EN PROCESO';


            $precio = Precio_puente::select('precio')
                ->where('modelo_id', '=', $datosLote->modelo_id)
                ->where('solicitud_id', '=', $id)->first();

            if ($precio == null)
                $precio = 0;

            $lote_puente = new Lote_puente();
            $lote_puente->solicitud_id = $id;
            $lote_puente->lote_id = $l;
            $lote_puente->modelo_id = $datosLote->modelo_id;
            $lote_puente->precio_p = $precio->precio;
            $lote_puente->save();

            $totalPuente = Lote_puente::select('id')->where('solicitud_id', '=', $id)->count();

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

    public function eliminarLote(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $l = $request->lote;
        $lp = $request->lp;
        $id = $request->solicitud_id;

        try {
            DB::beginTransaction();
            $datosLote = Lote::findOrFail($l);
            $datosLote->puente_id = NULL;
            $datosLote->credito_puente = NULL;
            $datosLote->save();

            $lote_puente = Lote_puente::findOrFail($lp);
            $totalPuente = Lote_puente::select('id')->where('solicitud_id', '=', $id)->count();
            $totalPuente = (int)$totalPuente - 1;

            $credito = Credito_puente::findOrFail($id);
            $credito->total -= $lote_puente->precio_p;
            $credito->folio = $credito->banco . '-' . $totalPuente;
            $credito->save();

            $lote_puente->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function actualizarPrecio(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        try {
            DB::beginTransaction();
            $precio = Precio_puente::findOrFail($request->id);

            $lotes = Lote_puente::select('id')
                ->where('modelo_id', '=', $precio->modelo_id)
                ->where('solicitud_id', '=', $precio->solicitud_id)
                ->get();

            if($request->tipo == 'precio'){
                $precio->precio = $request->precio;
                $precio->save();

                if (sizeof($lotes))
                    foreach ($lotes as $index => $l) {
                        $lote = Lote_puente::findOrFail($l->id);
                        $lote->precio_p = $precio->precio;
                        $lote->save();
                    }
            }
            else{
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

    public function actualizarSolicitud(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $totalPuente = Lote_puente::select('id')->where('solicitud_id', '=', $request->id)->count();
        $id = $request->id;

        $datos = $request->cabecera;
        $credito = Credito_puente::findOrFail($request->id);
        $bancoAnt = $credito->banco;
        $credito->banco = $datos['banco'];
        $credito->folio = $datos['banco'] . '-' . $totalPuente . '-' . $request->id;

        if($credito->banco != $bancoAnt){
            $this->nuevaObservacion($id, "Se ha cambiado el banco del credito puente");
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

        $credito->interes = $datos['interes'];
        $credito->total = $request->total;
        $credito->apertura = $datos['apertura'];
        $credito->save();
        //$credito->save();
    }

    public function getObs(Request $request)
    {
        return ['obs' => Obs_puente::where('puente_id', '=', $request->id)->orderBy('id', 'desc')->get()];
    }

    public function storeObs(Request $request)
    {
        $this->nuevaObservacion($request->id,$request->observacion);
    }

    public function nuevaObservacion($id,$observacion){
        $obs = new Obs_puente();
        $obs->puente_id = $id;
        $obs->observacion = $observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    public function getPlanos(Request $request)
    {
        $edificacion = Doc_puente::select('id', 'descripcion', 'clasificacion', 'archivo', 'created_at', 'fecha_entrega', 'notas', 'user_alta', 'user_confirm', 'fecha_confirm')
            ->where('puente_id', '=', $request->id)
            ->where('clasificacion', '=', 2)->get();

        $urbanizacion = Doc_puente::select('id', 'descripcion', 'clasificacion', 'archivo', 'created_at', 'fecha_entrega', 'notas', 'user_alta', 'user_confirm', 'fecha_confirm')
            ->where('puente_id', '=', $request->id)
            ->where('clasificacion', '=', 1)->get();

        return [
            'urbanizacion' => $urbanizacion,
            'edificacion' => $edificacion
        ];
    }

    public function getChkSinSolic(Request $request)
    {
        $chkList = Puente_checklist::select('documento_id')->where('solicitud_id', '=', $request->id)->get();
        $listado = [];

        foreach ($chkList as $index => $c) {
            array_push($listado, $c->documento_id);
        }

        $chk = Cat_documento::select('id', 'documento', 'categoria')
            ->whereNotIn('id', $listado)
            ->get();
        return ['chk' => $chk];
    }

    public function addDocChk(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $chkList = new Puente_checklist();
        $chkList->solicitud_id = $request->id;
        $chkList->documento_id = $request->documento;
        $chkList->save();
    }

    public function getModelosBase(Request $request)
    {

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
            foreach ($modelos as $index => $m) {
                $lotes = Lote_puente::select('id', 'precio_p')->where('modelo_id', '=', $m->id)
                    ->where('solicitud_id', '=', $request->id);

                $lotes2 = $lotes;

                $lotes2 = $lotes2->get();
                $m->precio_puente = $lotes2[0]->precio_p;
                $t_cont += $m->cont = $lotes->count();

                $m->inc = round($m->precio_puente / $m->valor_venta, 2);
                $t_venta += ($m->precio_puente * $m->cont);

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

    public function confirmarEntregaDoc(Request $request){
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $puente = Doc_puente::findOrFail($request->id);
        $puente->user_confirm = Auth::user()->usuario;
        $puente->fecha_confirm = new Carbon();
        $puente->save();
    }

    public function ingresarExpTecnico(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $credito = Credito_puente::findOrFail($request->id);
        $credito->status = 1;
        $credito->fecha_integracion = Carbon::now();
        $credito->save();

        $obs = new Obs_puente();
        $obs->puente_id = $request->id;
        $obs->observacion = 'Expediente integrado.';
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    public function resBanco(Request $request)
    {
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');
        $credito = Credito_puente::findOrFail($request->id);
        $obs = new Obs_puente();
        $obs->puente_id = $request->id;
        $obs->usuario = Auth::user()->usuario;

        if ($request->resultado == 0) {

            $credito->status = 2;
            $credito->fecha_banco = Carbon::now();
            $credito->motivo_rechazo = $request->comentario;

            $lotes = Lote::select('id')->where('puente_id','=',$request->id)->get();

            foreach ($lotes as $index => $l) {
                $lote = Lote::findOrFail($l->id);
                $lote->puente_id = NULL;
                $lote->credito_puente = NULL;
                $lote->save();
            }

            $obs->observacion = 'Solicitud rechazada por el banco.';
        } else {

            $credito->status = 3;
            $credito->fecha_banco = Carbon::now();
            $credito->motivo_rechazo = $request->comentario;
            $credito->credito_otorgado = $request->monto_aprob;
            if($credito->banco == 'BBVA Bancomer'){
                $credito->num_cuenta = '012970963023154101';
            }

            $lotes = Lote::select('id')->where('puente_id', '=', $request->id)->get();
            $lotesPuente = Lote_puente::select('id')->where('solicitud_id', '=', $request->id)->get();

            foreach ($lotes as $index => $lote) {
                $l = Lote::findOrFail($lote->id);
                $l->credito_puente = $credito->folio;
                $l->save();
            }

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

    public function numCuenta(Request $request)
    {
        $numCuenta = $request->numCuenta;
        $id = $request->id;

        $credito = Credito_puente::findOrFail($id);
        $credito->num_cuenta = $numCuenta;
        $credito->save();
    }

    public function getEdoCuenta(Request $request)
    {
        $prestado = 0;
        $pagos = Pago_puente::where('credito_puente_id', '=', $request->id)
            ->where('pendiente','=',0)
            ->orderBy('fecha', 'asc')
            ->get();

        $totalPrestado = Pago_puente::select(DB::raw("SUM(pagos_puentes.cargo) as total"))
            ->where('credito_puente_id', '=', $request->id)
            ->where('pendiente','=',0)
            ->orderBy('fecha', 'asc')
            ->first();

        if($totalPrestado->total != NULL)
            $prestado = $totalPrestado->total;

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

        foreach ($pagos as $index => $pago) {
            $pago->saldo = 0;
            if ($index == 0) {
                $saldo = $pago->saldo = $pago->cargo - $pago->abono;
            } else {
                $saldo = $pago->saldo = $pagos[$index - 1]->saldo + $pago->cargo - $pago->abono;
            }
        }

        return ['pagos' => $pagos, 'ultimoAbono' => $fecha, 
                'saldo' => $saldo, 'depCreditos' => $depCreditos,
                'prestado' => $prestado
            ];
    }

    public function tiie(Request $request)
    {
        return [$this->getTiie($request->fecha)];
    }

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
    /// Calcular intereses en crédito BANCREA ////////
    public function getInteresePeriodo(Request $request){
        $cargos = Pago_puente::select('id','concepto','fecha_interes','saldo','porc_interes as tasa')
                ->where('tipo', '=', 0)
                ->where('saldo','>',0)
                ->where('credito_puente_id', '=', $request->id)
                ->get();

        $fechaFin = Carbon::parse($request->fecha_sig_int);
        $intereses = 0;

        foreach ($cargos as $key => $cargo) {
            $fechaIni = Carbon::parse($cargo->fecha_interes);
            $cargo->interes = 0;
        
            $tiie = round(($cargo->tasa / 100), 6);
            $cargo->diasTransc = $fechaFin->diffInDays($fechaIni);
            $cargo->interes = ($cargo->saldo * $tiie) / 360;
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
                ->where('concepto', '!=', 'PREPUENTE')
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
                $fecha = $fecha->addMonths(1);

                $band = true;

                while ($band == true) {
                    if ($fecha->isWeekend())
                        $fecha = $fecha->addDays(1);
                    else
                        $band = false;
                }

                $credito->fecha_sig_int = $fecha->format('Y-m-j');
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

    public function selectCreditosPuente(Request $request){
        $creditos = Credito_puente::select('id','folio','fecha_integracion')
                    ->where('fecha_integracion','=',NULL);
                    if($request->fraccionamiento != '')
                        $creditos = $creditos->where('fraccionamiento','=',$request->fraccionamiento);
                    $creditos = $creditos->get();

        return $creditos;
    }

    ///////////////////// COMPROBANTES DE PAGOS
    public function formSubmit(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $pago = Pago_puente::findOrFail($request->id);
 
        //$fileName = time().$request->archivo->getClientOriginalName().'.'.$request->archivo->getClientOriginalExtension();
        $fileName = time().$request->archivo->getClientOriginalName();
         
        if($request->tipo == 1){
            $pathAnterior = public_path() . '/files/comprobantes/pagos/' . $pago->doc_pago;
            File::delete($pathAnterior);

            $moved =  $request->archivo->move(public_path('/files/comprobantes/pagos'), $fileName);
 
            if($moved){
               $pago->doc_pago = $fileName;
               $pago->save(); //Insert
           }
        }
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
 
     public function downloadFile($tipo,$fileName){
        if($tipo == 1)
            $pathtoFile = public_path().'/files/comprobantes/pagos/'.$fileName;
        else
            $pathtoFile = public_path().'/files/comprobantes/interes/'.$fileName;
        return response()->download($pathtoFile);
    }

    public function actualizarFolio(Request $request){
        $credito = Credito_puente::findOrFail($request->id);
        $lotes = Lote::select('id')->where('puente_id', '=', $request->id)->get();

        $credito->folio = $request->folio;
        $credito->save();

        foreach ($lotes as $index => $lote) {
            $l = Lote::findOrFail($lote->id);
            $l->credito_puente = $credito->folio;
            $l->save();
        }
    }

    public function insertFechaFirma(Request $request){
        if (!$request->ajax() || Auth::user()->rol_id == 11) return redirect('/');

        $fecha = $request->fecha;
        $id = $request->id;
        $tiie = $request->tiie;

        $credito = Credito_puente::findOrFail($id);
        $credito->fecha_firma = $fecha;
        $credito->tiie_firma = $tiie;
        $credito->save();
    }
}
