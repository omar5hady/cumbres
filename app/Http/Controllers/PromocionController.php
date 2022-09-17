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
    // Funcion de consulta de las promociones
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;
        $current = Carbon::today()->format('ymd');

        $promociones = Promocion::join('fraccionamientos','promociones.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','promociones.etapa_id','=','etapas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas',
                    'promociones.id','promociones.fraccionamiento_id', 'promociones.etapa_id','promociones.nombre',
                    'promociones.v_ini','promociones.v_fin','promociones.descuento','promociones.descripcion',
                    'promociones.desc_equipamiento',
                    DB::raw('(CASE WHEN promociones.v_fin >= ' . $current . ' THEN 1 ELSE 0 END) AS is_active'));

            if($buscar != '')
                $promociones = $promociones->where($criterio, '=', $buscar); // se filtra por el criterio seleccionado
            if($buscar2 != '')
                $promociones = $promociones->where('etapas.id', '=', $buscar2); // se filtra por las etapas

        $promociones = $promociones->orderBy('is_active', 'desc')
                                    ->orderBy('promociones.v_ini','desc')
                                    ->orderBy('fraccionamientos.nombre', 'asc')
                                    ->orderBy('etapas.num_etapa', 'asc')
                                    ->paginate(20);


        if(sizeOf($promociones)){ // verifica que almenos tenga un registro
            foreach($promociones as $index => $promo){
                $lotes_promocion = Lote_promocion::join('lotes','lotes_promocion.lote_id','=','lotes.id')
                ->join('promociones','lotes_promocion.promocion_id','=','promociones.id')
                ->select('lotes.num_lote as lote','promociones.nombre as promocion', 'lotes.manzana as manzana',
                        'lotes_promocion.id','lotes_promocion.lote_id','lotes_promocion.promocion_id')
                ->where('lotes_promocion.promocion_id', '=', $promo->id)
                ->orderBy('lotes.manzana', 'asc')
                ->orderBy('lotes.num_lote', 'asc')->get();

                $promo->lote = '';   // agrega un nuevo campo y los inicializa
                $promo->mostrar = 0;
                if(sizeof($lotes_promocion)){
                    foreach($lotes_promocion as $ind => $lote){  // al nuevo campo le añade informacion de lote y manzana
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
            'promociones' => $promociones, 'lotes_promocion'=>$lotes_promocion  // retorna los objetos en forma de arreglo
        ];
    }

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
        $promocion->desc_equipamiento = $request->desc_equipamiento;

        $promocion->save();
    }

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
        $promocion->desc_equipamiento = $request->desc_equipamiento;

        $promocion->save();
    }
    // elimina  un registro en la tabla de promocion
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $promocion = Promocion::findOrFail($request->id);
        $promocion->delete();
    }

    // selecciona un registro de la tabla filtrada por el id de lote y retorna la informacion
    public function selectPromocion(Request $request){
        $promociones = Promocion::join('lotes_promocion','promociones.id','=','lotes_promocion.promocion_id')
                            ->select('promociones.id','promociones.descripcion','promociones.nombre','promociones.v_ini' ,'promociones.desc_equipamiento')
                            ->where('lotes_promocion.lote_id','=',$request->lote)
                            ->get();

        return $promociones;
    }
}
