<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reubicacion;

use App\Deposito_conc;
use App\Deposito_gcc;
use App\Deposito;
use App\Dep_credito;
use App\Credito;
use Carbon\Carbon;
use App\Comentario_transferencia;
use Auth;
use DB;

class ReubicacionController extends Controller
{
    public function depositosPorReubicar(Request $request){
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                            ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            //->leftjoin('lotes as l','depositos.lote_id','=','l.id')
                            ->select('creditos.lote_id',
                                    'creditos.id as folio',
                                    'creditos.fraccionamiento',
                                    'creditos.etapa',
                                    'lotes.manzana',
                                    'lotes.num_lote',
                                    'depositos.lote_id as lote',
                                    'contratos.status',
                                    'lotes.emp_constructora',
                                    'lotes.emp_terreno',
                                    'depositos.cant_depo',
                                    'depositos.banco',
                                    'depositos.cuenta',
                                    'depositos.num_recibo',
                                    'depositos.monto_terreno as gcc',
                                    'creditos.valor_terreno',
                                    'creditos.saldo_terreno',
                                    'creditos.porcentaje_terreno',
                                    'depositos.id'
                                    )
                            ->whereRaw('creditos.lote_id != depositos.lote_id')
                            ->where('depositos.fecha_ingreso_concretania','!=',NULL)
                            ->where('contratos.status','!=',0)
                            ->where('depositos.reubicado','=',0);

                            if($request->buscar != '')                            
                                switch($request->criterio){
                                    case 'lotes.fraccionamiento_id':{
                                        $depositos = $depositos->where('lotes.fraccionamiento_id','=',$request->buscar);
                                        if($request->b_etapa != '')
                                            $depositos = $depositos->where('lotes.etapa_id','=',$request->b_etapa);
                                        if($request->b_manzana != '')
                                            $depositos = $depositos->where('lotes.manzana', 'like', '%'. $request->b_manzana . '%');
                                        if($request->b_lote != '')
                                            $depositos = $depositos->where('lotes.num_lote','=',$request->b_lote);
                                        break;
                                    }
                                    default:{
                                        $depositos = $depositos->where($request->criterio,'=',$request->buscar);
                                        break;
                                    }
                                }

                            $depositos =  $depositos->orderBy('depositos.fecha_pago','asc')
                            ->get();

            if(sizeof($depositos)){
                foreach ($depositos as $key => $deposito) {
                    if($deposito->emp_constructora == $deposito->emp_terreno)
                    $deposito->valor_terreno = 0;
                    $saldo = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                        ->select(
                            DB::raw('SUM(depositos.monto_terreno) as transferido'),
                            DB::raw('SUM(depositos.cant_depo) as monto_gcc')
                        )
                        ->where('pagos_contratos.contrato_id', '=', $deposito->folio)
                        ->where('depositos.fecha_ingreso_concretania','!=',NULL)
                        ->where('depositos.lote_id','=',$deposito->lote_id)
                        ->where('depositos.reubicado','=',0)
                        ->get();

                        $deposito->saldo_terreno_act = 0;
                        $deposito->cant_depo_act = 0;

                        $saldoAnt = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                        ->select(
                            DB::raw('SUM(depositos.monto_terreno) as transferido'),
                            DB::raw('SUM(depositos.cant_depo) as monto_gcc')
                        )
                        ->where('pagos_contratos.contrato_id', '=', $deposito->folio)
                        ->where('depositos.fecha_ingreso_concretania','!=',NULL)
                        ->where('depositos.lote_id','=',$deposito->lote)
                        ->where('depositos.reubicado','=',0)
                        ->get();

                    if($saldo[0]->transferido != NULL)
                        $deposito->saldo_terreno_act = $saldo[0]->transferido;
                    if($saldo[0]->monto_gcc != NULL)
                        $deposito->cant_depo_act = $saldo[0]->monto_gcc;

                    $reubicacion = Reubicacion::join('lotes','reubicaciones.lote_id','=','lotes.id')
                            ->join('etapas', 'lotes.etapa_id','=','etapas.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                            ->select('fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as etapa',
                                    'lotes.manzana', 'lotes.num_lote', 'lotes.emp_constructora','lotes.emp_terreno',
                                    'reubicaciones.valor_terreno',
                                    'reubicaciones.contrato_id',
                                    'reubicaciones.fecha_reubicacion'
                                )
                            ->where('reubicaciones.contrato_id','=',$deposito->folio)
                            ->where('reubicaciones.lote_id','=',$deposito->lote);

                            if($request->fecha1 != '' && $request->fecha2 != '')
                                $reubicacion =  $reubicacion->whereBetween('reubicaciones.fecha_reubicacion',[$request->fecha1, $request->fecha2]);

                            $reubicacion =  $reubicacion->orderBy('reubicaciones.fecha_reubicacion','desc')
                            ->first();

                    if($reubicacion){
                        $deposito->reubicacion = $reubicacion;

                        $reubicacion->cant_depo = $deposito->cant_depo;
                        $reubicacion->gcc = $deposito->gcc;

                        if($saldoAnt[0]->transferido != NULL)
                            $reubicacion->saldo_terreno_ant = $saldoAnt[0]->transferido;
                    }
                }
            }

        return $depositos;
    }

    public function getComentarios(Request $request){
        
        $comentarios = Comentario_transferencia::select('id','comentario','usuario','created_at');
                        if($request->id != '')
                            $comentarios = $comentarios->where('deposito_id','=',$request->id);
                        if($request->dep_conc != '')
                            $comentarios = $comentarios->where('dep_conc','=',$request->dep_conc);
                        if($request->dep_gcc != '')
                            $comentarios = $comentarios->where('dep_gcc','=',$request->dep_gcc);

                $comentarios = $comentarios->get();
        return $comentarios;
    }

    public function depositosPorReubicarGCC(Request $request){
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                            ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            //->leftjoin('lotes as l','depositos.lote_id','=','l.id')
                            ->select('creditos.lote_id',
                                    'creditos.id as folio',
                                    'creditos.fraccionamiento',
                                    'creditos.etapa',
                                    'lotes.manzana',
                                    'lotes.num_lote',
                                    'depositos.lote_id as lote',
                                    'contratos.status',
                                    'lotes.emp_constructora',
                                    'lotes.emp_terreno',
                                    'depositos.cant_depo',
                                    'depositos.banco',
                                    'depositos.cuenta',
                                    'depositos.num_recibo',
                                    'creditos.valor_terreno',
                                    'creditos.saldo_terreno',
                                    'creditos.porcentaje_terreno',
                                    'depositos.id'
                                    )
                            ->whereRaw('creditos.lote_id != depositos.lote_id')
                            ->where('lotes.emp_constructora','=','Grupo Constructor Cumbres')
                            ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
                            ->where('contratos.status','!=',0)
                            ->where('depositos.reubicado','=',0);

                            if($request->buscar != '')                            
                                switch($request->criterio){
                                    case 'lotes.fraccionamiento_id':{
                                        $depositos = $depositos->where('lotes.fraccionamiento_id','=',$request->buscar);
                                        if($request->b_etapa != '')
                                            $depositos = $depositos->where('lotes.etapa_id','=',$request->b_etapa);
                                        if($request->b_manzana != '')
                                            $depositos = $depositos->where('lotes.manzana', 'like', '%'. $request->b_manzana . '%');
                                        if($request->b_lote != '')
                                            $depositos = $depositos->where('lotes.num_lote','=',$request->b_lote);
                                        break;
                                    }
                                    default:{
                                        $depositos = $depositos->where($request->criterio,'=',$request->buscar);
                                        break;
                                    }
                                }

                            $depositos =  $depositos->orderBy('depositos.fecha_pago','asc')
                            ->get();

            if(sizeof($depositos)){
                foreach ($depositos as $key => $deposito) {
                    $saldo = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                        ->select(
                            DB::raw('SUM(depositos.cant_depo) as monto_gcc')
                        )
                        ->where('pagos_contratos.contrato_id', '=', $deposito->folio)
                        ->where('depositos.lote_id','=',$deposito->lote_id)
                        ->where('depositos.reubicado','=',0)
                        ->get();

                        $deposito->cant_depo_act = 0;

                        $saldoAnt = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                        ->select(
                            DB::raw('SUM(depositos.cant_depo) as monto_gcc')
                        )
                        ->where('pagos_contratos.contrato_id', '=', $deposito->folio)
                        ->where('depositos.lote_id','=',$deposito->lote)
                        ->where('depositos.reubicado','=',0)
                        ->get();

                    if($saldo[0]->monto_gcc != NULL)
                        $deposito->cant_depo_act = $saldo[0]->monto_gcc;

                    $reubicacion = Reubicacion::join('lotes','reubicaciones.lote_id','=','lotes.id')
                            ->join('etapas', 'lotes.etapa_id','=','etapas.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                            ->select('fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as etapa',
                                    'lotes.manzana', 'lotes.num_lote', 'lotes.emp_constructora','lotes.emp_terreno',
                                    'reubicaciones.valor_terreno',
                                    'reubicaciones.contrato_id',
                                    'reubicaciones.fecha_reubicacion'
                                )
                            ->where('reubicaciones.contrato_id','=',$deposito->folio)
                            ->where('lotes.emp_constructora','=','Grupo Constructor Cumbres')
                            ->where('lotes.emp_terreno','=','Grupo Constructor Cumbres')
                            ->where('reubicaciones.lote_id','=',$deposito->lote);

                            if($request->fecha1 != '' && $request->fecha2 != '')
                                $reubicacion =  $reubicacion->whereBetween('reubicaciones.fecha_reubicacion',[$request->fecha1, $request->fecha2]);

                            $reubicacion =  $reubicacion->orderBy('reubicaciones.fecha_reubicacion','desc')
                            ->first();

                    if($reubicacion){
                        $deposito->reubicacion = $reubicacion;

                        $reubicacion->cant_depo = $deposito->cant_depo;
                        $reubicacion->gcc = $deposito->gcc;

                        if($saldoAnt[0]->monto_gcc != NULL)
                            $reubicacion->monto_gcc = $saldoAnt[0]->monto_gcc;
                    }
                }
            }

        return $depositos;
    }

    public function indexGCC(Request $request){
        $gcc = Deposito_gcc::join('lotes','depositos_gcc.lote_id','=','lotes.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->select('depositos_gcc.*','lotes.num_lote',
                            'lotes.manzana','lotes.emp_constructora','lotes.emp_terreno',
                            'fraccionamientos.nombre as proyecto',
                            'etapas.num_etapa as etapa'
                    );

                if($request->buscar != '')
                    $gcc = $gcc->where($request->criterio,'=',$request->buscar);
                if($request->etapa != '')
                    $gcc = $gcc->where('lotes.etapa_id','=',$request->etapa);
                if($request->manzan != '')
                    $gcc = $gcc->where('lotes.manzana','like','%'.$request->manzana.'%');
                if($request->lote != '')
                    $gcc = $gcc->where('lotes.num_lote','=',$request->lote);

            $gcc = $gcc->paginate(10);

        return [
            'pagination' => [
                'total'         => $gcc->total(),
                'current_page'  => $gcc->currentPage(),
                'per_page'      => $gcc->perPage(),
                'last_page'     => $gcc->lastPage(),
                'from'          => $gcc->firstItem(),
                'to'            => $gcc->lastItem(),
            ],'gcc' => $gcc];

    }

    public function indexConc(Request $request){
        $conc = Deposito_conc::join('lotes','depositos_conc.lote_id','=','lotes.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->select('depositos_conc.*','lotes.num_lote',
                            'lotes.manzana','lotes.emp_constructora','lotes.emp_terreno',
                            'fraccionamientos.nombre as proyecto',
                            'etapas.num_etapa as etapa'
                    );

                if($request->buscar != '')
                    $conc = $conc->where($request->criterio,'=',$request->buscar);
                if($request->etapa != '')
                    $conc = $conc->where('lotes.etapa_id','=',$request->etapa);
                if($request->manzan != '')
                    $conc = $conc->where('lotes.manzana','like','%'.$request->manzana.'%');
                if($request->lote != '')
                    $conc = $conc->where('lotes.num_lote','=',$request->lote);

            $conc = $conc->paginate(10);

        return [
            'pagination' => [
                'total'         => $conc->total(),
                'current_page'  => $conc->currentPage(),
                'per_page'      => $conc->perPage(),
                'last_page'     => $conc->lastPage(),
                'from'          => $conc->firstItem(),
                'to'            => $conc->lastItem(),
            ],'conc' => $conc];

    }

    public function storeDepositoReubicacion(Request $request){
        try {
            DB::beginTransaction();

            $deposito = Deposito::findOrFail($request->id);
            $deposito->reubicado = 1;
            $deposito->save();

            if($request->monto_gcc != 0){
                $this->storeGCC($request);
            }

            if($request->monto_conc != 0){
                $this->storeConc($request);
            }

            
            DB::commit();
            } catch (Exception $e) { 
                DB::rollBack();
            }
    }

    public function storeGCC(Request $request){
        $gcc = new Deposito_gcc();
        $gcc->contrato_id = $request->contrato_id;
        $gcc->cuenta = $request->cuenta_gcc;
        $gcc->fecha = $request->fecha;
        $gcc->lote_id = $request->lote_id;
        $gcc->cheque = $request->cheque_gcc;
        $gcc->monto = $request->monto_gcc;
        $gcc->save();

        $comentarios = Comentario_transferencia::select('id')->where('deposito_id','=',$request->id)->get();

        if(sizeof($comentarios))
            foreach ($comentarios as $key => $comentario) {
                $com = Comentario_transferencia::findOrFail($comentario->id);
                $com->dep_gcc = $gcc->id;
                $com->save();
            }

        $this->calculateSaldoTerreno($request->contrato_id);
    }

    public function agregarComentario(Request $request){
        $comentario = new Comentario_transferencia();
        $comentario->deposito_id = $request->deposito_id;
        $comentario->dep_conc = $request->dep_conc;
        $comentario->dep_gcc = $request->dep_gcc;
        $comentario->comentario = $request->comentario;
        $comentario->usuario = Auth::user()->usuario;
        $comentario->save();
    }

    public function storeConc(Request $request){
        $conc = new Deposito_conc();
        $conc->contrato_id = $request->contrato_id;
        $conc->cuenta = $request->cuenta_conc;
        $conc->fecha = $request->fecha;
        $conc->lote_id = $request->lote_id;
        $conc->cheque = $request->cheque_conc;
        $conc->monto = $request->monto_conc;
        $conc->devolucion = $request->devolucion;
        $conc->save();

        $comentarios = Comentario_transferencia::select('id')->where('deposito_id','=',$request->id)->get();

        if(sizeof($comentarios))
            foreach ($comentarios as $key => $comentario) {
                $com = Comentario_transferencia::findOrFail($comentario->id);
                $com->dep_conc = $conc->id;
                $com->save();
            }

        $this->calculateSaldoTerreno($request->contrato_id);
    }

    private function calculateSaldoTerreno($id){

        $credito = Credito::findOrFail($id);

            $sumaDepositoCreditTerreno = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->select(DB::raw("SUM(dep_creditos.monto_terreno) as suma"))->where('contratos.id','=',$id)
                ->where('inst_seleccionadas.elegido','=',1)
                ->get();
                if($sumaDepositoCreditTerreno[0]->suma == NULL){
                    $sumaDepositoCreditTerreno[0]->suma = 0;
                }

            $sumaDepositoTerreno = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                ->select(DB::raw("SUM(depositos.monto_terreno) as suma"))->where('contratos.id','=',$id)
                ->where('depositos.fecha_ingreso_concretania','!=',NULL)
                ->where('depositos.lote_id','=',$credito->lote_id)
                ->get();
                if($sumaDepositoTerreno[0]->suma == NULL){
                    $sumaDepositoTerreno[0]->suma = 0;
                }

            $depositoGCC = Deposito_gcc::select(DB::raw("SUM(depositos_gcc.monto) as suma"))
                ->where('depositos_gcc.contrato_id','=',$id)
                ->where('depositos_gcc.lote_id','=',$credito->lote_id)
                ->get();
                if($depositoGCC[0]->suma == NULL){
                    $depositoGCC[0]->suma = 0;
                }

            $depositoConc = Deposito_conc::select(DB::raw("SUM(depositos_conc.monto) as suma"))
                ->where('depositos_conc.contrato_id','=',$id)
                ->where('depositos_conc.lote_id','=',$credito->lote_id)
                ->where('depositos_conc.devolucion','=',1)
                ->get();
                if($depositoConc[0]->suma == NULL){
                    $depositoConc[0]->suma = 0;
                }
        
        $credito->saldo_terreno = $sumaDepositoCreditTerreno[0]->suma + $sumaDepositoTerreno[0]->suma + $depositoGCC[0]->suma -  $depositoConc[0]->suma;
        $credito->save();

    }

    public function createReubicacion($contrato_id, $lote_id, $cliente_id,
            $asesor_id, $promocion, $tipo_credito, $institucion,
            $valor_terreno, $observacion, $fecha_reubicacion
        )
    {
        $reubicacion = new Reubicacion();
        $reubicacion->contrato_id = $contrato_id;
        $reubicacion->lote_id = $lote_id;
        $reubicacion->cliente_id = $cliente_id;
        $reubicacion->asesor_id = $asesor_id;
        $reubicacion->promocion = $promocion;
        $reubicacion->tipo_credito = $tipo_credito;
        $reubicacion->institucion = $institucion;
        $reubicacion->valor_terreno = $valor_terreno;
        $reubicacion->observacion = $observacion;
        if($fecha_reubicacion == '')
            $fecha_reubicacion = new Carbon();
        $reubicacion->fecha_reubicacion = $fecha_reubicacion;
        $reubicacion->save();
    }

    public function delete(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $reubicacion = Reubicacion::findOrFail($request->id);
        $reubicacion->delete();
    }

    public function getReubicaciones(Request $request){
        $reubicaciones = Reubicacion::join('lotes','reubicaciones.lote_id','=','lotes.id')
                                    ->join('etapas', 'lotes.etapa_id','=','etapas.id')
                                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                    ->join('personal as cliente','reubicaciones.cliente_id','=','cliente.id')
                                    ->join('personal as asesor','reubicaciones.asesor_id','=','asesor.id')
                                    ->select('reubicaciones.*','cliente.nombre as c_nombre','cliente.apellidos as c_apellidos',
                                            'asesor.nombre as a_nombre','asesor.apellidos as a_apellidos',
                                            'fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as etapa',
                                            'lotes.manzana', 'lotes.num_lote', 'lotes.emp_constructora','lotes.emp_terreno'
                                        )
                                    ->where('reubicaciones.contrato_id','=',$request->id)
                                    ->orderBy('reubicaciones.fecha_reubicacion','desc')
                                    ->get();

        return $reubicaciones;
    }

    public function store(Request $request){
        $this->createReubicacion(
            $request->contrato_id, 
            $request->lote_id, 
            $request->cliente_id,
            $request->asesor_id, 
            $request->promocion, 
            $request->tipo_credito, 
            $request->institucion,
            $request->valor_terreno, 
            $request->observacion, 
            $request->fecha_reubicacion
        );
    }
}
