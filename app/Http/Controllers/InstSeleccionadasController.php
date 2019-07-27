<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\inst_seleccionada;
use DB;
use Auth;
use Carbon\Carbon;
use App\Dep_credito;

class InstSeleccionadasController extends Controller
{
    public function indexCreditoSel(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $buscar4 = $request->buscar4;
        $b_cobrados = $request->b_cobrados;
        $criterio = $request->criterio;

        if($b_cobrados == 0){
            if($buscar == '' && $criterio != 'personal.nombre'){
                $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('contratos','contratos.id','=','creditos.id')
                    ->join('lotes','lotes.id','=','creditos.lote_id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('contratos.id as folio', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                    ->where('inst_seleccionadas.cobrado','=','0')
                    ->where('inst_seleccionadas.elegido', '=', 1)
                    ->where('contratos.status', '=', 3)
                    ->orWhere('inst_seleccionadas.tipo', '=', 1)
                    ->where('inst_seleccionadas.cobrado','=','0')
                    ->where('contratos.status', '=', 3)
                    ->orderBy('inst_seleccionadas.cobrado','asc')
                    
                    ->paginate(10);

                    $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('contratos','contratos.id','=','creditos.id')
                        ->join('lotes','lotes.id','=','creditos.lote_id')
                        ->join('personal','personal.id','=','creditos.prospecto_id')
                        ->select('contratos.id as folio')
                        ->where('inst_seleccionadas.cobrado','=','0')
                        ->where('inst_seleccionadas.elegido', '=', 1)
                        ->where('contratos.status', '=', 3)
                        ->orWhere('inst_seleccionadas.tipo', '=', 1)
                        ->where('inst_seleccionadas.cobrado','=','0')
                        ->where('contratos.status', '=', 3)
                        ->orderBy('inst_seleccionadas.cobrado','asc')
                        
                        ->count();
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        if($buscar2 == ''){
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                    ->join('contratos','contratos.id','=','creditos.id')
                                    ->join('lotes','lotes.id','=','creditos.lote_id')
                                    ->join('personal','personal.id','=','creditos.prospecto_id')
                                    ->select('contratos.id as folio')
                                    ->where('inst_seleccionadas.elegido', '=', 1)
                                    ->where('contratos.status', '=', 3)
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.cobrado','=','0')
                                    ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                    ->where('contratos.status', '=', 3)
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.cobrado','=','0')
                                    ->orderBy('inst_seleccionadas.cobrado','asc')
                                    ->count();
                        }
                        elseif ($buscar2 != '' && $buscar == '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                    ->join('contratos','contratos.id','=','creditos.id')
                                    ->join('lotes','lotes.id','=','creditos.lote_id')
                                    ->join('personal','personal.id','=','creditos.prospecto_id')
                                    ->select('contratos.id as folio')
                                    ->where('inst_seleccionadas.elegido', '=', 1)
                                    ->where('contratos.status', '=', 3)
                                    ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                    ->where('inst_seleccionadas.cobrado','=','0')
                                    ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                    ->where('contratos.status', '=', 3)
                                    ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                    ->where('inst_seleccionadas.cobrado','=','0')
                                    ->orderBy('inst_seleccionadas.cobrado','asc')
                                    ->count();
                        }
                        else{
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','=','0')
    
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);
                            
                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                    ->join('contratos','contratos.id','=','creditos.id')
                                    ->join('lotes','lotes.id','=','creditos.lote_id')
                                    ->join('personal','personal.id','=','creditos.prospecto_id')
                                    ->select('contratos.id as folio')
                                    ->where('inst_seleccionadas.elegido', '=', 1)
                                    ->where('contratos.status', '=', 3)
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                    ->where('inst_seleccionadas.cobrado','=','0')
        
                                    ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                    ->where('contratos.status', '=', 3)
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                    ->where('inst_seleccionadas.cobrado','=','0')
                                    ->orderBy('inst_seleccionadas.cobrado','asc')
                                    ->count();
                        }
                        break;
    
                    }
                    case 'creditos.fraccionamiento': {
                        if($buscar2 == '' && $buscar3 == '' && $buscar4 == ''){
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                            $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 != '' && $buscar3 == '' && $buscar4 == '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                            $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 != '' && $buscar3 != '' && $buscar4 == '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                            $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 != '' && $buscar3 != '' && $buscar4 != '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
    
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                            $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
    
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 == '' && $buscar3 == '' && $buscar4 != '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                            $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 != '' && $buscar3 == '' && $buscar4 != '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                            $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        break;
                    }
    
                    case 'contratos.id': {
                        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                            $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                    }
    
                }
            }
        }
        else{
            if($buscar == '' && $criterio != 'personal.nombre'){
                $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('contratos','contratos.id','=','creditos.id')
                    ->join('lotes','lotes.id','=','creditos.lote_id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('contratos.id as folio', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                    ->where('inst_seleccionadas.cobrado','!=','0')
                    ->where('inst_seleccionadas.elegido', '=', 1)
                    ->where('contratos.status', '=', 3)
                    ->orWhere('inst_seleccionadas.tipo', '=', 1)
                    ->where('inst_seleccionadas.cobrado','!=','0')
                    ->where('contratos.status', '=', 3)
                    ->orderBy('inst_seleccionadas.cobrado','asc')
                    
                    ->paginate(10);

                    $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('contratos','contratos.id','=','creditos.id')
                    ->join('lotes','lotes.id','=','creditos.lote_id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('contratos.id as folio')
                    ->where('inst_seleccionadas.cobrado','!=','0')
                    ->where('inst_seleccionadas.elegido', '=', 1)
                    ->where('contratos.status', '=', 3)
                    ->orWhere('inst_seleccionadas.tipo', '=', 1)
                    ->where('inst_seleccionadas.cobrado','!=','0')
                    ->where('contratos.status', '=', 3)
                    ->orderBy('inst_seleccionadas.cobrado','asc')
                    
                    ->count();
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        if($buscar2 == ''){
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 != '' && $buscar == '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        else{
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
    
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
    
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('personal.apellidos', 'like', '%' . $buscar2 . '%')
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        break;
    
                    }
                    case 'creditos.fraccionamiento': {
                        if($buscar2 == '' && $buscar3 == '' && $buscar4 == ''){
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 != '' && $buscar3 == '' && $buscar4 == '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 != '' && $buscar3 != '' && $buscar4 == '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 != '' && $buscar3 != '' && $buscar4 != '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
    
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
    
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.manzana', '=', $buscar3)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 == '' && $buscar3 == '' && $buscar4 != '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        elseif ($buscar2 != '' && $buscar3 == '' && $buscar4 != '') {
                            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $contador = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.etapa', '=', $buscar2)
                                ->where('creditos.num_lote', '=', $buscar4)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();
                        }
                        break;
                    }
    
                    case 'contratos.id': {
                        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio', 'lotes.credito_puente',
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                                        'personal.nombre','personal.apellidos', 'inst_seleccionadas.id as inst_sel_id',
                                        'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                                        'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->paginate(10);

                                $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('contratos','contratos.id','=','creditos.id')
                                ->join('lotes','lotes.id','=','creditos.lote_id')
                                ->join('personal','personal.id','=','creditos.prospecto_id')
                                ->select('contratos.id as folio')
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orWhere('inst_seleccionadas.tipo', '=', 1)
                                ->where('contratos.status', '=', 3)
                                ->where($criterio, '=', $buscar)
                                ->where('inst_seleccionadas.cobrado','!=','0')
                                ->orderBy('inst_seleccionadas.cobrado','asc')
                                ->count();

                                
                    }
    
                }
            }

        }

        


        return[
                'pagination' => [
                'total'         => $creditos->total(),
                'current_page'  => $creditos->currentPage(),
                'per_page'      => $creditos->perPage(),
                'last_page'     => $creditos->lastPage(),
                'from'          => $creditos->firstItem(),
                'to'            => $creditos->lastItem(),
            ],
            'creditos' => $creditos, 'contador' => $contador
        ];
    }

    public function indexDepCredito(Request $request){

        $depositos = Dep_credito::join('inst_seleccionadas','inst_seleccionadas.id','=','dep_creditos.inst_sel_id')
                            ->select('dep_creditos.id','dep_creditos.cant_depo', 'dep_creditos.banco', 'dep_creditos.fecha_deposito',
                                    'dep_creditos.inst_sel_id', 'inst_seleccionadas.institucion','inst_seleccionadas.monto_credito', 
                                    'inst_seleccionadas.cobrado')
                            ->where('inst_seleccionadas.id', '=', $request->id)
                            ->get();
                            
        return ['depositos' => $depositos];
    }

    public function storeDepositoCredito(Request $request){
        $deposito = new Dep_credito();
        $deposito->inst_sel_id = $request->inst_sel_id;
        $deposito->cant_depo = $request->cant_depo;
        $deposito->banco = $request->banco;
        $deposito->fecha_deposito = $request->fecha_deposito;
        
        $credito = inst_seleccionada::findOrFail($request->inst_sel_id);
        $credito->cobrado = $credito->cobrado + $request->cant_depo;
        $credito->save();

        $deposito->save();
    }

    public function updateDepositoCredito(Request $request){

        $deposito = Dep_credito::findOrFail($request->dep_id);
        $credito = inst_seleccionada::findOrFail($request->inst_sel_id);
        $credito->cobrado = $credito->cobrado - $deposito->cant_depo + $request->cant_depo;
        $credito->save();
        $deposito->inst_sel_id = $request->inst_sel_id;
        $deposito->cant_depo = $request->cant_depo;
        $deposito->banco = $request->banco;
        $deposito->fecha_deposito = $request->fecha_deposito;

        $deposito->save();
    }
}
