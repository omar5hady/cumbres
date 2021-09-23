<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reubicacion;
use App\Credito;
use Carbon\Carbon;
use Auth;

use App\Deposito;

class ReubicacionController extends Controller
{
    public function pruebaReubicaciones(Request $request){
        $reubicaciones = Reubicacion::orderBy('fecha_reubicacion','desc')->get();

        if(sizeof($reubicaciones))
            foreach ($reubicaciones as $key => $reubicacion) {
                $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                                    ->select('depositos.lote_id','depositos.fecha_pago','depositos.id')
                                    ->where('depositos.fecha_pago','<',$reubicacion->fecha_reubicacion)
                                    ->where('pagos_contratos.contrato_id','=',$reubicacion->contrato_id)
                                    ->get();
                // if(sizeof($depositos))
                //     foreach($depositos as $key => $dep){
                //         $deposito = Deposito::findOrFail($dep->id);
                //         $deposito->lote_id = $reubicacion->lote_id;
                //         $deposito->save();
                //     }

                $reubicacion->depositos = $depositos;
            }

        return $reubicaciones;
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
