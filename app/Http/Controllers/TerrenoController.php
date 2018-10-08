<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TerrenoController extends Controller
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
            $terrenos = Terreno::join('fraccionamientos','terrenos.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','terrenos.etapa_id','=','etapas.id')
            ->join('empresas','terrenos.empresa _id','=','empresas.id')
            ->select('fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as etapas','terrenos.manzana_id',
                     'terrenos.num_lote','empresas.nombre as empresa','terrenos.calle','terrenos.numero','terrenos.interior',
                     'terrenos.terreno','terrenos.id')
                ->orderBy('id','terrenos.fraccionamiento_id')->paginate(5);
        }
        else{
            $terrenos = Terreno::join('fraccionamientos','terrenos.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','terrenos.etapa_id','=','etapas.id')
            ->join('empresas','terrenos.empresa _id','=','empresas.id')
            ->select('fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as etapas','terrenos.manzana_id',
                     'terrenos.num_lote','empresas.nombre as empresa','terrenos.calle','terrenos.numero','terrenos.interior',
                     'terrenos.terreno','terrenos.id')  
            ->where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('id','terrenos.fraccionamiento_id')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         => $terrenos->total(),
                'current_page'  => $terrenos->currentPage(),
                'per_page'      => $terrenos->perPage(),
                'last_page'     => $terrenos->lastPage(),
                'from'          => $terrenos->firstItem(),
                'to'            => $terrenos->lastItem(),
            ],
            'terrenos' => $terrenos
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
        $terreno = new Terreno();
        $terreno->fraccionamiento_id = $request->fraccionamiento_id;
        $terreno->etapa_id = $request->etapa_id;
        $terreno->manzana_id = $request->manzana_id;
        $terreno->num_lote = $request->num_lote;
        $terreno->empresa_id = $request->empresa_id;
        $terreno->calle = $request->calle;
        $terreno->numero = $request->numero;
        $terreno->interior = $request->interior;
        $terreno->terreno = $request->terreno;
        $terreno->save();
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
        $terreno = Terreno::findOrFail($request->id);
        $terreno->fraccionamiento_id = $request->fraccionamiento_id;
        $terreno->etapa_id = $request->etapa_id;
        $terreno->manzana_id = $request->manzana_id;
        $terreno->num_lote = $request->num_lote;
        $terreno->empresa_id = $request->empresa_id;
        $terreno->calle = $request->calle;
        $terreno->numero = $request->numero;
        $terreno->interior = $request->interior;
        $terreno->terreno = $request->terreno;
    
        $terreno->save();
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
        $terreno = Terreno::findOrFail($request->id);
        $terreno->delete();
    }


}
