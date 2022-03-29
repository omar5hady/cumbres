<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;

class RentasController extends Controller
{
    //Función para actualizar la informacion de renta para un lote.
    public function updateDatosRenta(Request $request){
        $lote = Lote::findOrFail($request->id);
        $lote->precio_renta = $request->precio_renta;
        $lote->comentarios = $request->comentarios;
        $lote->save();
    }
}
