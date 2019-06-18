<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gasto_admin;
use App\Avaluo;

class GastosAdministrativosController extends Controller
{
    public function index(Request $request){
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
    }

    public function getDatosAvaluo(Request $request){
        $gasto = Gasto_admin::select('id','fecha')
                ->where('contrato_id','=',$request->folio)
                ->where('concepto','=','Avaluo')
                ->where('costo','=',$request->costo)
                ->get();
            
        return ['gasto' => $gasto];
    }

    public function updateAvaluo(Request $request){
        $gasto = Gasto_admin::findOrFail($request->gasto_id);
        $gasto->costo = $request->costo;
        $gasto->fecha = $request->fecha;
        $gasto->save();

        $avaluo = Avaluo::findOrFail($request->avaluoId);
        $avaluo->costo = $request->costo;
        $avaluo->save();
    }
}
