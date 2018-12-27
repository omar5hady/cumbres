<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sobreprecio_etapa;
use DB;


class SobreprecioEtapaController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        
        $sobreprecio_etapa = Sobreprecio_etapa::join('etapas','sobreprecios_etapas.etapa_id','=','etapas.id')
        ->join('sobreprecios','sobreprecios_etapas.sobreprecio_id','=','sobreprecios.id')
        ->select('etapas.num_etapa as etapas','sobreprecios.nombre as sobreprecionom', 
                'sobreprecios_etapas.id','sobreprecios_etapas.etapa_id',
                'sobreprecios_etapas.sobreprecio_id','sobreprecios_etapas.sobreprecio' )
        ->where('sobreprecios_etapas.etapa_id','=', $buscar)
        ->orderBy('id','sobreprecios_etapas.etapa_id')->paginate(7);

        return [
            'pagination' => [
                'total'         => $sobreprecio_etapa->total(),
                'current_page'  => $sobreprecio_etapa->currentPage(),
                'per_page'      => $sobreprecio_etapa->perPage(),
                'last_page'     => $sobreprecio_etapa->lastPage(),
                'from'          => $sobreprecio_etapa->firstItem(),
                'to'            => $sobreprecio_etapa->lastItem(),
            ],
            'sobreprecio_etapa' => $sobreprecio_etapa
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
        $sobreprecio_etapa = new Sobreprecio_etapa();
        $sobreprecio_etapa->etapa_id = $request->etapa_id;
        $sobreprecio_etapa->sobreprecio_id = $request->sobreprecio_id;
        $sobreprecio_etapa->sobreprecio = $request->sobreprecio;
        $sobreprecio_etapa->save();
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
        $sobreprecio_etapa = Sobreprecio_etapa::findOrFail($request->id);
        $sobreprecio_etapa->etapa_id = $request->etapa_id;
        $sobreprecio_etapa->sobreprecio_id = $request->sobreprecio_id;
        $sobreprecio_etapa->sobreprecio = $request->sobreprecio;
        $sobreprecio_etapa->save();
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
        $sobreprecio_etapa = Sobreprecio_etapa::findOrFail($request->id);
        $sobreprecio_etapa->delete();
    }


    public function select_sobreprecios_etapa(Request $request){
        // if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $sobreprecio_etapaM = 
            Sobreprecio_etapa::join('sobreprecios','sobreprecios_etapas.sobreprecio_id','=','sobreprecios.id')
                    ->select
                    (DB::raw("CONCAT(sobreprecios.nombre,' $',sobreprecios_etapas.sobreprecio) AS sobreprecioEtapa"),
                    'sobreprecios_etapas.id')
                    ->where('sobreprecios_etapas.etapa_id','=', $buscar)
                    ->get();
        return ['sobreprecio_etapaM' => $sobreprecio_etapaM];
    }

}
