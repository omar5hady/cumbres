<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalogo_bono;
use Auth;
use Carbon\Carbon;
use DB;

class CatalogoBonoController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $current = Carbon::today()->format('ymd');
        $catalogo = Catalogo_bono::join('etapas as e','catalogo_bonos.etapa_id','=','e.id')
                    ->join('fraccionamientos as f','e.fraccionamiento_id','=','f.id')
                    ->select('catalogo_bonos.id', 'catalogo_bonos.fecha_ini', 'catalogo_bonos.fecha_fin', 
                            'catalogo_bonos.descripcion','catalogo_bonos.monto','catalogo_bonos.etapa_id',
                            'e.fraccionamiento_id','e.num_etapa','f.nombre as proyecto',
                            DB::raw('(CASE WHEN catalogo_bonos.fecha_fin >= ' . $current . ' THEN 1 ELSE 0 END) AS is_active')); 

            if($request->buscar != ''){
                $catalogo = $catalogo->where('e.fraccionamiento_id','=',$request->buscar);
            }

            if($request->b_etapa != ''){
                $catalogo = $catalogo->where('catalogo_bonos.etapa_id','=',$request->b_etapa);
            }

            
        $catalogo = $catalogo->orderBy('catalogo_bonos.fecha_fin','desc')
                        ->paginate(8);
                    
        return [
            'pagination' => [
                'total'         => $catalogo->total(),
                'current_page'  => $catalogo->currentPage(),
                'per_page'      => $catalogo->perPage(),
                'last_page'     => $catalogo->lastPage(),
                'from'          => $catalogo->firstItem(),
                'to'            => $catalogo->lastItem(),
            ], 
            'catalogo'=>$catalogo ];
    }


    public function store(Request $request){
        $catalogo = new Catalogo_bono();
        $catalogo->fecha_ini = $request->fecha_ini;
        $catalogo->fecha_fin = $request->fecha_fin;
        $catalogo->descripcion = $request->descripcion;
        $catalogo->monto = $request->monto;
        $catalogo->etapa_id = $request->etapa_id;
        $catalogo->save();
    }

    public function update(Request $request){
        $catalogo = Catalogo_bono::findOrFail($request->id);
        $catalogo->fecha_ini = $request->fecha_ini;
        $catalogo->fecha_fin = $request->fecha_fin;
        $catalogo->descripcion = $request->descripcion;
        $catalogo->monto = $request->monto;
        $catalogo->etapa_id = $request->etapa_id;
        $catalogo->save();
    }

    public function delete(Request $request){
        $catalogo = Catalogo_bono::findOrFail($request->id);
        $catalogo->delete();
    }

}
