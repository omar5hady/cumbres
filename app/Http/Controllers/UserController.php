<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Personal;
use App\Vendedor;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $personas = User::join('personal','users.id','=','personal.id')
            ->join('roles','users.rol_id','=','roles.id')
            ->select('personal.id','personal.nombre','personal.rfc','personal.f_nacimiento',
            'personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.homoclave','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','users.usuario','users.password',
            'users.condicion','users.rol_id','roles.nombre as rol')
            ->orderBy('users.condicion', 'desc')
            ->orderBy('personal.id', 'desc')
            ->paginate(3);
        }
        else{
            $personas = User::join('personal','users.id','=','personal.id')
            ->join('roles','users.rol_id','=','roles.id')
            ->select('personal.id','personal.nombre','personal.rfc','personal.f_nacimiento',
            'personal.direccion','personal.telefono','personal.departamento_id',
            'personal.colonia','personal.ext','personal.homoclave','personal.cp',
            'personal.celular','personal.activo','personal.empresa_id','personal.apellidos',
            'personal.email','users.usuario','users.password',
            'users.condicion','users.rol_id','roles.nombre as rol')        
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('users.condicion', 'desc')
            ->orderBy('personal.id', 'desc')
           ->paginate(3);
        }
         
 
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }
 
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         
        try{
            DB::beginTransaction();
            $persona = new Personal();
            $persona->departamento_id = $request->departamento_id;
            $persona->nombre = $request->nombre;
            $persona->apellidos = $request->apellidos;
            $persona->f_nacimiento = $request->f_nacimiento;
            $persona->rfc = $request->rfc;
            $persona->direccion = $request->direccion;
            $persona->colonia = $request->colonia;
            $persona->cp = $request->cp;
            $persona->telefono = $request->telefono;
            $persona->ext = $request->ext;
            $persona->celular = $request->celular;
            $persona->email = $request->email;
            $persona->activo = $request->activo;
            $persona->empresa_id = $request->empresa_id;
            $persona->save();
 
            $user = new User();
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->rol_id = $request->rol_id;          
 
            $user->id = $persona->id;
 
            $user->save();
            
            if($user->rol_id == 2){
                $vendedor = new Vendedor();
                $vendedor->id = $persona->id;
                $vendedor->save();
            }

            DB::commit();

            
 
        } catch (Exception $e){
            DB::rollBack();
        }         
         
    }
 
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         
        try{
            DB::beginTransaction();
 
            //Buscar primero el proveedor a modificar
            $user = User::findOrFail($request->id);
 
            $Persona = Personal::findOrFail($request->id);
 
            $Persona->departamento_id = $request->departamento_id;
            $Persona->nombre = $request->nombre;
            $Persona->apellidos = $request->apellidos;
            $Persona->f_nacimiento = $request->f_nacimiento;
            $Persona->rfc = $request->rfc;
            $Persona->direccion = $request->direccion;
            $Persona->colonia = $request->colonia;
            $Persona->cp = $request->cp;
            $Persona->telefono = $request->telefono;
            $Persona->ext = $request->ext;
            $Persona->celular = $request->celular;
            $Persona->email = $request->email;
            $Persona->activo = $request->activo;
            $Persona->empresa_id = $request->empresa_id;
            $Persona->save();
            
            if($user->rol_id == 2){
                if($request->rol_id != 2){
                    $vendedor = Vendedor::findOrFail($request->id);
                    $vendedor->delete();
                }
            }

            if($user->rol_id != 2){
                if($request->rol_id == 2){
                    $vendedor = new Vendedor();
                    $vendedor->id = $Persona->id;
                    $vendedor->save();
                }
            }
             
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->rol_id = $request->rol_id;
            $user->save();

 
            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
 
    }

    public function asignar(Request $request){
        if (!$request->ajax()) return redirect('/');
        $user = new User();
        $user->id = $request->id_persona;
        $user->usuario = $request->usuario;
        $user->password = bcrypt( $request->password);
        $user->condicion = '1';
        $user->rol_id = $request->rol_id; 
        
        if($user->rol_id == 2){
            $vendedor = new Vendedor();
            $vendedor->id = $request->id_persona;
            $vendedor->save();
        }     

        $user->save();
    }
 
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }

    public function selectVendedores(Request $request){
        if (!$request->ajax()) return redirect('/');
        $personas = User::join('personal','users.id','=','personal.id')
            ->select('personal.id', 
            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS n_completo"))
            ->where('users.rol_id','=','2')
            ->get();

        return['vendedores' => $personas];
    }
}
