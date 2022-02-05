<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inv_producto;
use App\Inv_proveedor;
use App\Inv_salida;
use App\Inventario;
use Excel;
use Auth;
use DB;

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

    public function returnProveedor(Request $request){
        $inventario = Inv_proveedor::orderBy('nombre','asc')->get();
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

    public function getCompras(Request $request){
        $compra = Inv_producto::join('inventarios','inv_productos.tipo_producto','=','inventarios.id')
                            ->join('inv_proveedores','inv_productos.proveedor','inv_proveedores.id')
                            ->select('inv_productos.*','inventarios.unidad','inv_proveedores.nombre')
                            ->where('inv_productos.tipo_producto','=',$request->id)
                            ->orderBy('inv_productos.id','desc')
                            ->paginate('10');
        return $compra;
    }

    public function getSalidas(Request $request){
        $salidas = Inv_salida::join('inventarios','inv_salidas.tipo_producto','=','inventarios.id')
                        ->join('personal','inv_salidas.user_id','=','personal.id')
                        ->select('inv_salidas.*','personal.nombre','personal.apellidos','inventarios.unidad')
                        ->where('inv_salidas.tipo_producto','=',$request->id)
                        ->orderBy('inv_salidas.id','desc')
                        ->paginate('10');

        return $salidas;
    }

    // Entradas
    public function storeCompra(Request $request){
        $compra = new Inv_producto();
        $compra->fecha = $request->fecha;
        $compra->concepto = $request->concepto;
        $compra->proveedor = $request->proveedor;
        $compra->num_factura = $request->num_factura;
        $compra->cantidad = $request->cantidad;
        $compra->unidad = $request->unidad;
        $compra->p_unit = $request->p_unit;
        $compra->total = $request->total;
        $compra->stock = $request->cantidad;
        $compra->tipo_producto = $request->tipo_producto;
        $compra->user_id = Auth::user()->id;
        $compra->tipo_mov = $request->tipo_mov;
        $compra->save();

        $this->actualizarStock($request->tipo_producto);
    }

    // Salidas
    public function storeSalida(Request $request){
        $salida = new Inv_salida();
        $salida->fecha = $request->fecha;
        $salida->concepto = $request->concepto;
        $salida->cantidad = $request->cantidad;
        $salida->tipo_producto = $request->tipo_producto;
        $salida->user_id = $request->user_id;
        $salida->tipo_mov = $request->tipo_mov;
        $salida->oficina = $request->oficina;
        $salida->save();

        $this->actualizarStock($request->tipo_producto);
    }

    private function actualizarStock($producto){
        $inventario = Inventario::findOrFail($producto);

        $entradas = Inv_producto::select(DB::raw("SUM(inv_productos.cantidad) as total"))->where('tipo_producto','=',$producto)->first();
        if($entradas->total ==  NULL) $entradas->total = 0;
        $salidas = Inv_salida::select(DB::raw("SUM(inv_salidas.cantidad) as total"))->where('tipo_producto','=',$producto)->first();
        if($salidas->total ==  NULL) $salidas->total = 0;

        $inventario->stock = $entradas->total - $salidas->total;
        $inventario->save();
    }

}
