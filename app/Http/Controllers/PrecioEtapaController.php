<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Precio_etapa;

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
                ->orderBy('id','precios_etapas.fraccionamiento_id')->paginate(5);
        }
       else{
        $precios_etapas = Precio_etapa::join('fraccionamientos','precios_etapas.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','precios_etapas.etapa_id','=','etapas.id')
        ->select('fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as etapas', 
                'precios_etapas.precio_excedente','precios_etapas.id',
                'precios_etapas.etapa_id','precios_etapas.fraccionamiento_id' )
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('id','precios_etapas.fraccionamiento_id')->paginate(5);
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
        //if(!$request->ajax())return redirect('/');
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
    
        $precio_etapa->save();
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
}
