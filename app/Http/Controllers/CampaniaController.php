<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campania;
use Auth;
use Carbon\Carbon;

class CampaniaController extends Controller
{
    public function indexCampanias(Request $request){
        $campania = Campania::select('nombre_campania','medio_digital','fecha_ini','fecha_fin','id');

        if($request->buscar != ''){
            $campania = $campania->where('nombre_campania','like','%'.$request->buscar.'%')
                                ->orWhere('medio_digital','like','%'.$request->buscar.'%');
        }

        return $campania->paginate(8);
    }

    public function update(Request $request){
        $campania = Campania::findOrFail($request->id);
        $campania->nombre_campania = $request->nombre;
        $campania->medio_digital = $request->medio_digital;
        $campania->fecha_ini = $request->fecha_ini;
        $campania->fecha_fin = $request->fecha_fin;
        $campania->save();
    }

    public function store(Request $request){
        $data = $request->medio_digital;

        foreach ($data as $index => $medio) {
            $campania = new Campania();
            $campania->nombre_campania = $request->nombre;
            $campania->medio_digital = $medio;
            $campania->fecha_ini = $request->fecha_ini;
            $campania->fecha_fin = $request->fecha_fin;
            $campania->save();
        }
        
    }

    public function delete(Request $request){
        $campania = Campania::findOrFail($request->id);
        $campania->delete();
    }

    public function campaniaActiva(Request $request){
        $current = Carbon::now()->toDateString();
        $campanias = Campania::select('nombre_campania','medio_digital','fecha_ini','fecha_fin','id');
                    if($request->buscar == ''){
                        $campanias = $campanias->whereDate('fecha_ini','<=',$current)
                        ->whereDate('fecha_fin','>=',$current);
                    }
                    
                    
                    $campanias = $campanias->orderBy('nombre_campania','asc')->get();

        return $campanias;
    }
}
