<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;

class ProveedorController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $proveedores = Proveedor::select('id','proveedor', 'contacto', 'direccion', 'colonia',
                    'telefono', 'email', 'email2', 'poliza')->orderBy('proveedor','asc')
                    ->paginate(20);
        }
        else{
            $proveedores = Proveedor::select('id','proveedor', 'contacto', 'direccion', 'colonia',
                    'telefono', 'email', 'email2', 'poliza')
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('proveedor','asc')
                    ->paginate(20);
        }
        

        return [
            'pagination' => [
                'total'         => $proveedores->total(),
                'current_page'  => $proveedores->currentPage(),
                'per_page'      => $proveedores->perPage(),
                'last_page'     => $proveedores->lastPage(),
                'from'          => $proveedores->firstItem(),
                'to'            => $proveedores->lastItem(),
            ],
            'proveedores' => $proveedores
        ];

    }

    public function store(Request $request){

        $proveedor = new Proveedor();
        $proveedor->proveedor =$request->proveedor;
        $proveedor->contacto =$request->contacto;
        $proveedor->direccion =$request->direccion;
        $proveedor->colonia=$request->colonia;
        $proveedor->telefono =$request->telefono;
        $proveedor->email =$request->email;
        $proveedor->email2 =$request->email2;
        $proveedor->poliza=$request->poliza;
        $proveedor->save();
    }

    public function update(Request $request){

        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->proveedor =$request->proveedor;
        $proveedor->contacto =$request->contacto;
        $proveedor->direccion =$request->direccion;
        $proveedor->colonia=$request->colonia;
        $proveedor->telefono =$request->telefono;
        $proveedor->email =$request->email;
        $proveedor->email2 =$request->email2;
        $proveedor->poliza=$request->poliza;
        $proveedor->save();
    }
}
