<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fraccionamiento; //Importar el modelo
use App\Etapa;

class FraccionamientoController extends Controller
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
            $fraccionamientos = Fraccionamiento::orderBy('id','desc')->paginate(5);
        }
        else{
            $fraccionamientos = Fraccionamiento::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         => $fraccionamientos->total(),
                'current_page'  => $fraccionamientos->currentPage(),
                'per_page'      => $fraccionamientos->perPage(),
                'last_page'     => $fraccionamientos->lastPage(),
                'from'          => $fraccionamientos->firstItem(),
                'to'            => $fraccionamientos->lastItem(),
            ],
            'fraccionamientos' => $fraccionamientos
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
        $fraccionamiento = new Fraccionamiento();
        $fraccionamiento->nombre = $request->nombre;
        $fraccionamiento->tipo_proyecto = $request->tipo_proyecto;
        $fraccionamiento->calle = $request->calle;
        $fraccionamiento->colonia = $request->colonia;
        $fraccionamiento->estado = $request->estado;
        $fraccionamiento->ciudad = $request->ciudad;
        $fraccionamiento->save();

        
        $etapa = new Etapa();
        $etapa->fraccionamiento_id = $fraccionamiento->id;
        $etapa->num_etapa = "Sin Asignar";
        
        $etapa->personal_id = 1;
        $etapa->save();

       


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
        $fraccionamiento = Fraccionamiento::findOrFail($request->id);
        $fraccionamiento->nombre = $request->nombre;
        $fraccionamiento->tipo_proyecto = $request->tipo_proyecto;
        $fraccionamiento->calle = $request->calle;
        $fraccionamiento->colonia = $request->colonia;
        $fraccionamiento->estado = $request->estado;
        $fraccionamiento->ciudad = $request->ciudad;
        $fraccionamiento->save();
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
        $fraccionamiento = Fraccionamiento::findOrFail($request->id);

        $fraccionamiento->delete();
    }

    public function selectFraccionamiento(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $fraccionamientos = Fraccionamiento::select('nombre','id')->get();
        return['fraccionamientos' => $fraccionamientos];
    }

    public function selectFrac_Tipo(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $fraccionamiento = Fraccionamiento::select('nombre','id')
        ->where('tipo_proyecto', '=', $buscar)->get();
        return['fraccionamientos' => $fraccionamiento];
    }
}
