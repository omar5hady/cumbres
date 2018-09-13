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
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapas_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial')
                ->orderBy('id','lotes.num_lote')->paginate(5);
        }
        else{
            $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapas_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial')
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('id','lotes.num_lote')->paginate(5);
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
        $lotes = new Lote();
        $lotes->fraccionamiento_id = $request->fraccionamiento_id;
        $lotes->etapa_id = $request->etapa_id;
        $lotes->manzana = $request->manzana;
        $lotes->num_lote = $request->num_lote;
        $lotes->sublote = $request->sublote;
        $lotes->modelo_id = $request->modelo_id;
        $lotes->empresa_id = $request->empresa_id;
        $lotes->calle = $request->calle;
        $lotes->numero = $request->numero;
        $lotes->interior = $request->interior;
        $lotes->terreno = $request->terreno;
        $lotes->construccion = $request->construccion;
        $lotes->casa_muestra = $request->casa_muestra;
        $lotes->lote_comercial = $request->lote_comercial;

        $lotes->save();
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
        $lotes->fraccionamiento_id = $request->fraccionamiento_id;
        $lotes->etapa_id = $request->etapa_id;
        $lotes->manzana = $request->manzana;
        $lotes->num_lote = $request->num_lote;
        $lotes->sublote = $request->sublote;
        $lotes->modelo_id = $request->modelo_id;
        $lotes->empresa_id = $request->empresa_id;
        $lotes->calle = $request->calle;
        $lotes->numero = $request->numero;
        $lotes->interior = $request->interior;
        $lotes->terreno = $request->terreno;
        $lotes->construccion = $request->construccion;
        $lotes->casa_muestra = $request->casa_muestra;
        $lotes->lote_comercial = $request->lote_comercial;

        $lotes->save();
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
        $lotes = Lote::findOrFail($request->id);
        $lotes->delete();
    }


}
