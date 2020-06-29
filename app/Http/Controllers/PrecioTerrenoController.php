<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Precios_terreno;
use App\Modelo;
use App\Etapa;

class PrecioTerrenoController extends Controller
{
    public function show(Request $request)
    {
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
        $precios = Precios_terreno::where('etapa_id', '=', $request->idEtapa)->get();

        return $precios;
    }

    public function storage(Request $request){
        $precio = new Precios_terreno();

        $precio->etapa_id = $request->idEtapa;
        $precio->precio_m2 = $request->precio;

        $precio->save();

        return $this->showPrecios($request);
    }

    public function edit(Request $request){
        
        $precio = Precios_terreno::findOrFail($request->idPrecio);
        
        $precio->precio_m2 = $request->precio;

        $precio->save();

        return $this->showPrecios($request);
    }

    public function destroy(Request $request){
        
        $precio = Precios_terreno::findOrFail($request->idPrecio);

        $precio->delete();

        return $this->showPrecios($request);
    }
}
