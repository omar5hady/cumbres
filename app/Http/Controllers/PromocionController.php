<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Promocion;
use Carbon\Carbon;
use DB;

class PromocionController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $current = Carbon::today()->format('ymd');
                
        if($buscar==''){
            $promociones = Promocion::join('fraccionamientos','promociones.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','promociones.etapa_id','=','etapas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas',
                    'promociones.id','promociones.fraccionamiento_id', 'promociones.etapa_id','promociones.nombre',
                    'promociones.v_ini','promociones.v_fin','promociones.descuento','promociones.descripcion',
                    DB::raw('(CASE WHEN promociones.v_fin >= ' . $current . ' THEN 1 ELSE 0 END) AS is_active'))
            ->orderBy('promociones.id', 'fraccionamientos.nombre')->paginate(8);
        }
        else{
            $promociones = Promocion::join('fraccionamientos','promociones.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','promociones.etapa_id','=','etapas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas',
                    'promociones.id','promociones.fraccionamiento_id', 'promociones.etapa_id','promociones.nombre',
                    'promociones.v_ini','promociones.v_fin','promociones.descuento','promociones.descripcion',
                    DB::raw('(CASE WHEN promociones.v_fin >= ' . $current . ' THEN 1 ELSE 0 END) AS is_active'))
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('promociones.id', 'fraccionamientos.nombre')->paginate(8);
        }
            
        return [
            'pagination' => [
                'total'         => $promociones->total(),
                'current_page'  => $promociones->currentPage(),
                'per_page'      => $promociones->perPage(),
                'last_page'     => $promociones->lastPage(),
                'from'          => $promociones->firstItem(),
                'to'            => $promociones->lastItem(),
            ],
            'promociones' => $promociones
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
        $promocion = new Promocion();
        $promocion->fraccionamiento_id = $request->fraccionamiento_id;
        $promocion->etapa_id = $request->etapa_id;
        $promocion->nombre = $request->nombre;
        $promocion->v_ini = $request->v_ini;
        $promocion->v_fin = $request->v_fin;
        $promocion->descuento = $request->descuento;
        $promocion->descripcion = $request->descripcion;

        $promocion->save();
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
        $promocion = Promocion::findOrFail($request->id);
        $promocion->fraccionamiento_id = $request->fraccionamiento_id;
        $promocion->etapa_id = $request->etapa_id;
        $promocion->nombre = $request->nombre;
        $promocion->v_ini = $request->v_ini;
        $promocion->v_fin = $request->v_fin;
        $promocion->descuento = $request->descuento;
        $promocion->descripcion = $request->descripcion;

        $promocion->save();
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
        $promocion = Promocion::findOrFail($request->id);
        $promocion->delete();
    }
}
