<?php

namespace App\Http\Controllers\Privada;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Urban_equipment;
use DB;

class UrbanEquipmentController extends Controller
{
    public function store(Request $request){
        $equipamiento = new Urban_equipment();
        $equipamiento->categoria = $request->categoria;
        $equipamiento->nombre = $request->nombre;
        $equipamiento->descripcion = $request->descripcion;
        $equipamiento->fraccionamiento_id = $request->fraccionamiento_id;
        $equipamiento->save();

        return $equipamiento;
    }

    public function update(Request $request){
        $equipamiento = Urban_equipment::findOrFail($request->id);
        $equipamiento->categoria = $request->categoria;
        $equipamiento->nombre = $request->nombre;
        $equipamiento->descripcion = $request->descripcion;
        $equipamiento->fraccionamiento_id = $request->fraccionamiento_id;
        $equipamiento->save();

        return($equipamiento->id);
    }

    public function destroy(Request $request){
        $equipamiento = Urban_equipment::findOrFail($request->id);
        $equipamiento->delete();
    }
}
