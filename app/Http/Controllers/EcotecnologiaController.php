<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ecotecnologia;

class EcotecnologiaController extends Controller
{
    //
    public function index(Request $request){
        return Ecotecnologia::where('ecotecnologia','like','%'.$request->buscar.'%')->limit(10)->get();
    }

    public function store(Request $request){
        $eco = new Ecotecnologia();
        $eco->ecotecnologia = $request->ecotecnologia;
        $eco->precio = $request->precio;
        $eco->save();
    }

    public function update(Request $request){
        $eco = Ecotecnologia::findOrFail($request->id);
        $eco->ecotecnologia = $request->ecotecnologia;
        $eco->precio = $request->precio;
        $eco->save();
    }

    public function destroy(Request $request){
        $eco = Ecotecnologia::findOrFail($request->id);
        $eco->delete();
    }
}
