<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campania;
use Auth;
use DB;
use Carbon\Carbon;

// Controlador para campañas digitales.
class CampaniaController extends Controller
{
    // Función para retornar las campañas creadas
    public function indexCampanias(Request $request){
        $campania = Campania::select('nombre_campania','medio_digital','fecha_ini','fecha_fin','id','presupuesto');

        if($request->buscar != ''){
            $campania = $campania->where('nombre_campania','like','%'.$request->buscar.'%')
                                ->orWhere('medio_digital','like','%'.$request->buscar.'%');
        }

        return $campania->paginate(8);
    }

    // Función para actualizar los datos de la campaña
    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $campania = Campania::findOrFail($request->id);
        $campania->nombre_campania = $request->nombre;
        $campania->medio_digital = $request->medio_digital;
        $campania->fecha_ini = $request->fecha_ini;
        $campania->fecha_fin = $request->fecha_fin;
        $campania->presupuesto = $request->presupuesto;
        $campania->save();
    }

    // Función para crear el registro de una nueva campaña.
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $data = $request->medio_digital;

        foreach ($data as $index => $medio) {
            $campania = new Campania();
            $campania->nombre_campania = $request->nombre;
            $campania->medio_digital = $medio;
            $campania->fecha_ini = $request->fecha_ini;
            $campania->fecha_fin = $request->fecha_fin;
            $campania->presupuesto = $request->presupuesto;
            $campania->save();
        }

    }

    // Función para eliminar la campaña.
    public function delete(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $campania = Campania::findOrFail($request->id);
        $campania->delete();
    }

    // Función para retornar las campañas activas.
    public function campaniaActiva(Request $request){
        $current = Carbon::now()->toDateString();
        $campanias = Campania::select(DB::raw("CONCAT(nombre_campania,'-',medio_digital) AS campania_comp"),
                'nombre_campania','medio_digital','fecha_ini','fecha_fin','id','presupuesto');
                    if($request->buscar == ''){
                        $campanias = $campanias->whereDate('fecha_ini','<=',$current)
                        ->whereDate('fecha_fin','>=',$current);
                    }
                    if($request->campania != '')
                        $campanias = $campanias->where('nombre_campania','like','%'.$request->campania.'%');

                    $campanias = $campanias->orderBy('nombre_campania','asc')->get();

        return $campanias;
    }
}
