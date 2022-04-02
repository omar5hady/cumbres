<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;
use App\Licencia;
use App\Arrendador;

class RentasController extends Controller
{
    public function getArrendador(Request $request){
        if(!$request->ajax())return redirect('/');
        return Arrendador::orderBy('tipo','asc')->get();
    }

    //Función para actualizar la informacion de renta para un lote.
    public function updateDatosRenta(Request $request){
        if(!$request->ajax())return redirect('/');
        $lote = Lote::findOrFail($request->id);
        $lote->precio_renta = $request->precio_renta;
        $lote->comentarios = $request->comentarios;
        $lote->save();

        $licencia = Licencia::findOrFail($request->id);
        $licencia->duenio_id = $request->duenio_id;
        $licencia->save();
    }

    public function storeArrendador(Request $request){
        if(!$request->ajax())return redirect('/');
        $arrendador = new Arrendador();
        $arrendador->nombre = $request->nombre;
        $arrendador->tipo = $request->tipo;
        $arrendador->rfc = $request->rfc;
        $arrendador->direccion = $request->direccion;
        $arrendador->cp = $request->cp;
        $arrendador->colonia = $request->colonia;
        $arrendador->municipio = $request->municipio;
        $arrendador->estado = $request->estado;
        $arrendador->save();
    }

    public function updateArrendador(Request $request){
        if(!$request->ajax())return redirect('/');
        $arrendador = Arrendador::findOrFail($request->id);
        $arrendador->nombre = $request->nombre;
        $arrendador->tipo = $request->tipo;
        $arrendador->rfc = $request->rfc;
        $arrendador->direccion = $request->direccion;
        $arrendador->cp = $request->cp;
        $arrendador->colonia = $request->colonia;
        $arrendador->municipio = $request->municipio;
        $arrendador->estado = $request->estado;
        $arrendador->save();
    }
}
