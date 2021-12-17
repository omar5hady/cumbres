<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Contratista;
use App\Personal;
use App\User;
use Auth;

// Controlador para contratistas
class ContratistaController extends Controller
{
    // Función que devuelve el listado de contratistas.
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $contratistas = Contratista::orderBy('nombre','asc')->paginate(8);
        }
        else{
            $contratistas = Contratista::where($criterio, 'like', '%'. $buscar . '%')->orderBy('nombre','asc')->paginate(8);
        }

        return [
            'pagination' => [
                'total'         => $contratistas->total(),
                'current_page'  => $contratistas->currentPage(),
                'per_page'      => $contratistas->perPage(),
                'last_page'     => $contratistas->lastPage(),
                'from'          => $contratistas->firstItem(),
                'to'            => $contratistas->lastItem(),
            ],
            'contratistas' => $contratistas
        ];
    }

    // Función para busqueda de contratistas con Vue
    public function selectContratistaVue(Request $request){
        if(!$request->ajax())return redirect('/');
        $filtro = $request->filtro;

        $contratistas = Contratista::where('nombre','like','%'.$filtro.'%')
            ->orWhere('rfc','like','%'.$filtro.'%')
            ->select('nombre','rfc','id')
            ->orderBy('nombre','asc')->get();
        
        return ['contratistas'=>$contratistas];

    }

    // Función para almacenar un nuevo contratista.
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            // Primero se crea el registro en la tabla de Personal
            $persona = new Personal();
            $persona->departamento_id = 11;
            $persona->nombre = $request->nombre;
            $persona->apellidos = '.';
            $persona->f_nacimiento = '2019-01-01';
            $persona->rfc = $request->rfc;
            $persona->direccion = $request->direccion;
            $persona->colonia = $request->colonia;
            $persona->telefono = $request->telefono;
            $persona->celular = $request->telefono;
            $persona->email = 'correo@correo.com';
            $persona->activo = 1;
            $persona->empresa_id = 1;
            $persona->save();

            // Se crea el registro en la tabla de contratista.
            $contratistas = new Contratista();
            $contratistas->id = $persona->id;
            $contratistas->nombre = $request->nombre;
            $contratistas->tipo = $request->tipo;
            $contratistas->rfc = $request->rfc;
            $contratistas->direccion = $request->direccion;
            $contratistas->colonia = $request->colonia;
            $contratistas->cp = $request->cp;
            $contratistas->estado = $request->estado;
            $contratistas->ciudad = $request->ciudad;
            $contratistas->representante = $request->representante;
            $contratistas->IMSS = $request->IMSS;
            $contratistas->telefono = $request->telefono;
            $contratistas->save();

            // Se crea el usuario para acceso al sistema.
            $user = new User();
            $user->id = $persona->id;
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->rol_id = 13; 
            $user->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    //funcion para actualizar los datos del contratista
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $contratistas = Contratista::findOrFail($request->id);
        $contratistas->nombre = $request->nombre;
        $contratistas->tipo = $request->tipo;
        $contratistas->rfc = $request->rfc;
        $contratistas->direccion = $request->direccion;
        $contratistas->colonia = $request->colonia;
        $contratistas->cp = $request->cp;
        $contratistas->estado = $request->estado;
        $contratistas->ciudad = $request->ciudad;
        $contratistas->representante = $request->representante;
        $contratistas->IMSS = $request->IMSS;
        $contratistas->telefono = $request->telefono;
        $contratistas->save();
    }

    // Función para eliminar el contratista
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $contratistas = Contratista::findOrFail($request->id);
        $contratistas->delete();
    }

    // Función para retornar los contratistas para selector.
    public function selectContratista(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $contratista = Contratista::select('nombre','id')->get();
        return['contratista' => $contratista];
    }

}
