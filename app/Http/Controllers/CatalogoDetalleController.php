<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cat_detalle_general;
use App\Cat_detalle_subconcepto;
use App\Cat_detalle;
use Auth;
use Illuminate\Support\Facades\DB;

class CatalogoDetalleController extends Controller
{
    public function indexGenerales(Request $request){
        if($request->buscar == '')
        {
            $general = Cat_detalle_general::orderBy('general','asc')->paginate(12);
        }
        else{
            $general = Cat_detalle_general::where($request->criterio,'like', '%' . $request->buscar . '%')->orderBy('general','asc')->paginate(12);
        }

        return [
            'pagination' => [
                'total'         => $general->total(),
                'current_page'  => $general->currentPage(),
                'per_page'      => $general->perPage(),
                'last_page'     => $general->lastPage(),
                'from'          => $general->firstItem(),
                'to'            => $general->lastItem(),
            ],
            'general' => $general
        ];
    }

    public function storeGenerales (Request $request){
        $generales = new Cat_detalle_general();
        $generales->general = $request->detalle_general;
        $generales->dias_garantia = $request->dias_garantia;
        $generales->save();
    }

    public function updateGenerales (Request $request){
        $generales = Cat_detalle_general::findOrFail($request->id);
        $generales->general = $request->detalle_general;
        $generales->dias_garantia = $request->dias_garantia;
        $generales->save();
    }

    public function indexSubCategoria(Request $request){

        $id_gral = $request->id_gral;
        $subconcepto = $request->b_subconcepto;

        if($id_gral == '' && $subconcepto == '')
        {
            $subconceptos = Cat_detalle_subconcepto::join('cat_detalles_generales as gen','cat_detalles_subconceptos.id_gral','=','gen.id')
                        ->select('gen.general','cat_detalles_subconceptos.id', 'cat_detalles_subconceptos.id_gral','cat_detalles_subconceptos.subconcepto')
                        ->orderBy('gen.general','asc')->orderBy('cat_detalles_subconceptos.subconcepto','asc')->paginate(12);
        }
        else{
            if($subconcepto == '' && $id_gral != ''){
                $subconceptos = Cat_detalle_subconcepto::join('cat_detalles_generales as gen','cat_detalles_subconceptos.id_gral','=','gen.id')
                        ->select('gen.general','cat_detalles_subconceptos.id', 'cat_detalles_subconceptos.id_gral','cat_detalles_subconceptos.subconcepto')
                        ->where('cat_detalles_subconceptos.id_gral','=',$id_gral)
                        ->orderBy('gen.general','asc')->orderBy('cat_detalles_subconceptos.subconcepto','asc')->paginate(12);
            }
            elseif($subconcepto != '' && $id_gral == ''){
                $subconceptos = Cat_detalle_subconcepto::join('cat_detalles_generales as gen','cat_detalles_subconceptos.id_gral','=','gen.id')
                        ->select('gen.general','cat_detalles_subconceptos.id', 'cat_detalles_subconceptos.id_gral','cat_detalles_subconceptos.subconcepto')
                        ->where('cat_detalles_subconceptos.subconcepto','like','%'. $subconcepto .'%')
                        ->orderBy('gen.general','asc')->orderBy('cat_detalles_subconceptos.subconcepto','asc')->paginate(12);
            }
            else{
                $subconceptos = Cat_detalle_subconcepto::join('cat_detalles_generales as gen','cat_detalles_subconceptos.id_gral','=','gen.id')
                        ->select('gen.general','cat_detalles_subconceptos.id', 'cat_detalles_subconceptos.id_gral','cat_detalles_subconceptos.subconcepto')
                        ->where('cat_detalles_subconceptos.id_gral','=',$id_gral)
                        ->where('cat_detalles_subconceptos.subconcepto','like','%'. $subconcepto .'%')
                        ->orderBy('gen.general','asc')->orderBy('cat_detalles_subconceptos.subconcepto','asc')->paginate(12);
            }
            
        }

        return [
            'pagination' => [
                'total'         => $subconceptos->total(),
                'current_page'  => $subconceptos->currentPage(),
                'per_page'      => $subconceptos->perPage(),
                'last_page'     => $subconceptos->lastPage(),
                'from'          => $subconceptos->firstItem(),
                'to'            => $subconceptos->lastItem(),
            ],
            'subconceptos' => $subconceptos
        ];
    }

    public function indexDetalles(Request $request){

        $id_sub = $request->id_sub;
        $detalle = $request->detalle;

        if($detalle == '')
        {
            $detalles = Cat_detalle::join('cat_detalles_subconceptos as sub','cat_detalles.id_sub','=','sub.id')
                        ->select('sub.subconcepto','cat_detalles.id','cat_detalles.detalle','cat_detalles.activo')
                        ->where('sub.id','=',$id_sub)->orderBy('cat_detalles.activo','desc')
                        ->orderBy('sub.subconcepto','asc')->orderBy('cat_detalles.detalle','asc')->paginate(12);
        }
        else{
            $detalles = Cat_detalle::join('cat_detalles_subconceptos as sub','cat_detalles.id_sub','=','sub.id')
                        ->select('sub.subconcepto','cat_detalles.id','cat_detalles.detalle','cat_detalles.activo')
                        ->where('sub.id','=',$id_sub)
                        ->where('cat_detalles.detalle','like','%'. $detalle .'%')->orderBy('cat_detalles.activo','desc')
                        ->orderBy('sub.subconcepto','asc')->orderBy('cat_detalles.detalle','asc')->paginate(12);
        }

        return [
            'pagination' => [
                'total'         => $detalles->total(),
                'current_page'  => $detalles->currentPage(),
                'per_page'      => $detalles->perPage(),
                'last_page'     => $detalles->lastPage(),
                'from'          => $detalles->firstItem(),
                'to'            => $detalles->lastItem(),
            ],
            'detalles' => $detalles
        ];
    }

    public function selectGeneral(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        
        $general = Cat_detalle_general::select('general','id')->orderBy('general','asc')->get();
        return['general' => $general];
    }

    public function storeSubconcepto(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $subconcepto = new Cat_detalle_subconcepto();
        $subconcepto->id_gral = $request->id_gral;
        $subconcepto->subconcepto = $request->subconcepto;
        $subconcepto->save();
    }

    public function updateSubconcepto(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $subconcepto = Cat_detalle_subconcepto::findOrFail($request->id);
        $subconcepto->id_gral = $request->id_gral;
        $subconcepto->subconcepto = $request->subconcepto;
        $subconcepto->save();
    }

    public function storeDetalle(Request $request){
        $detalles = new Cat_detalle();
        $detalles->detalle = $request->detalle;
        $detalles->id_sub = $request->id_sub;
        $detalles->save();
    }

    public function updateDetalle(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $detalle = Cat_detalle::findOrFail($request->id);
        $detalle->detalle = $request->detalle;
        $detalle->save();
    }

    public function activarDetalle(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $detalle = Cat_detalle::findOrFail($request->id);
        $detalle->activo = 1;
        $detalle->save();
    }

    public function desactivarDetalle(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $detalle = Cat_detalle::findOrFail($request->id);
        $detalle->activo = 0;
        $detalle->save();
    }
}
