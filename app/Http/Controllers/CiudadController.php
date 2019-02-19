<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ciudad; //Importar el modelo

class CiudadController extends Controller
{
    public function selectCiudades(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        //$ciudades = Ciudad::where('estado', 'like', 'San Luis Potosí')
        $ciudades = Ciudad::where('estado', 'like', '%'. $buscar . '%')
        ->select('municipio')->distinct()->orderBy('municipio','asc')->get();
        return['ciudades' => $ciudades];
    }

    public function selectColonias(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        //$colonias = Ciudad::where('estado', 'like', 'San Luis Potosí')
        $colonias = Ciudad::where('cp', '=', $buscar)
        ->select('colonia')->orderBy('colonia','asc')->get();
        return['colonias' => $colonias];
    }

    public function selectColoniasVue(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $filtro = $request->filtro;
        $colonias = Ciudad::where('cp', '=', $buscar)
        ->where('colonia', 'like', '%'.$filtro.'%')
        ->select('colonia')->orderBy('colonia','asc')->get();
        return['colonias' => $colonias];
    }


    public function selectEstados (Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $estados = Ciudad::select('estado')
                           ->distinct()
                           ->orderBy('estado','asc')->get();

        return ['estados' => $estados];
    }

    
}
