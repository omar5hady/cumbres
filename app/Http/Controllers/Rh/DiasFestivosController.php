<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DiaFestivo;

class DiasFestivosController extends Controller
{
    public function index(Request $request){
        $f_ini = $request->b_ini;
        $f_fin = $request->b_fin;

        $dias = DiaFestivo::select('*');
        if($f_fin != '' && $f_ini != '')
            $dias = $dias->whereBetween('fecha', [$f_ini, $f_fin]);

        $dias = $dias->orderBy('fecha', 'asc')->paginate(10);
        return $dias;
    }

    public function store(Request $request){
        $dia = new DiaFestivo();
        $dia->fecha = $request->fecha;
        $dia->medio_dia = $request->medio_dia;
        $dia->nombre = $request->nombre;
        $dia->save();
    }

    public function update(Request $request){
        $dia = DiaFestivo::findOrFail($request->id);
        $dia->fecha = $request->fecha;
        $dia->medio_dia = $request->medio_dia;
        $dia->nombre = $request->nombre;
        $dia->save();
    }

    public function destroy(Request $request){
        $dia = DiaFestivo::findOrFail($request->id);
        $dia->delete();
    }

    public function searchDias(Request $request){
        return DiaFestivo::select('fecha')
            ->whereBetween('fecha', [$rquest->f_ini, $request->f_fin])
            ->get();
    }
}
