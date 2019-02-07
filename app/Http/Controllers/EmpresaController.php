<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa; //Importar el modelo

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $empresas = Empresa::orderBy('id','nombre')->paginate(5);
        }
        else{
            $empresas = Empresa::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','nombre')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         => $empresas->total(),
                'current_page'  => $empresas->currentPage(),
                'per_page'      => $empresas->perPage(),
                'last_page'     => $empresas->lastPage(),
                'from'          => $empresas->firstItem(),
                'to'            => $empresas->lastItem(),
            ],
            'empresas' => $empresas
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $empresa = new Empresa();
        $empresa->nombre = $request->nombre;
        $empresa->direccion = $request->direccion;
        $empresa->cp = $request->cp;
        $empresa->colonia = $request->colonia;
        $empresa->estado = $request->estado;
        $empresa->ciudad = $request->ciudad;
        $empresa->telefono = $request->telefono;
        $empresa->ext = $request->ext;
        $empresa->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //funcion para actualizar los datos
    public function update(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $empresa = Empresa::findOrFail($request->id);
        $empresa->nombre = $request->nombre;
        $empresa->direccion = $request->direccion;
        $empresa->cp = $request->cp;
        $empresa->colonia = $request->colonia;
        $empresa->estado = $request->estado;
        $empresa->ciudad = $request->ciudad;
        $empresa->telefono = $request->telefono;
        $empresa->ext = $request->ext;
        $empresa->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $empresa = Empresa::findOrFail($request->id);
        $empresa->delete();
    }

    public function selectEmpresa(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $filtro = $request->filtro;
        $empresas = Empresa::select('nombre','id')
                             ->where('nombre','like','%'.$filtro.'%')
                             ->orderBy('nombre','asc')->get();
        return['empresas' => $empresas];
    }
}
