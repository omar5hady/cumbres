<?php

namespace App\Http\Controllers\controlPresupuestal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TerrenoCompra;

class TerrenosCompraController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;

        $data = TerrenoCompra::select('*');
            if($buscar != '')
                $data = $data->where('comprador', 'like', '%'.$buscar.'%')
                        ->orWhere('vendedor', 'like', '%'.$buscar.'%')
                        ->orWhere('nombre', 'like', '%'.$buscar.'%');

        $data = $data->orderBy('nombre','asc')->paginate(10);
        return $data;
    }

    public function store(Request $request){
        if(!$request->ajax())return redirect('/');

        $terreno = new TerrenoCompra();
        $terreno->nombre = $request->nombre;
        $terreno->direccion = $request->direccion;
        $terreno->valor_venta = $request->valor_venta;
        $terreno->valor_compra = $request->valor_compra;
        $terreno->valor_escritura = $request->valor_escritura;
        $terreno->fecha_firma_promesa = $request->fecha_firma_promesa;
        $terreno->fecha_firma_esc = $request->fecha_firma_esc;
        $terreno->tamanio = $request->tamanio;
        $terreno->comprador = $request->comprador;
        $terreno->vendedor = $request->vendedor;
        $terreno->save();
    }

    public function update(Request $request){
        if(!$request->ajax())return redirect('/');

        $terreno = TerrenoCompra::findOrFail($request->id);
        $terreno->nombre = $request->nombre;
        $terreno->direccion = $request->direccion;
        $terreno->valor_venta = $request->valor_venta;
        $terreno->valor_compra = $request->valor_compra;
        $terreno->valor_escritura = $request->valor_escritura;
        $terreno->fecha_firma_promesa = $request->fecha_firma_promesa;
        $terreno->fecha_firma_esc = $request->fecha_firma_esc;
        $terreno->tamanio = $request->tamanio;
        $terreno->comprador = $request->comprador;
        $terreno->vendedor = $request->vendedor;
        $terreno->save();
    }

    public function destroy(Request $request){
        if(!$request->ajax())return redirect('/');
        $terreno = TerrenoCompra::findOrFail($request->id);
        $terreno->delete();
    }
}
