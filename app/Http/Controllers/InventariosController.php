<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inv_producto;
use App\Inv_proveedor;
use App\Inv_salida;
use App\Inventario;
use Excel;
use Auth;

class InventariosController extends Controller
{
    public function getInventario(Request $request){
        $inventario = Inventario::where('producto','like','%'.$request->buscar.'%')
        ->orderBy('stock','desc')
        ->orderBy('producto','asc')->paginate(6);
        return $inventario;
    }

    public function storeInventario(Request $request){
        $inventario = new Inventario();
        $inventario->producto = $request->producto;
        $inventario->unidad = $request->unidad;
        $inventario->save();
    }

    public function updateInventario(Request $request){
        $inventario = Inventario::findOrFail($request->id);
        $inventario->producto = $request->producto;
        $inventario->unidad = $request->unidad;
        $inventario->save();
    }

    ////////////////// Proveedores
    public function getProveedores(Request $request){
        $inventario = Inv_proveedor::where('nombre','like','%'.$request->buscar.'%')
        ->orderBy('nombre','asc')->paginate(10);
        return $inventario;
    }

    public function storeProveedor(Request $request){
        $inventario = new Inv_proveedor();
        $inventario->nombre = $request->nombre;
        $inventario->save();
    }

    public function updateProveedor(Request $request){
        $inventario = Inv_proveedor::findOrFail($request->id);
        $inventario->nombre = $request->nombre;
        $inventario->save();
    }

}
