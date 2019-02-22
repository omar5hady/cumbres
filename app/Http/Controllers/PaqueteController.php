<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paquete;
use DB;
use Carbon\Carbon;

class PaqueteController extends Controller
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
        $current = Carbon::today()->format('ymd');
        $criterio = $request->criterio;
        
        if($buscar==''){
            $paquetes = Paquete::join('fraccionamientos','paquetes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','paquetes.etapa_id','=','etapas.id')
            ->select('etapas.num_etapa as etapa','fraccionamientos.nombre as fraccionamiento','paquetes.id',
            'paquetes.fraccionamiento_id','paquetes.etapa_id','paquetes.nombre','paquetes.v_ini','paquetes.v_fin',
            'paquetes.costo','paquetes.descripcion',
            DB::raw('(CASE WHEN paquetes.v_fin >= ' . $current . ' THEN 1 ELSE 0 END) AS is_active'))
                ->orderBy('id','paquetes.nombre')
                //->where('paquetes.v_fin', '>', $current)
                ->paginate(5);
        }
        else{
            $paquetes = Paquete::join('fraccionamientos','paquetes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','paquetes.etapa_id','=','etapas.id')
            ->select('etapas.num_etapa as etapa','fraccionamientos.nombre as fraccionamiento','paquetes.id',
            'paquetes.fraccionamiento_id','paquetes.etapa_id','paquetes.nombre','paquetes.v_ini',
            'paquetes.v_fin','paquetes.costo','paquetes.descripcion'
            ,DB::raw('(CASE WHEN paquetes.v_fin > ' . $current . ' THEN 1 ELSE 0 END) AS is_active'))
                ->orderBy('id','paquetes.nombre')
                ->where($criterio, 'like', '%'. $buscar . '%')->paginate(5);
        }


        return [
            'pagination' => [
                'total'         => $paquetes->total(),
                'current_page'  => $paquetes->currentPage(),
                'per_page'      => $paquetes->perPage(),
                'last_page'     => $paquetes->lastPage(),
                'from'          => $paquetes->firstItem(),
                'to'            => $paquetes->lastItem(),
            ],
            'paquetes' => $paquetes
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
        $paquetes = new Paquete();
        $paquetes->fraccionamiento_id = $request->fraccionamiento_id;
        $paquetes->etapa_id = $request->etapa_id;
        $paquetes->nombre = $request->nombre;
        $paquetes->v_ini = $request->v_ini;
        $paquetes->v_fin = $request->v_fin;
        $paquetes->costo = $request->costo;
        $paquetes->descripcion = $request->descripcion;
        $paquetes->save();
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
        $paquetes = Paquete::findOrFail($request->id);
        $paquetes->fraccionamiento_id = $request->fraccionamiento_id;
        $paquetes->etapa_id = $request->etapa_id;
        $paquetes->nombre = $request->nombre;
        $paquetes->v_ini = $request->v_ini;
        $paquetes->v_fin = $request->v_fin;
        $paquetes->costo = $request->costo;
        $paquetes->descripcion = $request->descripcion;
    
        $paquetes->save();
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
        $paquetes = Paquete::findOrFail($request->id);
        $paquetes->delete();
    }


    public function select_paquetes(Request $request){
        $buscar = $request->buscar;

        $paquetes = Paquete::join('etapas','paquetes.etapa_id','=','etapas.id')
                            ->select('paquetes.id','paquetes.nombre','paquetes.descripcion','paquetes.costo','paquetes.v_ini','paquetes.v_fin')
                            ->where('etapas.num_etapa','=',$buscar)
                            ->get();

        return['paquetes' => $paquetes];
    }

    public function select_datos_paquetes(Request $request){
        $buscar = $request->buscar;
        $datos_paquetes = Paquete::select('paquetes.descripcion','paquetes.costo','paquetes.v_ini','paquetes.v_fin')
                          ->where('paquetes.id','=',$buscar)
                          ->get();

        return['datos_paquetes' => $datos_paquetes];

    }


}
