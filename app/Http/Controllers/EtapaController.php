<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Etapa; //Importar el modelo
use App\Precio_etapa; //Importar el modelo
use App\Sobreprecio_etapa; //Importar el modelo
use DB;
use File;
use App\Contrato;

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
                    'etapas.fraccionamiento_id','fraccionamientos.nombre as fraccionamiento','etapas.archivo_reglamento',
                    'etapas.plantilla_carta_servicios','etapas.costo_mantenimiento')
                    ->where('etapas.num_etapa','!=','Sin Asignar')
                    ->orderBy('fraccionamientos.nombre','asc')
                    ->orderBy('etapas.num_etapa','asc')->paginate(8);
        }
        else{
            if($criterio == 'f_ini' || $criterio == 'f_fin')
            {
                $etapas = Etapa::join('personal','etapas.personal_id','=','personal.id')
                    ->join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('etapas.num_etapa','etapas.f_ini',
                        'etapas.f_fin','etapas.id','etapas.personal_id', 
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS name"),
                        'etapas.fraccionamiento_id','fraccionamientos.nombre as fraccionamiento','etapas.archivo_reglamento',
                        'etapas.plantilla_carta_servicios','etapas.costo_mantenimiento')
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->where('etapas.num_etapa','!=','Sin Asignar')
                        ->orderBy('fraccionamientos.nombre','asc')
                        ->orderBy('etapas.num_etapa','asc')->paginate(8);
            }
            else{
                $etapas = Etapa::join('personal','etapas.personal_id','=','personal.id')
                    ->join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('etapas.num_etapa','etapas.f_ini',
                        'etapas.f_fin','etapas.id','etapas.personal_id', 
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS name"),
                        'etapas.fraccionamiento_id','fraccionamientos.nombre as fraccionamiento','etapas.archivo_reglamento',
                        'etapas.plantilla_carta_servicios','etapas.costo_mantenimiento')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('etapas.num_etapa','!=','Sin Asignar')
                        ->orderBy('fraccionamientos.nombre','asc')
                        ->orderBy('etapas.num_etapa','asc')->paginate(8);
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

        for($i=1;$i<=6;$i++){
            $sobreprecio_etapa= new Sobreprecio_etapa();
            $sobreprecio_etapa->etapa_id= $etapa->id;
            $sobreprecio_etapa->sobreprecio_id = $i;
            $sobreprecio_etapa->sobreprecio = 0;
            $sobreprecio_etapa->save();
        }
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
        ->where('fraccionamiento_id', '=', $buscar )
        //>where('num_etapa', '!=', 'Sin Asignar' )
        ->get();
        return['etapas' => $etapas];
    }
    public function uploadReglamento (Request $request, $id){
        $ultimoReglamento = Etapa::select('archivo_reglamento','id')
                                 ->where('id','=',$id)
                                 ->get();

        if($ultimoReglamento->isEmpty()==1){
            $fileName = uniqid().'.'.$request->archivo_reglamento->getClientOriginalExtension();
            $moved =  $request->archivo_reglamento->move(public_path('/files/etapas/reglamentos/'), $fileName);
    
            if($moved){
                if(!$request->ajax())return redirect('/');
                $reglamentoEtapa = Etapa::findOrFail($request->id);
                $reglamentoEtapa->archivo_reglamento = $fileName;
                $reglamentoEtapa->id = $id;
                $reglamentoEtapa->save(); //Insert
        
                }
            return back();
            }else{
                $pathAnterior = public_path().'/files/etapas/reglamentos/'.$ultimoReglamento[0]->archivo_reglamento;
                File::delete($pathAnterior);
                $fileName = uniqid().'.'.$request->archivo_reglamento->getClientOriginalExtension();
                $moved =  $request->archivo_reglamento->move(public_path('/files/etapas/reglamentos/'), $fileName);
        
                if($moved){
                    if(!$request->ajax())return redirect('/');
                    $reglamentoEtapa = Etapa::findOrFail($request->id);
                    $reglamentoEtapa->archivo_reglamento = $fileName;
                    $reglamentoEtapa->id = $id;
                    $reglamentoEtapa->save(); //Insert
            
                    }
                return back();
            }
    }

    public function uploadPlantillaCartaServicios (Request $request, $id){
        $ultimaPlantilla = Etapa::select('plantilla_carta_servicios','id')
                                 ->where('id','=',$id)
                                 ->get();

        if($ultimaPlantilla->isEmpty()==1){
            $fileName = uniqid().'.'.$request->plantilla_carta_servicios->getClientOriginalExtension();
            $moved =  $request->plantilla_carta_servicios->move(public_path('/files/etapas/plantillasCartaServicios/'), $fileName);
    
            if($moved){
                if(!$request->ajax())return redirect('/');
                $plantillaCartaServicios = Etapa::findOrFail($request->id);
                $plantillaCartaServicios->plantilla_carta_servicios = $fileName;
                $plantillaCartaServicios->id = $id;
                $plantillaCartaServicios->save(); //Insert
        
                }
            return back();
            }else{
                $pathAnterior = public_path().'/files/etapas/plantillasCartaServicios/'.$ultimaPlantilla[0]->plantilla_carta_servicios;
                File::delete($pathAnterior);

                $fileName = uniqid().'.'.$request->plantilla_carta_servicios->getClientOriginalExtension();
                $moved =  $request->plantilla_carta_servicios->move(public_path('/files/etapas/plantillasCartaServicios/'), $fileName);
    
            if($moved){
                if(!$request->ajax())return redirect('/');
                $plantillaCartaServicios = Etapa::findOrFail($request->id);
                $plantillaCartaServicios->plantilla_carta_servicios = $fileName;
                $plantillaCartaServicios->id = $id;
                $plantillaCartaServicios->save(); //Insert
        
                }
            return back();
        }
    }

    public function downloadReglamento ($fileName){
        $pathtoFile = public_path().'/files/etapas/reglamentos/'.$fileName;
        return response()->download($pathtoFile);
    }

    public function downloadPlantillaCartaServicios ($fileName){
        $pathtoFile = public_path().'/files/etapas/plantillasCartaServicios/'.$fileName;
        return response()->download($pathtoFile);
    }

    public function descargarReglamentoContrato (Request $request, $id)
    {
         $reglamento = Contrato::join('creditos','contratos.id','=','creditos.id')
        ->join('lotes','creditos.lote_id','=','lotes.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->select('etapas.archivo_reglamento')
        ->where('contratos.id','=',$id)->get();

        $pathtoFile = public_path().'/files/etapas/reglamentos/'.$reglamento[0]->archivo_reglamento;
        return response()->download($pathtoFile);
    }

    
    public function registrarCostoMantenimiento(Request $request, $id){
        $costoAnterior = Etapa::select('costo_mantenimiento')
                                             ->where('id','=',$id)
                                             ->get();
        if($costoAnterior->isEmpty()==1){
            $costoMantenimiento = new Etapa();
            $costoMantenimiento->costo_mantenimiento = $request->costo_mantenimiento;
            $costoMantenimiento->save();
        }else{
            $costoMantenimiento = Etapa::findOrFail($request->id);
            $costoMantenimiento->costo_mantenimiento = $request->costo_mantenimiento;
            $costoMantenimiento->save();
        }
        
    }
}
