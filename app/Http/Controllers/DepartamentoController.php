<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departamento; //Importar el modelo
use Auth;

class DepartamentoController extends Controller
{
    //Funcion para listar los registros de la tabla departamentos
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar=='')
            $departamentos = Departamento::orderBy('id_departamento','desc')->paginate(8);
        else
            $departamentos = Departamento::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id_departamento','desc')->paginate(8);

        return [
            'pagination' => [
                'total'         => $departamentos->total(),
                'current_page'  => $departamentos->currentPage(),
                'per_page'      => $departamentos->perPage(),
                'last_page'     => $departamentos->lastPage(),
                'from'          => $departamentos->firstItem(),
                'to'            => $departamentos->lastItem(),
            ],
            'departamentos' => $departamentos
        ];
    }

     //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $departamento = new Departamento();
        $departamento->departamento = $request->departamento;
        $departamento->user_alta = $request->user_alta;
        $departamento->save();
    }

    //funcion para actualizar los datos
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $departamento = Departamento::findOrFail($request->id_departamento);
        $departamento->departamento = $request->departamento;
        $departamento->user_alta = $request->user_alta;
        $departamento->save();
    }

    // Función para eliminar el registro.
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $departamento = Departamento::findOrFail($request->id_departamento);
        $departamento->delete();
    }

    // Función que retorna los departamentos en la empresa.
    public function selectDepartamento(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $departamentos = Departamento::select('departamento','id_departamento')->get();
        return['departamentos' => $departamentos];

    }
}
