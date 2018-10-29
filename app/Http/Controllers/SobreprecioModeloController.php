<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sobreprecio_modelo;
use DB;

class SobreprecioModeloController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        //if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3= $request->buscar3;
        
        if($buscar == ''){
        $sobreprecio_modelo = Sobreprecio_modelo::join('lotes','sobreprecios_modelos.lote_id','=','lotes.id')
            ->join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
            ->join('sobreprecios','sobreprecios_etapas.sobreprecio_id','=','sobreprecios.id')
            ->select('lotes.num_lote as lotes',DB::raw("CONCAT(sobreprecios.nombre,' $',sobreprecios_etapas.sobreprecio) AS sobreprecioModelo"),'sobreprecios_modelos.id',
                    'sobreprecios_modelos.sobreprecio_etapa_id','sobreprecios_modelos.ajuste')
            ->orderBy('id','sobreprecios_modelos.lote_id')->paginate(7);
        }
        else{
            if($buscar2 == '' && $buscar3 == '') {
                $sobreprecio_modelo = Sobreprecio_modelo::join('lotes','sobreprecios_modelos.lote_id','=','lotes.id')
                ->join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                ->join('sobreprecios','sobreprecios_etapas.sobreprecio_id','=','sobreprecios.id')
                ->select('lotes.num_lote as lotes',DB::raw("CONCAT(sobreprecios.nombre,' $',sobreprecios_etapas.sobreprecio) AS sobreprecioModelo"),'sobreprecios_modelos.id',
                        'sobreprecios_modelos.sobreprecio_etapa_id','sobreprecios_modelos.ajuste')
                    ->where('sobreprecios_etapas.etapa_id','=', $buscar)
                    ->orderBy('id','sobreprecios_modelos.lote_id')->paginate(7);
            }
            else{
                if($buscar2 == ''){
                    $sobreprecio_modelo = Sobreprecio_modelo::join('lotes','sobreprecios_modelos.lote_id','=','lotes.id')
                    ->join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                    ->join('sobreprecios','sobreprecios_etapas.sobreprecio_id','=','sobreprecios.id')
                    ->select('lotes.num_lote as lotes',DB::raw("CONCAT(sobreprecios.nombre,' $',sobreprecios_etapas.sobreprecio) AS sobreprecioModelo"),'sobreprecios_modelos.id',
                            'sobreprecios_modelos.sobreprecio_etapa_id','sobreprecios_modelos.ajuste')
                        ->where('sobreprecios_etapas.etapa_id','=', $buscar)
                        ->where('lotes.manzana', 'like', '%'. $buscar3 . '%')
                        ->orderBy('id','sobreprecios_modelos.lote_id')->paginate(7);
                    }
                
                if($buscar3 == ''){
                    $sobreprecio_modelo = Sobreprecio_modelo::join('lotes','sobreprecios_modelos.lote_id','=','lotes.id')
                    ->join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                    ->join('sobreprecios','sobreprecios_etapas.sobreprecio_id','=','sobreprecios.id')
                    ->select('lotes.num_lote as lotes',DB::raw("CONCAT(sobreprecios.nombre,' $',sobreprecios_etapas.sobreprecio) AS sobreprecioModelo"),'sobreprecios_modelos.id',
                            'sobreprecios_modelos.sobreprecio_etapa_id','sobreprecios_modelos.ajuste')
                        ->where('sobreprecios_etapas.etapa_id','=', $buscar)
                        ->where('lotes.num_lote', 'like', '%'. $buscar2 . '%')
                        ->orderBy('id','sobreprecios_modelos.lote_id')->paginate(7);
                    }
                else{
                    $sobreprecio_modelo = Sobreprecio_modelo::join('lotes','sobreprecios_modelos.lote_id','=','lotes.id')
                    ->join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                    ->join('sobreprecios','sobreprecios_etapas.sobreprecio_id','=','sobreprecios.id')
                    ->select('lotes.num_lote as lotes',DB::raw("CONCAT(sobreprecios.nombre,' $',sobreprecios_etapas.sobreprecio) AS sobreprecioModelo"),'sobreprecios_modelos.id',
                            'sobreprecios_modelos.sobreprecio_etapa_id','sobreprecios_modelos.ajuste')
                        ->where('sobreprecios_etapas.etapa_id','=', $buscar)
                        ->where('lotes.manzana', 'like', '%'. $buscar3 . '%')
                        ->where('lotes.num_lote', 'like', '%'. $buscar2 . '%')
                        ->orderBy('id','sobreprecios_modelos.lote_id')->paginate(7);

                    }
            }
        }


        return [
            'pagination' => [
                'total'         => $sobreprecio_modelo->total(),
                'current_page'  => $sobreprecio_modelo->currentPage(),
                'per_page'      => $sobreprecio_modelo->perPage(),
                'last_page'     => $sobreprecio_modelo->lastPage(),
                'from'          => $sobreprecio_modelo->firstItem(),
                'to'            => $sobreprecio_modelo->lastItem(),
            ],
            'sobreprecio_modelo' => $sobreprecio_modelo
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
        $sobreprecio_modelo = new Sobreprecio_modelo();
        $sobreprecio_modelo->lote_id = $request->lote_id;
        $sobreprecio_modelo->sobreprecio_etapa_id = $request->sobreprecio_etapa_id;
        $sobreprecio_modelo->ajuste = $request->ajuste;
        $sobreprecio_modelo->save();
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
        $sobreprecio_modelo = Sobreprecio_modelo::findOrFail($request->id);
        $sobreprecio_modelo->lote_id = $request->lote_id;
        $sobreprecio_modelo->sobreprecio_etapa_id = $request->sobreprecio_etapa_id;
        $sobreprecio_modelo->ajuste = $request->ajuste;
        $sobreprecio_modelo->save();
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
        $sobreprecio_modelo = Sobreprecio_modelo::findOrFail($request->id);
        $sobreprecio_modelo->delete();
    }
}
