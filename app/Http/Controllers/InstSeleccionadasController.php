<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\inst_seleccionada;
use DB;
use App\Gasto_admin;
use Carbon\Carbon;
use App\Dep_credito;
use Excel;
use App\Contrato;
use App\Dev_credito;
use Auth;

class InstSeleccionadasController extends Controller
{
    public function indexCreditoSel(Request $request){
        if(!$request->ajax())return redirect('/');
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
                    ->orderBy('inst_seleccionadas.monto_credito','desc')
                    
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
                        ->orderBy('inst_seleccionadas.monto_credito','desc')
                        
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                    ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                    ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                    ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                    ->orderBy('inst_seleccionadas.monto_credito','desc')
                    
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
                    ->orderBy('inst_seleccionadas.monto_credito','desc')
                    
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
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
        if(!$request->ajax())return redirect('/');

        $depositos = Dep_credito::join('inst_seleccionadas','inst_seleccionadas.id','=','dep_creditos.inst_sel_id')
                            ->select('dep_creditos.id','dep_creditos.cant_depo', 'dep_creditos.banco', 'dep_creditos.fecha_deposito',
                                    'dep_creditos.inst_sel_id', 'inst_seleccionadas.institucion','inst_seleccionadas.monto_credito', 
                                    'inst_seleccionadas.cobrado')
                            ->where('inst_seleccionadas.id', '=', $request->id)
                            ->get();
                            
        return ['depositos' => $depositos];
    }

    public function storeDepositoCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            DB::beginTransaction();
            $deposito = new Dep_credito();
            $deposito->inst_sel_id = $request->inst_sel_id;
            $deposito->cant_depo = $request->cant_depo;
            $deposito->banco = $request->banco;
            $deposito->fecha_deposito = $request->fecha_deposito;
            
            $credito = inst_seleccionada::findOrFail($request->inst_sel_id);
            $credito->cobrado = $credito->cobrado + $request->cant_depo;

            $contrato = Contrato::findOrFail($credito->credito_id);
            $contrato->saldo = $contrato->saldo - $deposito->cant_depo;
            $contrato->save();

            $credito->save();

            $deposito->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function updateDepositoCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try {
            DB::beginTransaction();
            $deposito = Dep_credito::findOrFail($request->dep_id);
            $credito = inst_seleccionada::findOrFail($request->inst_sel_id);
            $credito->cobrado = $credito->cobrado - $deposito->cant_depo + $request->cant_depo;

            $contrato = Contrato::findOrFail($credito->credito_id);
            $contrato->saldo = $contrato->saldo + $deposito->cant_depo - $request->cant_depo;
            $contrato->save();

            $credito->save();
            $deposito->inst_sel_id = $request->inst_sel_id;
            $deposito->cant_depo = $request->cant_depo;
            $deposito->banco = $request->banco;
            $deposito->fecha_deposito = $request->fecha_deposito;

            $deposito->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function excelCobroCredito (Request $request){
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
                    ->orderBy('inst_seleccionadas.monto_credito','desc')
                    
                    ->get();

                  
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();
                            
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();
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
                    ->orderBy('inst_seleccionadas.monto_credito','desc')
                    ->get();
                   
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

                                
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

                               
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

                               
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

                                
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

                              
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

                                
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
                                ->orderBy('inst_seleccionadas.monto_credito','desc')
                                ->get();

                               
                    }
    
                }
            }

        }

        return Excel::create('creditos', function($excel) use ($creditos){
            $excel->sheet('creditos', function($sheet) use ($creditos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto','Etapa', 'Manzana',
                    '# Lote','Credito puente', 'Institución', 'Crédito', 'Cobrado',
                    'Pendiente'
                ]);


                $sheet->cells('A1:K1', function ($cells) {
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

                foreach($creditos as $index => $credito) {
                    $cont++;

                    $montoCredito = number_format((float)$credito->monto_credito, 2, '.', ',');
                    $cobrado = number_format((float)$credito->cobrado, 2, '.', ',');
                    $pend = $credito->monto_credito - $credito->cobrado;
                    $pendiente = number_format((float)$pend, 2, '.', ',');

                    $sheet->row($index+2, [
                        $credito->folio, 
                        $credito->nombre. ' ' . $credito->apellidos,
                        $credito->proyecto,
                        $credito->etapa,
                        $credito->manzana,
                        $credito->num_lote,
                        $credito->credito_puente,
                        $credito->institucion,
                        '$ '.$montoCredito,
                        '$ '.$cobrado,
                        '$ '.$pendiente
                        

                    ]);	
                }
                $num='A1:K' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    public function indexDevolucion (Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        if($buscar == ''){
            $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('contratos','contratos.id','=','creditos.id')
                    ->join('lotes','lotes.id','=','creditos.lote_id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('contratos.id', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
                            )

                    ->where('contratos.saldo','<',0)
                    ->where('inst_seleccionadas.elegido', '=', 1)
                    ->orderBy('inst_seleccionadas.cobrado','asc')
                    ->orderBy('inst_seleccionadas.monto_credito','desc')
                    
                    ->paginate(10);
        }
        else{
            switch($criterio){
                case 'creditos.id':{
                    $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('contratos','contratos.id','=','creditos.id')
                    ->join('lotes','lotes.id','=','creditos.lote_id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('contratos.id', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
                            )

                        ->where('contratos.saldo','<',0)
                        ->where('inst_seleccionadas.elegido', '=', 1)
                        ->where($criterio,'=',$buscar)
                        ->orderBy('inst_seleccionadas.cobrado','asc')
                        ->orderBy('inst_seleccionadas.monto_credito','desc')
                        
                        ->paginate(10);

                    break;
                }
                case 'personal.nombre':{
                    $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('contratos','contratos.id','=','creditos.id')
                    ->join('lotes','lotes.id','=','creditos.lote_id')
                    ->join('personal','personal.id','=','creditos.prospecto_id')
                    ->select('contratos.id', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
                            )
                        ->where('contratos.saldo','<',0)
                        ->where('inst_seleccionadas.elegido', '=', 1)
                        ->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%')
                        ->orderBy('inst_seleccionadas.cobrado','asc')
                        ->orderBy('inst_seleccionadas.monto_credito','desc')
                        
                        ->paginate(10);
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('contratos','contratos.id','=','creditos.id')
                        ->join('lotes','lotes.id','=','creditos.lote_id')
                        ->join('personal','personal.id','=','creditos.prospecto_id')
                        ->select('contratos.id', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
                            )
                        ->where('contratos.saldo','<',0)
                        ->where('inst_seleccionadas.elegido', '=', 1)
                        ->where($criterio,'=',$buscar)
                        ->orderBy('inst_seleccionadas.cobrado','asc')
                        ->orderBy('inst_seleccionadas.monto_credito','desc')
                        
                        ->paginate(10);
                        
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('contratos','contratos.id','=','creditos.id')
                        ->join('lotes','lotes.id','=','creditos.lote_id')
                        ->join('personal','personal.id','=','creditos.prospecto_id')
                        ->select('contratos.id', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
                            )
                        ->where('contratos.saldo','<',0)
                        ->where('inst_seleccionadas.elegido', '=', 1)
                        ->where($criterio,'=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->orderBy('inst_seleccionadas.cobrado','asc')
                        ->orderBy('inst_seleccionadas.monto_credito','desc')
                        
                        ->paginate(10);
                        
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('contratos','contratos.id','=','creditos.id')
                        ->join('lotes','lotes.id','=','creditos.lote_id')
                        ->join('personal','personal.id','=','creditos.prospecto_id')
                        ->select('contratos.id', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
                            )
                        ->where('contratos.saldo','<',0)
                        ->where('inst_seleccionadas.elegido', '=', 1)
                        ->where($criterio,'=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->orderBy('inst_seleccionadas.cobrado','asc')
                        ->orderBy('inst_seleccionadas.monto_credito','desc')
                        
                        ->paginate(10);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('contratos','contratos.id','=','creditos.id')
                        ->join('lotes','lotes.id','=','creditos.lote_id')
                        ->join('personal','personal.id','=','creditos.prospecto_id')
                        ->select('contratos.id', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
                            )
                        ->where('contratos.saldo','<',0)
                        ->where('inst_seleccionadas.elegido', '=', 1)
                        ->where($criterio,'=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('inst_seleccionadas.cobrado','asc')
                        ->orderBy('inst_seleccionadas.monto_credito','desc')
                        
                        ->paginate(10);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('contratos','contratos.id','=','creditos.id')
                        ->join('lotes','lotes.id','=','creditos.lote_id')
                        ->join('personal','personal.id','=','creditos.prospecto_id')
                        ->select('contratos.id', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
                            )
                        ->where('contratos.saldo','<',0)
                        ->where('inst_seleccionadas.elegido', '=', 1)
                        ->where($criterio,'=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('inst_seleccionadas.cobrado','asc')
                        ->orderBy('inst_seleccionadas.monto_credito','desc')
                        
                        ->paginate(10);
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $creditos = inst_seleccionada::join('creditos','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('contratos','contratos.id','=','creditos.id')
                        ->join('lotes','lotes.id','=','creditos.lote_id')
                        ->join('personal','personal.id','=','creditos.prospecto_id')
                        ->select('contratos.id', 'lotes.credito_puente',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote', 
                            'personal.nombre','personal.apellidos', 
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            'inst_seleccionadas.id as inst_sel_id', 'contratos.saldo',
                            'inst_seleccionadas.tipo_credito', 'inst_seleccionadas.institucion', 
                            'inst_seleccionadas.elegido', 'inst_seleccionadas.monto_credito','inst_seleccionadas.cobrado'
                            )
                        ->where('contratos.saldo','<',0)
                        ->where('inst_seleccionadas.elegido', '=', 1)
                        ->where($criterio,'=',$buscar)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('inst_seleccionadas.cobrado','asc')
                        ->orderBy('inst_seleccionadas.monto_credito','desc')
                        
                        ->paginate(10);
                    }

                    break;
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
            'creditos' => $creditos
        ];
        
    }

    public function storeDevolucion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();

            $devolucion = new Dev_credito();
            $devolucion->contrato_id = $request->id;
            $devolucion->devolver = $request->cant_dev;
            $devolucion->fecha = $request->fecha;
            $devolucion->cheque = $request->cheque;
            $devolucion->cuenta = $request->cuenta;
            $devolucion->observaciones = $request->observaciones;
            $devolucion->save();

            $contrato = Contrato::findOrFail($request->id);
            $contrato->saldo += $request->cant_dev;
            $contrato->save();

            $gastos = new Gasto_admin();
            $gastos->contrato_id = $request->id;
            $gastos->concepto = "Devolución por excedente";
            $gastos->costo = $request->cant_dev;
            $gastos->observacion = $request->observaciones;
            $gastos->fecha = $request->fecha;
            $gastos->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function indexHistorialDev(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
       
        if ($buscar == '') {
            $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                ->select(
                    'creditos.id',
                    'creditos.prospecto_id',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.modelo',
                    'creditos.precio_base',
                    'creditos.precio_venta',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.lote_id',

                    'personal.nombre',
                    'personal.apellidos',
                    'personal.telefono',
                    'personal.celular',
                    'personal.email',
                    'personal.direccion',
                    'personal.cp',
                    'personal.colonia',
                    'personal.f_nacimiento',
                    'personal.rfc',
                    'personal.homoclave',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                    
                    'v.nombre as vendedor_nombre',
                    'v.apellidos as vendedor_apellidos',
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    

                    'contratos.status',
                    'contratos.fecha_status',
                    'contratos.total_pagar',
                    'contratos.monto_total_credito',
                    'contratos.enganche_total',
                    'contratos.avance_lote',
                    'contratos.observacion',

                    'dev_creditos.fecha',
                    'dev_creditos.cheque',
                    'dev_creditos.cuenta',
                    'dev_creditos.observaciones',
                    'dev_creditos.devolver',


                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                    
                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                )
                ->orderBy('id', 'desc')->paginate(8);
           
        }
        else{
            switch ($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == '')
                    {
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',
                            

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    
                    break;
                }
                case 'creditos.id':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                    ->select(
                        'creditos.id',
                        'creditos.prospecto_id',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.modelo',
                        'creditos.precio_base',
                        'creditos.precio_venta',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.lote_id',

                        'personal.nombre',
                        'personal.apellidos',
                        'personal.telefono',
                        'personal.celular',
                        'personal.email',
                        'personal.direccion',
                        'personal.cp',
                        'personal.colonia',
                        'personal.f_nacimiento',
                        'personal.rfc',
                        'personal.homoclave',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                        
                        'v.nombre as vendedor_nombre',
                        'v.apellidos as vendedor_apellidos',
                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                        

                        'contratos.status',
                        'contratos.fecha_status',
                        'contratos.total_pagar',
                        'contratos.monto_total_credito',
                        'contratos.enganche_total',
                        'contratos.avance_lote',
                        'contratos.observacion',

                        'dev_creditos.fecha',
                        
                        'dev_creditos.cheque',
                        'dev_creditos.cuenta',
                        'dev_creditos.observaciones',
                        'dev_creditos.devolver',

                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                        
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                    )
                    ->where($criterio, '=', $buscar)
                    ->orderBy('id', 'desc')->paginate(8);
                    break;
                }
                case 'personal.nombre':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, 'like', '%' . $buscar . '%')
                        ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                        ->orderBy('id', 'desc')->paginate(8);
                    
                    break;
                }
            }
        }
        
        return [
            'pagination' => [
                'total'         => $devoluciones->total(),
                'current_page'  => $devoluciones->currentPage(),
                'per_page'      => $devoluciones->perPage(),
                'last_page'     => $devoluciones->lastPage(),
                'from'          => $devoluciones->firstItem(),
                'to'            => $devoluciones->lastItem(),
            ], 'devoluciones' => $devoluciones//, 'contadorContrato' => $contadorContratos
        ];
    }

    public function excelHistDev(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
       
        if ($buscar == '') {
            $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                ->select(
                    'creditos.id',
                    'creditos.prospecto_id',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.modelo',
                    'creditos.precio_base',
                    'creditos.precio_venta',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.lote_id',

                    'personal.nombre',
                    'personal.apellidos',
                    'personal.telefono',
                    'personal.celular',
                    'personal.email',
                    'personal.direccion',
                    'personal.cp',
                    'personal.colonia',
                    'personal.f_nacimiento',
                    'personal.rfc',
                    'personal.homoclave',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                    
                    'v.nombre as vendedor_nombre',
                    'v.apellidos as vendedor_apellidos',
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    

                    'contratos.status',
                    'contratos.fecha_status',
                    'contratos.total_pagar',
                    'contratos.monto_total_credito',
                    'contratos.enganche_total',
                    'contratos.avance_lote',
                    'contratos.observacion',

                    'dev_creditos.fecha',
                    'dev_creditos.cheque',
                    'dev_creditos.cuenta',
                    'dev_creditos.observaciones',
                    'dev_creditos.devolver',


                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                    
                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                )
                ->orderBy('id', 'desc')->paginate(8);
           
        }
        else{
            switch ($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == '')
                    {
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',
                            

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    
                    break;
                }
                case 'creditos.id':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                    ->select(
                        'creditos.id',
                        'creditos.prospecto_id',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.modelo',
                        'creditos.precio_base',
                        'creditos.precio_venta',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.lote_id',

                        'personal.nombre',
                        'personal.apellidos',
                        'personal.telefono',
                        'personal.celular',
                        'personal.email',
                        'personal.direccion',
                        'personal.cp',
                        'personal.colonia',
                        'personal.f_nacimiento',
                        'personal.rfc',
                        'personal.homoclave',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                        
                        'v.nombre as vendedor_nombre',
                        'v.apellidos as vendedor_apellidos',
                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                        

                        'contratos.status',
                        'contratos.fecha_status',
                        'contratos.total_pagar',
                        'contratos.monto_total_credito',
                        'contratos.enganche_total',
                        'contratos.avance_lote',
                        'contratos.observacion',

                        'dev_creditos.fecha',
                        
                        'dev_creditos.cheque',
                        'dev_creditos.cuenta',
                        'dev_creditos.observaciones',
                        'dev_creditos.devolver',

                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                        
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                    )
                    ->where($criterio, '=', $buscar)
                    ->orderBy('id', 'desc')->paginate(8);
                    break;
                }
                case 'personal.nombre':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('dev_creditos','contratos.id','=','dev_creditos.contrato_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            'dev_creditos.fecha',
                            
                            'dev_creditos.cheque',
                            'dev_creditos.cuenta',
                            'dev_creditos.observaciones',
                            'dev_creditos.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, 'like', '%' . $buscar . '%')
                        ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                        ->orderBy('id', 'desc')->paginate(8);
                    
                    break;
                }
            }
        }
        
        return Excel::create('devoluciones', function($excel) use ($devoluciones){
            $excel->sheet('devoluciones', function($sheet) use ($devoluciones){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto', 'Etapa', 'Manzana',
                    '# Lote','Devuelto', 'Fecha cancelación', 'Fecha devolución',
                    '# Cheque', 'Cuenta'
                ]);

                $sheet->setColumnFormat(array(
                    'G' => '$#,##0.00',
                ));


                $sheet->cells('A1:K1', function ($cells) {
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

                foreach($devoluciones as $index => $devolucion) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($devolucion->fecha);
                    $fecha2 = new Carbon($devolucion->fecha_status);
                    $devolucion->fecha = $fecha1->formatLocalized('%d de %B de %Y');
                    $devolucion->fecha_status = $fecha2->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $devolucion->id, 
                        $devolucion->nombre. ' ' . $devolucion->apellidos,
                        $devolucion->proyecto,
                        $devolucion->etapa,
                        $devolucion->manzana,
                        $devolucion->num_lote,
                        $devolucion->devolver,
                        $devolucion->fecha_status,
                        $devolucion->fecha,
                        $devolucion->cheque,
                        $devolucion->cuenta,

                    ]);	
                }
                $num='A1:K' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }
}
