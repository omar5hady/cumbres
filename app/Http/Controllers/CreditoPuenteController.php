<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;
use App\Modelo;
use App\Credito_puente;
use App\Lote_puente;
use App\Obs_puente;
use App\Doc_puente;
use App\Precio_puente;
use App\Puente_checklist;
use App\Cat_documento;
use DB;
use Auth;

class CreditoPuenteController extends Controller
{
    public function indexSinCredito( Request $request){
        if(!$request->ajax())return redirect('/');

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

    private function getSinCredito($request){
        $lotes = Lote::join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as e','lotes.etapa_id','=','e.id')
                ->join('modelos as m','lotes.modelo_id','=','m.id')
                ->select('lotes.id', 'lotes.manzana', 'lotes.num_lote', 'lotes.sublote',
                    'lotes.habilitado','lotes.credito_puente', 'lotes.terreno',
                    'lotes.emp_terreno', 'lotes.emp_constructora','lotes.sublote','lotes.calle',
                    'lotes.numero', 'lotes.interior',
                    'm.nombre as modelo', 'e.num_etapa','f.nombre as proyecto'
                )
                ->where('lotes.contrato','=',0);
                //->where('lotes.habilitado','=',1);

                if($request->puente == ''){
                    $lotes = $lotes->where('lotes.credito_puente','=',NULL);
                }
                else{
                    $lotes = $lotes->where('lotes.credito_puente','=',$request->puente);
                }

                if($request->proyecto != '')
                    $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);

                if($request->etapa != '')
                    $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);

                if($request->modelo != '')
                    $lotes = $lotes->where('lotes.modelo_id','=',$request->modelo);

                if($request->manzana != '')
                    $lotes = $lotes->where('lotes.manzana','like','%'.$request->manzana.'%');

                if($request->lote != '')
                    $lotes = $lotes->where('lotes.num_lote','=',$request->lote);

                if($request->emp_terreno != '')
                    $lotes = $lotes->where('lotes.emp_terreno','=',$request->emp_terreno);

                if($request->emp_constructora != '')
                    $lotes = $lotes->where('lotes.emp_constructora','=',$request->emp_constructora);
        
        $lotes = $lotes->paginate(30);

        return $lotes;
    }

    public function getLotes(Request $request){
        if(!$request->ajax())return redirect('/');
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->select('lotes.id','lotes.num_lote','lotes.fraccionamiento_id','lotes.etapa_id',
                'lotes.manzana', 'modelos.nombre as modelo','lotes.modelo_id',
                'etapas.num_etapa','fraccionamientos.nombre as proyecto')
            ->whereIn('lotes.id',$request->id)
            ->orderBy('lotes.num_lote')
            ->get();

        return ['lotes' => $lotes];
    }

    public function getModelosPuente(Request $request){
        if(!$request->ajax())return redirect('/');
        $modelos = Modelo::select('id','nombre')->where('fraccionamiento_id','=',$request->id)
                ->where('nombre','!=','Por Asignar')->orderBy('nombre','asc')->get();

                return ['modelos'=>$modelos];
    }

    public function storeSolicitud(Request $request){
        
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

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
            $puente->folio = $request->banco.'-'.$request->cantidad;
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

            foreach($docs as $index => $d){
                $doc = new Puente_checklist();
                $doc->solicitud_id = $id;
                $doc->documento_id =$d['id'];
                $doc->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function indexCreditos(Request $request){
        $creditos = Credito_puente::join('fraccionamientos','creditos_puente.fraccionamiento','=','fraccionamientos.id')
                    ->join('instituciones_financiamiento', 'creditos_puente.banco','=','instituciones_financiamiento.nombre')
                    ->select('creditos_puente.id','creditos_puente.banco','creditos_puente.folio','creditos_puente.interes',
                                'creditos_puente.status','creditos_puente.total','creditos_puente.apertura',
                                'instituciones_financiamiento.lic',
                                'fraccionamientos.nombre as proyecto','creditos_puente.fraccionamiento'    
                    );

        if($request->fraccionamiento != '')
            $creditos = $creditos->where('creditos_puente.fraccionamiento','=',$request->fraccionamiento);
        
        if($request->folio != '')
            $creditos = $creditos->where('creditos_puente.folio','=',$request->folio);

        $creditos = $creditos->orderBy('creditos_puente.id','desc')->paginate(10);

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

    public function selectLotes(Request $request){
        $lotes = $this->getSinCredito($request);
        return ['lotes' => $lotes];
    }

    public function getPreciosModelo(Request $request){
        $modelos = Precio_puente::join('creditos_puente','precios_puente.solicitud_id','=','creditos_puente.id')
                    ->join('modelos','precios_puente.modelo_id','=','modelos.id')
                    ->select('precios_puente.id','precios_puente.precio','precios_puente.modelo_id','modelos.nombre as modelo')
                    ->where('creditos_puente.id','=',$request->id)
                    ->orderBy('precios_puente.precio','desc')
                    ->orderBy('modelos.nombre','asc')
                    ->get();

        return['modelos'=>$modelos];
    }

    public function getLotesPuente(Request $request){
        $lotes = Lote_puente::join('lotes','lotes_puente.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->join('modelos','lotes_puente.modelo_id','=','modelos.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->select('lotes_puente.id','lotes_puente.modelo_id','lotes_puente.precio_p','lotes_puente.modeloAnt1',
                            'lotes_puente.modeloAnt2','lotes_puente.precio1','lotes_puente.precio2', 'lotes.num_lote',
                            'lotes.manzana','lotes_puente.lote_id','lotes.emp_constructora',
                            'modelos.nombre as modelo','fraccionamientos.nombre as proyecto','etapas.num_etapa',
                            'etapas.factibilidad','licencias.foto_predial','licencias.foto_lic','licencias.num_licencia'
                    )
                    ->where('lotes_puente.solicitud_id','=',$request->id)
                    ->orderBy('lotes.etapa_id','asc')
                    ->orderBy('lotes.manzana','asc')
                    ->orderBy('lotes.num_lote','asc')
                    ->get();

        return['lotes'=>$lotes];
    }

    public function getChecklist(Request $request){

        $checklist = Puente_checklist::join('cat_documentos','puente_checklist.documento_id','=','cat_documentos.id')
                                ->select('cat_documentos.documento','cat_documentos.categoria','puente_checklist.solicitud_id',
                                    'puente_checklist.id','puente_checklist.documento_id', 'puente_checklist.id','puente_checklist.listo'
                                )
                                ->where('puente_checklist.solicitud_id','=',$request->id)
                                ->orderBy('cat_documentos.categoria','asc')
                                ->orderBy('puente_checklist.id','asc')
                                ->get();
        
        $listos = Puente_checklist::where('puente_checklist.solicitud_id','=',$request->id)
                                  ->where('puente_checklist.listo','=',1)->count();

        return ['checklist' => $checklist, 
            'listos' => $listos,
            'total' => $checklist->count()
        ];
    }

    public function cambiarChk(Request $request){
        $chk = Puente_checklist::findOrFail($request->id);
        $chk->listo = $request->valor;
        $chk->save();
    }

    public function deleteChk(Request $request){
        $chk = Puente_checklist::findOrFail($request->id);
        $chk->delete();
    }

    public function agregarLote(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $l = $request->lote;
        $id = $request->solicitud_id;

        try {
            DB::beginTransaction();
            $datosLote = Lote::findOrFail($l);
            $datosLote->puente_id = $id;
            $datosLote->credito_puente = 'EN PROCESO';
            

            $precio = Precio_puente::select('precio')
                        ->where('modelo_id','=',$datosLote->modelo_id)
                        ->where('solicitud_id','=',$id)->first();

            if($precio == null)
                $precio = 0;

            $lote_puente = new Lote_puente();
            $lote_puente->solicitud_id = $id;
            $lote_puente->lote_id = $l;
            $lote_puente->modelo_id = $datosLote->modelo_id;
            $lote_puente->precio_p = $precio->precio;
            $lote_puente->save();

            $totalPuente = Lote_puente::select('id')->where('solicitud_id','=',$id)->count();

            $credito = Credito_puente::findOrFail($id);
            $credito->total+=$precio->precio;
            $credito->folio = $credito->banco.'-'.$totalPuente;
            $credito->save();
            $datosLote->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function eliminarLote(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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
            $totalPuente = Lote_puente::select('id')->where('solicitud_id','=',$id)->count();
            $totalPuente = (int)$totalPuente-1;

            $credito = Credito_puente::findOrFail($id);
            $credito->total-=$lote_puente->precio_p;
            $credito->folio = $credito->banco.'-'.$totalPuente;
            $credito->save();

            $lote_puente->delete();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function actualizarPrecio(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            DB::beginTransaction();
            
            $precio = Precio_puente::findOrFail($request->id);
            $precio->precio = $request->precio;
            $precio->save();

            $lotes = Lote_puente::select('id')
                        ->where('modelo_id','=',$precio->modelo_id)
                        ->where('solicitud_id','=',$precio->solicitud_id)
                        ->get();

            if(sizeof($lotes))
                foreach ($lotes as $index => $l) {
                    $lote = Lote_puente::findOrFail($l->id);
                    $lote->precio_p = $precio->precio;
                    $lote->save();
                }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    public function actualizarSolicitud(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $totalPuente = Lote_puente::select('id')->where('solicitud_id','=',$request->id)->count();

        $datos = $request->cabecera;
        $credito = Credito_puente::findOrFail($request->id);
        $credito->banco = $datos['banco'];
        $credito->interes = $datos['interes'];
        $credito->total = $request->total;
        $credito->apertura = $datos['apertura'];
        $credito->folio = $datos['banco'].'-'.$totalPuente.'-'.$request->id;
        $credito->save();
        //$credito->save();
    }

    public function getObs(Request $request){
        return ['obs' => Obs_puente::where('puente_id','=',$request->id)->orderBy('id','desc')->get()];
        //return ['obs'=>$obs];
    }

    public function storeObs(Request $request){
        $obs = new Obs_puente();
        $obs->puente_id = $request->id;
        $obs->observacion = $request->observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    public function getPlanos(Request $request){
        $edificacion = Doc_puente::select('id','descripcion','clasificacion','archivo','created_at','fecha_entrega','notas','user_alta','user_confirm','fecha_confirm')
                    ->where('puente_id','=',$request->id)
                    ->where('clasificacion','=',2)->get();

        $urbanizacion = Doc_puente::select('id','descripcion','clasificacion','archivo','created_at','fecha_entrega','notas','user_alta','user_confirm','fecha_confirm')
                    ->where('puente_id','=',$request->id)
                    ->where('clasificacion','=',1)->get();
        
        return[
            'urbanizacion' => $urbanizacion, 
            'edificacion' => $edificacion
        ];
    }

    public function getChkSinSolic(Request $request){
        $chkList = Puente_checklist::select('documento_id')->where('solicitud_id','=',$request->id)->get();
        $listado = [];

        foreach($chkList as $index => $c){
            array_push($listado,$c->documento_id);
        }

        $chk = Cat_documento::select('id','documento','categoria')
                ->whereNotIn('id',$listado)
                ->get();
        return ['chk' => $chk];
    }

    public function addDocChk(Request $request){
        $chkList = new Puente_checklist();
        $chkList->solicitud_id = $request->id;
        $chkList->documento_id = $request->documento;
        $chkList->save();
    }

    public function getModelosBase(Request $request){

        $t_adquisicion_terreno =$t_estudios_lic =$t_proyectos_disen = $t_edificacion = $t_urbanizacion_infra = 
        $t_promocion_publi = $t_gastos_venta = $t_gastos_admin = $t_gastos_notariales = 
        $t_gastos_fin_com = $t_int_cpuente_com = $t_cont = $t_venta = 0;

        $modelos = Modelo::join('lotes_puente as lp','modelos.id','=','lp.modelo_id')
                    ->join('bases_presupuestales as bp','modelos.id','=','bp.modelo_id')
                    ->select('modelos.nombre','bp.*','modelos.id')
                    ->where('solicitud_id','=',$request->id)
                    ->distinct()->get();
                    
        if(sizeof($modelos))
        foreach ($modelos as $index => $m) {
            $lotes = Lote_puente::select('id','precio_p')->where('modelo_id','=',$m->id)
                        ->where('solicitud_id','=',$request->id);
            
            $lotes2 = $lotes;

            $lotes2 = $lotes2->get();
            $m->precio_puente = $lotes2[0]->precio_p;
            $t_cont += $m->cont = $lotes->count();

            $m->inc = round($m->precio_puente/$m->valor_venta,2);
            $t_venta += ($m->precio_puente * $m->cont);

            $t_adquisicion_terreno += $m->adquisicion_terreno = round($m->cont * ($m->int_pago_terreno + $m->valor_terreno + $m->escritura_gcc + $m->adicional_terreno) * $m->inc,2);
            $t_estudios_lic += $m->estudios_lic = round($m->cont * ($m->permisos * $m->inc) * 0.7,2);
            $t_proyectos_disen += $m->proyectos_disen = round($m->cont * ($m->permisos * $m->inc) * 0.3,2);
            $t_edificacion += $m->edificacion = round($m->cont * ($m->presupuesto_edif + $m->laboratorio + $m->partida_inflacionaria) * $m->inc,2);
            $t_urbanizacion_infra += $m->urbanizacion_infra = round($m->cont * ($m->presupuesto_urb + $m->equipamiento + $m->fianzas) * $m->inc,2);
            $t_promocion_publi += $m->promocion_publi = round($m->cont * ($m->gastos_comerc * $m->inc),2);
            $t_gastos_venta += $m->gastos_venta = round($m->cont * ($m->comicion_venta * $m->inc),2);
            $t_gastos_admin += $m->gastos_admin = round($m->cont * ($m->gastos_ind_op * $m->inc),2);
            $t_gastos_notariales += $m->gastos_notariales = round($m->cont * ($m->gastos_esc * $m->inc),2);
            $t_gastos_fin_com += $m->gastos_fin_com = round($m->cont * ($m->comision_int * $m->inc),2);
            $t_int_cpuente_com += $m->int_cpuente_com = round($m->cont * ($m->insc_conjunto + $m->int_nafin + $m->int_cpuente) * $m->inc,2);
        }
        
        return ['modelos'=>$modelos,
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

    public function saveDoc(Request $request){
        $puente = new Doc_puente();
        $puente->puente_id = $request->id;
        $puente->descripcion = $request->descripcion;
        $puente->clasificacion = $request->clasificacion;
        $puente->notas = $request->notas;
        $puente->fecha_entrega = $request->fecha_entrega;
        $puente->user_alta = Auth::user()->usuario;
        $puente->save();
    }

}
