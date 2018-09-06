<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modelo;
use DB;

class ModeloController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $modelos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
            ->select('modelos.nombre','modelos.tipo','modelos.fraccionamiento_id',
            'fraccionamientos.nombre as fraccionamiento','modelos.terreno','modelos.construccion','modelos.archivo','modelos.id')
                ->orderBy('id','modelos.nombre')->paginate(5);
        }
        else{
            
            
        }

        return [
            'pagination' => [
                'total'         => $modelos->total(),
                'current_page'  => $modelos->currentPage(),
                'per_page'      => $modelos->perPage(),
                'last_page'     => $modelos->lastPage(),
                'from'          => $modelos->firstItem(),
                'to'            => $modelos->lastItem(),
            ],
            'modelos' => $modelos
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
        $modelo = new Modelo();
        $modelo->nombre = $request->nombre;
        $modelo->tipo = $request->tipo;
        $modelo->fraccionamiento_id = $request->fraccionamiento_id;
        $modelo->terreno = $request->terreno;
        $modelo->construccion = $request->construccion;
        $modelo->archivo = $request->archivo;
        $modelo->save();
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
        $modelo = Modelo::findOrFail($request->id);
        $modelo->nombre = $request->nombre;
        $modelo->tipo = $request->tipo;
        $modelo->fraccionamiento_id = $request->fraccionamiento_id;
        $modelo->terreno = $request->terreno;
        $modelo->construccion = $request->construccion;
        $modelo->archivo = $request->archivo;

        $modelo->save();
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
        $modelo = Modelo::findOrFail($request->id);
        $modelo->delete();
    }
}
