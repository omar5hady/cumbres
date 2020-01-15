<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Promocion;
use App\Lote_promocion;
use Carbon\Carbon;
use DB;
use Auth;

class PromocionController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;
        $current = Carbon::today()->format('ymd');
                
        if($buscar==''){
            $promociones = Promocion::join('fraccionamientos','promociones.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','promociones.etapa_id','=','etapas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas',
                    'promociones.id','promociones.fraccionamiento_id', 'promociones.etapa_id','promociones.nombre',
                    'promociones.v_ini','promociones.v_fin','promociones.descuento','promociones.descripcion',
                    DB::raw('(CASE WHEN promociones.v_fin >= ' . $current . ' THEN 1 ELSE 0 END) AS is_active'))
            ->orderBy('fraccionamientos.nombre', 'asc')
            ->orderBy('etapas.num_etapa', 'asc')
            ->orderBy('is_active', 'desc')->paginate(20);
        }
        else{
            if($buscar2 == ''){
                $promociones = Promocion::join('fraccionamientos','promociones.fraccionamiento_id','=','fraccionamientos.id')
                ->join('etapas','promociones.etapa_id','=','etapas.id')
                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas',
                        'promociones.id','promociones.fraccionamiento_id', 'promociones.etapa_id','promociones.nombre',
                        'promociones.v_ini','promociones.v_fin','promociones.descuento','promociones.descripcion',
                        DB::raw('(CASE WHEN promociones.v_fin >= ' . $current . ' THEN 1 ELSE 0 END) AS is_active'))
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('fraccionamientos.nombre', 'asc')
                ->orderBy('etapas.num_etapa', 'asc')
                ->orderBy('is_active', 'desc')->paginate(20);
            }
            else{
                $promociones = Promocion::join('fraccionamientos','promociones.fraccionamiento_id','=','fraccionamientos.id')
                ->join('etapas','promociones.etapa_id','=','etapas.id')
                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas',
                        'promociones.id','promociones.fraccionamiento_id', 'promociones.etapa_id','promociones.nombre',
                        'promociones.v_ini','promociones.v_fin','promociones.descuento','promociones.descripcion',
                        DB::raw('(CASE WHEN promociones.v_fin >= ' . $current . ' THEN 1 ELSE 0 END) AS is_active'))
                ->where($criterio, '=', $buscar)
                ->where('etapas.id', '=', $buscar2)
                ->orderBy('fraccionamientos.nombre', 'asc')
                ->orderBy('etapas.num_etapa', 'asc')
                ->orderBy('is_active', 'desc')->paginate(20);

            }
            
        }

        if(sizeOf($promociones)){
            foreach($promociones as $index => $promo){
                $lotes_promocion = Lote_promocion::join('lotes','lotes_promocion.lote_id','=','lotes.id')
                ->join('promociones','lotes_promocion.promocion_id','=','promociones.id')
                ->select('lotes.num_lote as lote','promociones.nombre as promocion', 'lotes.manzana as manzana',
                        'lotes_promocion.id','lotes_promocion.lote_id','lotes_promocion.promocion_id')
                ->where('lotes_promocion.promocion_id', '=', $promo->id)
                ->orderBy('lotes.manzana', 'asc')
                ->orderBy('lotes.num_lote', 'asc')->get();

                $promo->lote = '';
                $promo->mostrar = 0;
                if(sizeof($lotes_promocion)){
                    foreach($lotes_promocion as $ind => $lote){
                        $promo->lote = $promo->lote. ' Lote ' .$lote->lote.' (Manzana '.$lote->manzana.'),';
                    }
                }
            }
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
            'promociones' => $promociones, 'lotes_promocion'=>$lotes_promocion
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $promocion = Promocion::findOrFail($request->id);
        $promocion->delete();
    }
}
