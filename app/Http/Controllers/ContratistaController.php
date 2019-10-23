<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratista;
use Auth;

class ContratistaController extends Controller
{
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

    public function selectContratistaVue(Request $request){
        if(!$request->ajax())return redirect('/');
        $filtro = $request->filtro;

        $contratistas = Contratista::where('nombre','like','%'.$filtro.'%')
            ->orWhere('rfc','like','%'.$filtro.'%')
            ->select('nombre','rfc','id')
            ->orderBy('nombre','asc')->get();
        
        return ['contratistas'=>$contratistas];

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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $contratistas = new Contratista();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $contratistas = Contratista::findOrFail($request->id);
        $contratistas->delete();
    }

    public function selectContratista(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $contratista = Contratista::select('nombre','id')->get();
        return['contratista' => $contratista];
    }

}
