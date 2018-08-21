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
        $colonias = Ciudad::where('cp', 'like', '%'. $buscar . '%')
        ->select('colonia')->orderBy('colonia','asc')->get();
        return['colonias' => $colonias];
    }
}
