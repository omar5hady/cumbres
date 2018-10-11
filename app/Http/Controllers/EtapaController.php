<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Etapa; //Importar el modelo
use App\Precio_etapa; //Importar el modelo
use DB;

class EtapaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        //if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $etapas = Etapa::join('personal','etapas.personal_id','=','personal.id')
                ->join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                ->select('etapas.num_etapa','etapas.f_ini',
                    'etapas.f_fin','etapas.id','etapas.personal_id', 
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS name"),
                    'etapas.fraccionamiento_id','fraccionamientos.nombre as fraccionamiento')
                    ->orderBy('id','name')->paginate(5);
        }
        else{
            if($criterio == 'f_ini' || $criterio == 'f_fin')
            {
                $etapas = Etapa::join('personal','etapas.personal_id','=','personal.id')
                    ->join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('etapas.num_etapa','etapas.f_ini',
                        'etapas.f_fin','etapas.id','etapas.personal_id', 
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS name"),
                        'etapas.fraccionamiento_id','fraccionamientos.nombre as fraccionamiento')
                        ->whereBetween($criterio, [$buscar,$buscar2])->orderBy('id','name')->paginate(5);
            }
            else{
                $etapas = Etapa::join('personal','etapas.personal_id','=','personal.id')
                    ->join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('etapas.num_etapa','etapas.f_ini',
                        'etapas.f_fin','etapas.id','etapas.personal_id', 
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS name"),
                        'etapas.fraccionamiento_id','fraccionamientos.nombre as fraccionamiento')
                        ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','name')->paginate(5);
            }
            
        }

        return [
            'pagination' => [
                'total'         => $etapas->total(),
                'current_page'  => $etapas->currentPage(),
                'per_page'      => $etapas->perPage(),
                'last_page'     => $etapas->lastPage(),
                'from'          => $etapas->firstItem(),
                'to'            => $etapas->lastItem(),
            ],
            'etapas' => $etapas
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
        $etapa = new Etapa();
        $etapa->fraccionamiento_id = $request->fraccionamiento_id;
        $etapa->num_etapa = $request->num_etapa;
        $etapa->f_ini = $request->f_ini;
        $etapa->f_fin = $request->f_fin;
        $etapa->personal_id = $request->personal_id;
        $etapa->save();

        $precio_etapa = new Precio_etapa();
        $precio_etapa->fraccionamiento_id = $request->fraccionamiento_id;
        $precio_etapa->etapa_id = $etapa->id;
        $precio_etapa->precio_excedente = 0;
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
        $etapa = Etapa::findOrFail($request->id);
        $etapa->fraccionamiento_id = $request->fraccionamiento_id;
        $etapa->num_etapa = $request->num_etapa;
        $etapa->f_ini = $request->f_ini;
        $etapa->f_fin = $request->f_fin;
        $etapa->personal_id = $request->personal_id;
        $etapa->save();
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
        $etapa = Etapa::findOrFail($request->id);
        $etapa->delete();
    }

    public function contEtapa(Request $request)
    {
        $fraccionamiento_id = $request->fraccionamiento_id;
        $etapas = Etapa::where('fraccionamiento_id','=',$fraccionamiento_id)->get();
        $contador = $etapas->count();

        return $contador + 1;
    }

    public function selectEtapa_proyecto(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $etapas = Etapa::select('num_etapa','id')
        ->where('fraccionamiento_id', '=', $buscar )->get();
        return['etapas' => $etapas];
    }
}
