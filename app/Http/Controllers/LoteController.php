<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;

class LoteController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
       // if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id')
                ->orderBy('lotes.id','lotes.fraccionamiento_id')->paginate(5);
        }
        else{
            $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id')
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('lotes.id','lotes.fraccionamiento_id')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes
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
        $lote = new Lote();
        $lote->fraccionamiento_id = $request->fraccionamiento_id;
        $lote->etapa_id = $request->etapa_id;
        $lote->manzana = $request->manzana;
        $lote->num_lote = $request->num_lote;
        $lote->sublote = $request->sublote;
        $lote->modelo_id = $request->modelo_id;
        $lote->empresa_id = $request->empresa_id;
        $lote->calle = $request->calle;
        $lote->numero = $request->numero;
        $lote->interior = $request->interior;
        $lote->terreno = $request->terreno;
        $lote->construccion = $request->construccion;
        $lote->casa_muestra = $request->casa_muestra;
        $lote->lote_comercial = $request->lote_comercial;

        $lote->save();
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
        $lote = Lote::findOrFail($request->id);
        $lote->fraccionamiento_id = $request->fraccionamiento_id;
        $lote->etapa_id = $request->etapa_id;
        $lote->manzana = $request->manzana;
        $lote->num_lote = $request->num_lote;
        $lote->sublote = $request->sublote;
        $lote->modelo_id = $request->modelo_id;
        $lote->empresa_id = 1;
        $lote->calle = $request->calle;
        $lote->numero = $request->numero;
        $lote->interior = $request->interior;
        $lote->terreno = $request->terreno;
        $lote->construccion = $request->construccion;
        $lote->casa_muestra = $request->casa_muestra;
        $lote->lote_comercial = $request->lote_comercial;

        $lote->save();
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
        $lote = Lote::findOrFail($request->id);
        $lote->delete();
    }


}
