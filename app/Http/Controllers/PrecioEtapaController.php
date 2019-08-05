<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Precio_etapa;
use App\Precio_modelo;
use App\Lote;
use App\Modelo;

class PrecioEtapaController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $precios_etapas = Precio_etapa::join('fraccionamientos','precios_etapas.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','precios_etapas.etapa_id','=','etapas.id')
            ->select('fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as etapas', 
                    'precios_etapas.precio_excedente','precios_etapas.id',
                    'precios_etapas.etapa_id','precios_etapas.fraccionamiento_id' )
                ->orderBy('id','precios_etapas.fraccionamiento_id')->paginate(8);
        }
       else{
        $precios_etapas = Precio_etapa::join('fraccionamientos','precios_etapas.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','precios_etapas.etapa_id','=','etapas.id')
        ->select('fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as etapas', 
                'precios_etapas.precio_excedente','precios_etapas.id',
                'precios_etapas.etapa_id','precios_etapas.fraccionamiento_id' )
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('id','precios_etapas.fraccionamiento_id')->paginate(8);
        }

        return [
            'pagination' => [
                'total'         => $precios_etapas->total(),
                'current_page'  => $precios_etapas->currentPage(),
                'per_page'      => $precios_etapas->perPage(),
                'last_page'     => $precios_etapas->lastPage(),
                'from'          => $precios_etapas->firstItem(),
                'to'            => $precios_etapas->lastItem(),
            ],
            'precios_etapas' => $precios_etapas
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
        $precio_etapa = new Precio_etapa();
        $precio_etapa->fraccionamiento_id = $request->fraccionamiento_id;
        $precio_etapa->etapa_id = $request->etapa_id;
        $precio_etapa->precio_excedente = $request->precio_excedente;
        $precio_etapa->save();
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
        $precio_etapa = Precio_etapa::findOrFail($request->id);
        $precio_etapa->fraccionamiento_id = $request->fraccionamiento_id;
        $precio_etapa->etapa_id = $request->etapa_id;
        $precio_etapa->precio_excedente = $request->precio_excedente;
        $precio_etapa->save();

        $lotes = Lote::select('modelo_id','id','terreno')
            ->where('contrato','=','0')
            ->where('habilitado','=','1')
            ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
            ->where('etapa_id','=',$request->etapa_id)
            ->get();
        
        //if($lotes)
            foreach($lotes as $lote){
                $modelo = Modelo::select('terreno')->where('id','=',$lote->modelo_id)->get();
                $loteExc = Lote::findOrFail($lote->id);
                $terrenoExcedente = ($loteExc->terreno - $modelo[0]->terreno);
                if($terrenoExcedente > 0)
                    $loteExc->excedente_terreno = $terrenoExcedente * $request->precio_excedente;
                $loteExc->save();
            }
        
        
    
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
        $precio_etapa = Precio_etapa::findOrFail($request->id);
        $precio_etapa->delete();
    }

    /*public function storePrecioModelo(Request $request){
        if(!$request->ajax())return redirect('/');
        $precio_etapa = Precio_etapa::findOrfail($request->id);
        $precio_modelo = new Precio_modelo();
                
         $precio_modelo->precio_etapa_id = $precio_etapa->id;
         $precio_modelo->modelo_id = $request->modelo_id;
         $precio_modelo->precio_modelo = $request->arrayPreciosModelos;
         $precio_modelo->save();

    }*/

    public function selectPrecioEtapa(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $precio_etapa = Precio_etapa::select('precio_excedente','id')
        ->where('fraccionamiento_id', '=', $buscar )
        ->where('etapa_id', '=', $buscar2 )->get();

        return ['precio_etapa' => $precio_etapa];
    }

}
