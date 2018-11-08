<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote_promocion;

class LotePromocionController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
       // if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
                
        if($buscar==''){
            $lotes_promocion = Lote_promocion::join('lotes','lotes_promocion.lote_id','=','lotes.id')
            ->join('promociones','lotes_promocion.promocion_id','=','promociones.id')
            ->select('lotes.num_lote as lote','promociones.nombre as promocion',
                    'lotes_promocion.id','lotes_promocion.lote_id','lotes_promocion.promocion_id')
            ->orderBy('lotes_promocion.id', 'promociones.nombre')->paginate(5);
        }
        else{
            $lotes_promocion = Lote_promocion::join('lotes','lotes_promocion.lote_id','=','lotes.id')
            ->join('promociones','lotes_promocion.promocion_id','=','promociones.id')
            ->select('lotes.num_lote as lote','promociones.nombre as promocion',
                    'lotes_promocion.id','lotes_promocion.lote_id','lotes_promocion.promocion_id')
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('lotes_promocion.id', 'promociones.nombre')->paginate(5);
        }
            
        return [
            'pagination' => [
                'total'         => $lotes_promocion->total(),
                'current_page'  => $lotes_promocion->currentPage(),
                'per_page'      => $lotes_promocion->perPage(),
                'last_page'     => $lotes_promocion->lastPage(),
                'from'          => $lotes_promocion->firstItem(),
                'to'            => $lotes_promocion->lastItem(),
            ],
            'lotes_promocion' => $lotes_promocion
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
        $lote_promocion = new Lote_promocion();
        $lote_promocion->lote_id = $request->lote_id;
        $lote_promocion->promocion_id = $request->promocion_id;

        $lote_promocion->save();
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
        $lote_promocion = Lote_promocion::findOrFail($request->id);
        $lote_promocion->lote_id = $request->lote_id;
        $lote_promocion->promocion_id = $request->promocion_id;

        $lote_promocion->save();
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
        $lote_promocion = Lote_promocion::findOrFail($request->id);
        $lote_promocion->delete();
    }
}
