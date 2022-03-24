<?php

namespace App\Http\Controllers;
use App\Precio_modelo;

use Illuminate\Http\Request;
use App\Precio_etapa;
use App\Lote;
use App\Credito;
use Auth;

class PrecioModeloController extends Controller
{
    // funcion para optener los precios por modelo 
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        
            $precios_modelos = 
            Precio_modelo::join('precios_etapas','precios_modelos.precio_etapa_id','=','precios_etapas.id')
            ->join('modelos','precios_modelos.modelo_id','=','modelos.id')
            ->select('modelos.nombre as modelo','precios_etapas.etapa_id as etapa', 
                    'precios_etapas.fraccionamiento_id as proyecto', 
                    'precios_modelos.precio_modelo','precios_modelos.id',
                    'precios_modelos.modelo_id','precios_modelos.precio_etapa_id' )
                    ->where('precios_modelos.precio_etapa_id', '=', $buscar )
                ->orderBy('modelos.nombre','asc')->paginate(20);
       
        return [
            'pagination' => [
                'total'         => $precios_modelos->total(),
                'current_page'  => $precios_modelos->currentPage(),
                'per_page'      => $precios_modelos->perPage(),
                'last_page'     => $precios_modelos->lastPage(),
                'from'          => $precios_modelos->firstItem(),
                'to'            => $precios_modelos->lastItem(),
            ],
            'precios_modelos' => $precios_modelos
        ];
    }

    //funcion para insertar nuevo registro en la tabla 
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
        $precio_modelo = new Precio_modelo();
        $precio_modelo->precio_etapa_id = $request->precio_etapa_id;
        $precio_modelo->modelo_id = $request->modelo_id;
        $precio_modelo->precio_modelo = $request->precio_modelo;
        $precio_modelo->save();
    }

//   actualizacion de un registro en las tablas ( percio_modelo y precio_etapa )
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $precio_modelo = Precio_modelo::findOrFail($request->id);
        $precio_modelo->precio_etapa_id = $request->precio_etapa_id;
        $precio_modelo->modelo_id = $request->modelo_id;
        $precio_modelo->precio_modelo = $request->precio_modelo;
        $precio_modelo->save();

        $etapa_id = Precio_etapa::select('etapa_id')->where('id','=',$request->precio_etapa_id)->get();
        $lote_id = Lote::select('id')->where('modelo_id','=',$request->modelo_id)
        ->where('etapa_id','=',$etapa_id[0]->etapa_id)
        //->where('contrato','=',0)
        ->get();
        
        foreach($lote_id as $loteid){
            $credito_id = Credito::select('id','precio_base','precio_venta')->where('lote_id','=',$loteid->id)
            ->where('contrato','=',0)->get(); // selecciona los creditos que no tengan contrato
            foreach($credito_id as $creditoid){
                $newPrecioVenta = $creditoid->precio_venta - $creditoid->precio_base + $request->precio_modelo;  // se actualiza el precio venta 
                $credito = Credito::findOrFail($creditoid->id);
                $credito->precio_venta=$newPrecioVenta;  // se guarda nuevo precio venta
                $credito->precio_base = $request->precio_modelo; // se guarda precio base 
                $credito->save();
            }

            $precio = Lote::findOrFail($loteid->id);
            $precio->precio_base=$request->precio_modelo;
            $precio->save(); // se guarda el precio base para el lote seleccionado 
        }
        
    }

    // Elimina un registro de la tabla precio_modelo
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
        $precio_modelo = Precio_modelo::findOrFail($request->id);
        $precio_modelo->delete();
    }
}
