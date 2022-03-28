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

//Controlador para el modelo Inventario
class InventariosController extends Controller
{
    //Función que retorna el los registros de la tabla inventarios
    public function getInventario(Request $request){
        $inventario = Inventario::where('producto','like','%'.$request->buscar.'%')//busqueda por nombre de producto
        ->orderBy('stock','desc')
        ->orderBy('producto','asc')->paginate(6);
        return $inventario;
    }

    //Función para registrar un nuevo producto al inventario
    public function storeInventario(Request $request){
        $inventario = new Inventario();
        $inventario->producto = $request->producto;
        $inventario->unidad = $request->unidad;
        $inventario->save();
    }

    //Función para actualizar la información de un producto al inventario
    public function updateInventario(Request $request){
        $inventario = Inventario::findOrFail($request->id);
        $inventario->producto = $request->producto;
        $inventario->unidad = $request->unidad;
        $inventario->save();
    }

    ////////////////// Proveedores
    //Función que retorna a los proveedores con paginación
    public function getProveedores(Request $request){
        $inventario = Inv_proveedor::where('nombre','like','%'.$request->buscar.'%')
        ->orderBy('nombre','asc')->paginate(10);
        return $inventario;
    }

    //Función que retorna a todos los proveedores
    public function returnProveedor(Request $request){
        $inventario = Inv_proveedor::orderBy('nombre','asc')->get();
        return $inventario;
    }

    //Función para registrar un nuevo proveedor.
    public function storeProveedor(Request $request){
        $inventario = new Inv_proveedor();
        $inventario->nombre = $request->nombre;
        $inventario->save();
    }
    //Función para actualizar el registro de un proveedor
    public function updateProveedor(Request $request){
        $inventario = Inv_proveedor::findOrFail($request->id);
        $inventario->nombre = $request->nombre;
        $inventario->save();
    }
    //Función que retorna las entradas de insumos de un producto (Compras).
    public function getCompras(Request $request){
        $compra = Inv_producto::join('inventarios','inv_productos.tipo_producto','=','inventarios.id')
                            ->join('inv_proveedores','inv_productos.proveedor','inv_proveedores.id')
                            ->select('inv_productos.*','inventarios.unidad','inv_proveedores.nombre')
                            ->where('inv_productos.tipo_producto','=',$request->id)
                            ->orderBy('inv_productos.id','desc')
                            ->paginate('10');
        return $compra;
    }
    //Función que retorna las salidas de insumos de un producto.
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
    //Función para registrar la compra de un producto
    public function storeCompra(Request $request){
        //Se registra la compra
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
        //Llamada a la función que se encarga de actualizar el stock en el inventario
        $this->actualizarStock($request->tipo_producto);
    }

    // Salidas
    //Función para registrar la salida de un producto
    public function storeSalida(Request $request){
        //Se registra la compra
        $salida = new Inv_salida();
        $salida->fecha = $request->fecha;
        $salida->concepto = $request->concepto;
        $salida->cantidad = $request->cantidad;
        $salida->tipo_producto = $request->tipo_producto;
        $salida->user_id = $request->user_id;
        $salida->tipo_mov = $request->tipo_mov;
        $salida->oficina = $request->oficina;
        $salida->save();
        //Llamada a la función que se encarga de actualizar el stock en el inventario
        $this->actualizarStock($request->tipo_producto);
    }

    //Función que se encarga de actualizar la cantidad actual de productos en el inventario
    private function actualizarStock($producto){
        //Se accede al registro del producto
        $inventario = Inventario::findOrFail($producto);
        //Se obtiene la sumatoria de compras del producto
        $entradas = Inv_producto::select(DB::raw("SUM(inv_productos.cantidad) as total"))->where('tipo_producto','=',$producto)->first();
        if($entradas->total ==  NULL) $entradas->total = 0;
        //Se obtiene la sumatoria de salidas del producto
        $salidas = Inv_salida::select(DB::raw("SUM(inv_salidas.cantidad) as total"))->where('tipo_producto','=',$producto)->first();
        if($salidas->total ==  NULL) $salidas->total = 0;
        //Se calcula la cantidad actual de productos
        $inventario->stock = $entradas->total - $salidas->total;
        $inventario->save();
    }

}
