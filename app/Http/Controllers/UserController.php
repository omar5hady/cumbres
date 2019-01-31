<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Personal;
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
            ->select('personal.id','personal.nombre','personal.rfc',
            'personal.direccion','personal.telefono',
            'personal.email','users.usuario','users.password',
            'users.condicion','users.rol_id','roles.nombre as rol')
            ->orderBy('personal.id', 'desc')->paginate(3);
        }
        else{
            $personas = User::join('personal','users.id','=','personal.id')
            ->join('roles','users.rol_id','=','roles.id')
            ->select('personal.id','personal.nombre','personal.rfc',
            'personal.direccion','personal.telefono',
            'personal.email','users.usuario','users.password',
            'users.condicion','users.rol_id','roles.nombre as rol')            
            ->where('personal.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personal.id', 'desc')->paginate(3);
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
 
            $persona = Personal::findOrFail($user->id);
 
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
}
