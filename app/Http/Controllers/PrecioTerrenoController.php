<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Precios_terreno;
use App\Modelo;
use App\Etapa;

class PrecioTerrenoController extends Controller
{
    public function show(Request $request){
        $etapas = Etapa::join('fraccionamientos', 'etapas.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->select(
                'etapas.id',
                'fraccionamientos.nombre as fracc',
                //'fraccionamientos.tipo_proyecto',
                'etapas.num_etapa'
            );
        
        if($request->b_project != "") $etapas = $etapas->where('fraccionamientos.nombre', '=', $request->b_project);
        
        $etapas = $etapas->where('etapas.num_etapa', '!=', 'Sin Asignar')
        ->paginate(15);

        
        foreach($etapas as $index => $etapa){
            $etapa->precios_terreno;
        }

        return $etapas;
    }

    public function showPrecios(Request $request){
        $precios = Precios_terreno::where('etapa_id', '=', $request->idEtapa)
            ->orderBy('created_at', 'desc')
        ->get();
        return $precios;
    }

    public function storage(Request $request){

        Precios_terreno::where('etapa_id', '=', $request->idEtapa)
        ->update(['estatus' => 0]);

        $precio = new Precios_terreno();

        $precio->etapa_id = $request->idEtapa;
        $precio->precio_m2 = $request->precio;
        $precio->total_gastos = $request->tGastos;

        $precio->save();

        return $this->showPrecios($request);
    }

    public function edit(Request $request){
        
        $precio = Precios_terreno::findOrFail($request->idPrecio);
        
        $precio->precio_m2 = $request->precio;
        $precio->total_gastos = $request->tGastos;

        $precio->save();

        return $this->showPrecios($request);
    }

    public function destroy(Request $request){
        
        $precio = Precios_terreno::findOrFail($request->idPrecio);

        $precio->delete();

        return $this->showPrecios($request);
    }
}
