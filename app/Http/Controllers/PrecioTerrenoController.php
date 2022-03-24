<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Precios_terreno;
use App\Modelo;
use App\Etapa;

class PrecioTerrenoController extends Controller
{
    // Funcion que regresa un objeto con la informacion de las etapas  que sean diferentes de "sin asignar"
    public function show(Request $request){
        $etapas = Etapa::join('fraccionamientos', 'etapas.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->select(
                'etapas.id',
                'fraccionamientos.nombre as fracc',
                //'fraccionamientos.tipo_proyecto',
                'etapas.num_etapa'
            );
        
        // filtra en caso de que se especifique el proyecto
        if($request->b_project != "") $etapas = $etapas->where('fraccionamientos.nombre', '=', $request->b_project);
        
        $etapas = $etapas->where('etapas.num_etapa', '!=', 'Sin Asignar')
        ->paginate(15);

        
        foreach($etapas as $index => $etapa){ // se agrega un elemento al objeto 
            $etapa->precios_terreno;
        }

        return $etapas;
    }


    //Funcion que regresa un objeto con la informacion de los precios de terreno filtrados por el id de la etapa 
    public function showPrecios(Request $request){ 
        $precios = Precios_terreno::where('etapa_id', '=', $request->idEtapa)
            ->orderBy('created_at', 'desc')
        ->get();
        return $precios;
    }

    // Funcion para actualizar el status de un registro de precio terreno 
    public function storage(Request $request){

        Precios_terreno::where('etapa_id', '=', $request->idEtapa)
        ->update(['estatus' => 0]);

        $precio = new Precios_terreno();  // crea nuevo registro 

        $precio->etapa_id = $request->idEtapa;
        $precio->precio_m2 = $request->precio;
        $precio->total_gastos = $request->tGastos;
        $precio->estatus = 1;

        $precio->save();  // guarda el nuevo registro 

        return $this->showPrecios($request);  // hace referencia a una funcion para mostrar los precios de terreno
    }

    public function edit(Request $request){
        
        $precio = Precios_terreno::findOrFail($request->idPrecio); // busca el id a buscar 
        
        $precio->precio_m2 = $request->precio;   // edita los campos
        $precio->total_gastos = $request->tGastos;

        $precio->save();

        return $this->showPrecios($request);// hace referencia a una funcion para mostrar los precios de terreno
    }


    // Funcion para eliminar un registro 
    public function destroy(Request $request){ 
        
        $precio = Precios_terreno::findOrFail($request->idPrecio); 

        $precio->delete();

        return $this->showPrecios($request); // hace referencia a una funcion para mostrar los precios de terreno
    }
}
