<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gasto_admin;
use App\Avaluo;
use App\Contrato;
use DB;

class GastosAdministrativosController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;

        if($buscar==''){
            $gastos = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
                            'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha')
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
        }
        else{
            switch($criterio){
                case 'gastos_admin.fecha':{
                    $gastos = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                                'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
                                'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha')
                        ->orderBy('contratos.id','asc')
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->paginate(8);
                    break;
                }
                case 'personal.nombre':{
                    $gastos = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                                'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
                                'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha')
                        ->orderBy('contratos.id','asc')
                        ->where($criterio, 'like', '%'.$buscar . '%')
                        ->orWhere('personal.apellidos','like', '%'.$buscar .'%')
                        ->paginate(8);
                    break;
                }
                case 'creditos.fraccionamiento':{
                    if($buscar2 == '' && $buscar3 == ''){
                        $gastos = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
                                    'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha')
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->paginate(8);
                        break;
                    }
                    elseif($buscar2 != '' && $buscar3 == ''){
                        $gastos = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
                                    'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha')
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->where('creditos.etapa', '=', $buscar2)
                            ->paginate(8);
                        break;
                    }
                    elseif($buscar2 == '' && $buscar3 != ''){
                        $gastos = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
                                    'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha')
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->where('creditos.manzana', '=', $buscar3)
                            ->paginate(8);
                        break;
                    }
                    else{
                        $gastos = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
                                    'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha')
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->where('creditos.etapa', '=', $buscar2)
                            ->where('creditos.manzana', '=', $buscar3)
                            ->paginate(8);
                        break;
                    }
                }
                default:{
                    $gastos = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                                'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
                                'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha')
                        ->orderBy('contratos.id','asc')
                        ->where($criterio, '=', $buscar)
                        ->paginate(8);
                    break;
                }
            }
        }
        
        
        return [
                'pagination' => [
                    'total'         => $gastos->total(),
                    'current_page'  => $gastos->currentPage(),
                    'per_page'      => $gastos->perPage(),
                    'last_page'     => $gastos->lastPage(),
                    'from'          => $gastos->firstItem(),
                    'to'            => $gastos->lastItem(),
                ],
                'gastos' => $gastos];
    }

    public function storeAvaluo(Request $request){
        if(!$request->ajax())return redirect('/');
        try{
            DB::beginTransaction();
            $gasto = new Gasto_admin();
            $gasto->contrato_id = $request->id;
            $gasto->concepto = 'Avaluo';
            $gasto->costo = $request->costo;
            $gasto->fecha = $request->fecha;
            $gasto->observacion = '';
            $gasto->save();

            $avaluo = Avaluo::findOrFail($request->avaluoId);
            $avaluo->costo = $request->costo;
            $avaluo->save();

            $contrato = Contrato::findOrFail($request->id);
            $contrato->saldo = $contrato->saldo + $request->costo;
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function getDatosAvaluo(Request $request){
        if(!$request->ajax())return redirect('/');
        $gasto = Gasto_admin::select('id','fecha')
                ->where('contrato_id','=',$request->folio)
                ->where('concepto','=','Avaluo')
                ->where('costo','=',$request->costo)
                ->get();
            
        return ['gasto' => $gasto];
    }

    public function updateAvaluo(Request $request){
        if(!$request->ajax())return redirect('/');
        try{
            DB::beginTransaction();
            $gasto = Gasto_admin::findOrFail($request->gasto_id);
            $costo_ant = $gasto->costo;
            $contrato_id = $gasto->contrato_id;
            $gasto->costo = $request->costo;
            $gasto->fecha = $request->fecha;
            $gasto->save();

            $avaluo = Avaluo::findOrFail($request->avaluoId);
            $avaluo->costo = $request->costo;
            $avaluo->save();

            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = $contrato->saldo - $costo_ant + $request->costo;
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function indexContratos (Request $request){
        if(!$request->ajax())return redirect('/');
        $b = $request->b;
        $b2 = $request->b2;
        $b3 = $request->b3;
        $criterio2 = $request->criterio2;

        if($b == ''){
            $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
                       'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote')
           ->where('contratos.status','!=',0)
           ->orderBy('contratos.id','asc')
           ->paginate(8);
        }else{
            switch($criterio2){
                case 'contratos.id': {
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
                                'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote')
                    ->where('contratos.status','!=',0)
                    ->where('contratos.id','=',$b)
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
                    break;
                }
                case 'personal.nombre': {
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
                                'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote')
                    ->where('contratos.status','!=',0)
                    ->where('personal.nombre','LIKE','%'.$b.'%')
                    ->orwhere('contratos.status','!=',0)
                    ->where('personal.apellidos','LIKE','%'.$b.'%')
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
                    break;
                }

                case 'creditos.fraccionamiento': {
                    if($b2 == '' && $b3 == ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
                                'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote')
                    ->where('contratos.status','!=',0)
                    ->where($criterio2,'=',$b)
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
                    break;
                    }elseif($b2 != '' && $b3 == ''){
                        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
                                    'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote')
                        ->where('contratos.status','!=',0)
                        ->where($criterio2,'=',$b)
                        ->where('creditos.etapa','=',$b2)
                        ->orderBy('contratos.id','asc')
                        ->paginate(8);
                        break;
                    }elseif ($b2 == '' && $b3 != '') {
                        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
                                    'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote')
                        ->where('contratos.status','!=',0)
                        ->where($criterio2,'=',$b)
                        ->where('creditos.manzana','=',$b3)
                        ->orderBy('contratos.id','asc')
                        ->paginate(8);
                        break;
                    }else {
                        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
                                    'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote')
                        ->where('contratos.status','!=',0)
                        ->where($criterio2,'=',$b)
                        ->where('creditos.etapa','=',$b2)
                        ->where('creditos.manzana','=',$b3)
                        ->orderBy('contratos.id','asc')
                        ->paginate(8);
                        break;
                    }
                }
                default: {
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
                                'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote')
                    ->where('contratos.status','!=',0)
                    ->where($criterio2,'=',$b)
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
                    break;
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
            ],
            'contratos' => $contratos];
        

    }

    public function store(Request $request){
        if(!$request->ajax())return redirect('/');
        try{
            DB::beginTransaction();
            $gastos = new Gasto_admin();
            $gastos->contrato_id = $request->contrato_id;
            $gastos->concepto = $request->concepto;
            $gastos->costo = $request->costo;
            $gastos->observacion = $request->observacion;
            $gastos->fecha = $request->fecha;
            $gastos->save();

            $contrato = Contrato::findOrFail($request->contrato_id);
            $contrato->saldo = $contrato->saldo + $request->costo;
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function update(Request $request){
        if(!$request->ajax())return redirect('/');
        try{
            DB::beginTransaction();
            $gastos = Gasto_admin::findOrFail($request->id);
            $costo_ant = $gastos->costo;
            $contrato_id = $gastos->contrato_id;
            $gastos->concepto = $request->concepto;
            $gastos->observacion = $request->observacion;
            $gastos->fecha = $request->fecha;
            $gastos->costo = $request->costo;
            $gastos->save();

            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = $contrato->saldo - $costo_ant + $request->costo;
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function delete(Request $request){
        if(!$request->ajax())return redirect('/');
        try{
            DB::beginTransaction();
            $gastos = Gasto_admin::findOrFail($request->id);
            $costo_ant = $gastos->costo;
            $contrato_id = $gastos->contrato_id;
            $gastos->delete();

            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = $contrato->saldo - $costo_ant;
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function getGastos(Request $request){
        if(!$request->ajax())return redirect('/');
        $gastos=Gasto_admin::select('concepto','costo','id')
                    ->where('contrato_id','=',$request->folio)
                    ->get();

        $totalGastos = Gasto_admin::select(DB::raw("SUM(costo) as sumGasto"))
                    ->groupBy('contrato_id')
                    ->where('contrato_id','=',$request->folio)
                    ->get();

        return ['gastos' => $gastos,
                'totalGastos' => $totalGastos ];
    }
}
