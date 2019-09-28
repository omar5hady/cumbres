<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;

use App\Personal;
use App\User;
use Illuminate\Support\Facades\DB;

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

        try{
            DB::beginTransaction();
        
            $persona = new Personal();
            $persona->departamento_id = 9;
            $persona->nombre = $request->proveedor;
            $persona->apellidos = '.';
            $persona->f_nacimiento = '2019-01-01';
            $persona->rfc = $request->rfc;
            $persona->direccion = $request->direccion;
            $persona->colonia = $request->colonia;
            $persona->telefono = $request->telefono;
            $persona->celular = $request->telefono;
            $persona->email = $request->email;
            $persona->activo = 1;
            $persona->empresa_id = 1;
            $persona->save();

            $proveedor = new Proveedor();
            $proveedor->id = $persona->id;
            $proveedor->proveedor =$request->proveedor;
            $proveedor->contacto =$request->contacto;
            $proveedor->direccion =$request->direccion;
            $proveedor->colonia=$request->colonia;
            $proveedor->telefono =$request->telefono;
            $proveedor->email =$request->email;
            $proveedor->email2 =$request->email2;
            $proveedor->poliza=$request->poliza;
            $proveedor->save();
 
            $user = new User();
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->rol_id = 10;          
 
            $user->id = $persona->id;
            $user->save();
            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }         
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

    public function selectProveedor(Request $request){
        $proveedor = Proveedor::select('id','proveedor')->get();

        return ['proveedor' => $proveedor];
    }
}
