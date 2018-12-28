<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PartidaController;
use App\Lote;
use App\Modelo;
use App\Etapa;
use App\Licencia;
use Session;
use Excel;
use File;
use DB;
use Carbon\Carbon;

class LoteController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) //Index para modulo asignar modelo
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                      'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                      'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.etapa_servicios')
                      ->orderBy('fraccionamientos.nombre','DESC')
                      ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
        }
        else{
            if($buscar2=='' && $buscar3=='')
            {
                $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('empresas','lotes.empresa_id','=','empresas.id')
                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                        'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.etapa_servicios')
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('fraccionamientos.nombre','DESC')
                    ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
            }
            else{
                if($buscar3=='')
                {
                    $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('empresas','lotes.empresa_id','=','empresas.id')
                    ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                            'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                            'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                            'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                            'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.etapa_servicios')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('lotes.etapa_id', 'like', '%'. $buscar2 . '%')
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
                }
                else{
                    $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('empresas','lotes.empresa_id','=','empresas.id')
                    ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                            'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                            'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                            'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                            'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.etapa_servicios')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('lotes.etapa_id', 'like', '%'. $buscar2 . '%')
                        ->where('lotes.manzana', 'like', '%'. $buscar3 . '%')
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
                }
            }
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

    public function index2(Request $request) // index para modulo de lotes
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                      'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                      'lotes.clv_catastral','lotes.etapa_servicios')
                      ->orderBy('fraccionamientos.nombre','DESC')
                      ->orderBy('lotes.manzana','ASC')
                      ->orderBy('lotes.num_lote','ASC')->paginate(5);
        }
        else{
            if($buscar2=='' &&$buscar3=='')
            {
                $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('empresas','lotes.empresa_id','=','empresas.id')
                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                        'lotes.clv_catastral','lotes.etapa_servicios')
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('fraccionamientos.nombre','DESC')
                    ->orderBy('lotes.manzana','ASC')
                    ->orderBy('lotes.num_lote','ASC')->paginate(5);
            }
            else{
                if($buscar3=='')
                {
                    $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('empresas','lotes.empresa_id','=','empresas.id')
                    ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                            'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                            'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                            'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                            'lotes.clv_catastral','lotes.etapa_servicios')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('lotes.etapa_servicios', 'like', '%'. $buscar2 . '%')
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(5);
                }
                else{
                    $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('empresas','lotes.empresa_id','=','empresas.id')
                    ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                            'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                            'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                            'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                            'lotes.clv_catastral','lotes.etapa_servicios')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('lotes.etapa_servicios', 'like', '%'. $buscar2 . '%')
                        ->where('lotes.manzana', '=', $buscar3)
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(5);
                }
            }
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

        $etapa= Etapa::select('id')
                ->where('num_etapa','=', 'Sin Asignar')
                ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                ->get();
        
        $modelo= Modelo::select('id')
                ->where('nombre','=', 'Por Asignar')
                ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                ->get();

            try {
                DB::beginTransaction();
                $lote = new Lote();
                $lote->fraccionamiento_id = $request->fraccionamiento_id;
                $lote->etapa_id = $etapa[0]->id;
                $lote->manzana = $request->manzana;
                $lote->num_lote = $request->num_lote;
                $lote->sublote = $request->sublote;
                $lote->modelo_id = $modelo[0]->id;
                $lote->empresa_id = $request->empresa_id;
                $lote->calle = $request->calle;
                $lote->numero = $request->numero;
                $lote->interior = $request->interior;
                $lote->terreno = $request->terreno;
                $lote->construccion = $request->construccion;
                $lote->clv_catastral = $request->clv_catastral;
                $lote->etapa_servicios = $request ->etapa_servicios;
                $lote->arquitecto_id = 1;
                $lote->save();   

                $licencia = new Licencia();
                $licencia->id = $lote->id;
                $licencia->perito_dro = $lote->arquitecto_id;
                $licencia->save();

                DB::commit();


            } catch (Exception $e) { 
                DB::rollBack();
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
        $lote = Lote::findOrFail($request->id);
        $lote->fraccionamiento_id = $request->fraccionamiento_id;
        $lote->etapa_id = $request->etapa_id;
        $lote->manzana = $request->manzana;
        $lote->num_lote = $request->num_lote;
        $lote->sublote = $request->sublote;
        $lote->empresa_id = 1;
        $lote->calle = $request->calle;
        $lote->numero = $request->numero;
        $lote->interior = $request->interior;
        $lote->terreno = $request->terreno;
        $lote->construccion = $request->construccion;
        $lote->clv_catastral = $request->clv_catastral;
        $lote->etapa_servicios = $request ->etapa_servicios;
        

        $lote->save();
    }


    public function update2(Request $request)
    {
        $siembra = '';
       if(!$request->ajax())return redirect('/');

       $etapa= Etapa::select('num_etapa')
       ->where('id','=', $request->etapa_id)
       ->get();

       $modeloOld = Lote::select('modelo_id')
       ->where('id','=',$request->id)
       ->get();

       $nombreModelo = Modelo::select('nombre')
       ->where('id','=',$modeloOld[0]->modelo_id)
       ->get();

        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lote = Lote::findOrFail($request->id);
        $lote->fraccionamiento_id = $request->fraccionamiento_id;
        $lote->etapa_id = $request->etapa_id;
        $lote->manzana = $request->manzana;
        $lote->num_lote = $request->num_lote;
        $lote->sublote = $request->sublote;
        $lote->modelo_id = $request->modelo_id;
        if($request->modelo_id != $modeloOld[0]->modelo_id){
            $siembra = Carbon::today()->format('ymd');
            $lote->siembra=$siembra;

            $licencia = Licencia::findOrFail($request->id);
            $licencia->cambios = 1;
            if($nombreModelo[0]->nombre != "Por Asignar")
                $licencia->modelo_ant = $nombreModelo[0]->nombre;
            $licencia->save();
        }
        $lote->empresa_id = 1;
        $lote->calle = $request->calle;
        $lote->numero = $request->numero;
        $lote->interior = $request->interior;
        $lote->terreno = $request->terreno;
        $lote->construccion = $request->construccion;
        $lote->credito_puente = $request->credito_puente;
        /*if($etapa[0]->num_etapa!='Sin Asignar'){
            $siembra = Carbon::today()->format('ymd');
            $lote->siembra=$siembra;
         }*/
        

        $lote->save();
    }



    public function asignarMod(Request $request) // EN MASA
    {
        $siembra = '';
       if(!$request->ajax())return redirect('/');

       $etapa= Etapa::select('num_etapa')
       ->where('id','=', $request->etapa_id)
       ->get();

       $modeloOld = Lote::select('modelo_id')
       ->where('id','=',$request->id)
       ->get();

       $nombreModelo = Modelo::select('nombre')
       ->where('id','=',$modeloOld[0]->modelo_id)
       ->get();

       $modelo= Modelo::select('construccion')
       ->where('id','=', $request->modelo_id)
       ->get();
       
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lote = Lote::findOrFail($request->id);
        $lote->etapa_id = $request->etapa_id;
        $lote->modelo_id = $request->modelo_id;

        if($request->modelo_id != $modeloOld[0]->modelo_id){
            $siembra = Carbon::today()->format('ymd');
            $lote->siembra=$siembra;

            $licencia = Licencia::findOrFail($request->id);
            $licencia->cambios = 1;
            if($nombreModelo[0]->nombre != "Por Asignar")
                $licencia->modelo_ant = $nombreModelo[0]->nombre;
            $licencia->save();
        }
        $lote->construccion = $modelo[0]->construccion;
        /*if($etapa[0]->num_etapa!='Sin Asignar'){
            $siembra = Carbon::today()->format('ymd');
            $lote->siembra=$siembra;
         }*/
        $lote->save();
    }

    public function enviarAviso(Request $request)
    {
       if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lote = Lote::findOrFail($request->id);
        $lote->fecha_ini = $request ->fecha_ini;
        $lote->fecha_fin = $request ->fecha_fin;
        $lote->ini_obra = 1;
        $lote->arquitecto_id = $request ->arquitecto_id;
        $lote->ehl_solicitado = Carbon::today()->format('ymd');
        
        $lote->save();

        for($i =1;$i<=49;$i++){
            $partida = new PartidaController();
            switch($i){
                case 1:
                    $nombre='Preliminares y drenaje';
                    $partida->store($request->id, $nombre);
                    break;
                case 2:
                    $nombre='Cimentación (Incluye dentellones)';
                    $partida->store($request->id, $nombre);
                    break;
                case 3:
                    $nombre='Firmes';
                    $partida->store($request->id, $nombre);
                    break;
                case 4:
                    $nombre='Muros planta baja';
                    $partida->store($request->id, $nombre);
                    break;
                case 5:
                    $nombre='Cimbras planta baja';
                    $partida->store($request->id, $nombre);
                    break;
                case 6:
                    $nombre='Acero en losa de entrepiso';
                    $partida->store($request->id, $nombre);
                    break;
                case 7:
                    $nombre='Concreto en losa de entrepiso';
                    $partida->store($request->id, $nombre);
                    break;
                case 8:
                    $nombre='Muros planta alta';
                    $partida->store($request->id, $nombre);
                    break;
                case 9:
                    $nombre='Cimbras planta alta';
                    $partida->store($request->id, $nombre);
                    break;
                case 10:
                    $nombre='Acero en losa de azotea';
                    $partida->store($request->id, $nombre);
                    break;
                case 11:
                    $nombre='Concreto en losa de azotea';
                    $partida->store($request->id, $nombre);
                    break;
                case 12:
                    $nombre='Pergolas en azotea y/o losa inclinada';
                    $partida->store($request->id, $nombre);
                    break;
                case 13:
                    $nombre='Escalera';
                    $partida->store($request->id, $nombre);
                    break;
                case 14:
                    $nombre='Barra en cocina';
                    $partida->store($request->id, $nombre);
                    break;
                case 15:
                    $nombre='Pretiles';
                    $partida->store($request->id, $nombre);
                    break;
                case 16:
                    $nombre='Aplanados en azotea';
                    $partida->store($request->id, $nombre);
                    break;
                case 17:
                    $nombre='Impermeabilizaciones';
                    $partida->store($request->id, $nombre);
                    break;
                case 18:
                    $nombre='Instalacione hidraúlica';
                    $partida->store($request->id, $nombre);
                    break;
                case 19:
                    $nombre='Instalaciòn eléctrica';
                    $partida->store($request->id, $nombre);
                    break;
                case 20:
                    $nombre='Suministro de herreria';
                    $partida->store($request->id, $nombre);
                    break;
                case 21:
                    $nombre='Colocaciones herreria';
                    $partida->store($request->id, $nombre);
                    break;
                case 22:
                    $nombre='Aluminio';
                    $partida->store($request->id, $nombre);
                    break;
                case 23:
                    $nombre='Aplanados interiores planta baja';
                    $partida->store($request->id, $nombre);
                    break;
                case 24:
                    $nombre='Aplanados interiores planta alta';
                    $partida->store($request->id, $nombre);
                    break;
                case 25:
                    $nombre='Aplanados exteriores';
                    $partida->store($request->id, $nombre);
                    break;
                case 26:
                    $nombre='Pisos interiores planta baja';
                    $partida->store($request->id, $nombre);
                    break;
                case 27:
                    $nombre='Pisos interiores planta alta';
                    $partida->store($request->id, $nombre);
                    break;
                case 28:
                    $nombre='Piso en escalera';
                    $partida->store($request->id, $nombre);
                    break;
                case 29:
                    $nombre='Antiderrapante';
                    $partida->store($request->id, $nombre);
                    break;
                case 30:
                    $nombre='Azulejos';
                    $partida->store($request->id, $nombre);
                    break;
                case 31:
                    $nombre='Fachada';
                    $partida->store($request->id, $nombre);
                    break;
                case 32:
                    $nombre='Patio';
                    $partida->store($request->id, $nombre);
                    break;
                case 33:
                    $nombre='Acceso';
                    $partida->store($request->id, $nombre);
                    break;
                case 34:
                    $nombre='Lavadero';
                    $partida->store($request->id, $nombre);
                    break;
                case 35:
                    $nombre='Acometida';
                    $partida->store($request->id, $nombre);
                    break;
                case 36:
                    $nombre='Cimentacion en bardas';
                    $partida->store($request->id, $nombre);
                    break;
                case 37:
                    $nombre='Bardas';
                    $partida->store($request->id, $nombre);
                    break;
                case 38:
                    $nombre='Yeso planta baja';
                    $partida->store($request->id, $nombre);
                    break;
                case 39:
                    $nombre='Yeso planta alta';
                    $partida->store($request->id, $nombre);
                    break;
                case 40:
                    $nombre='Barra en baños';
                    $partida->store($request->id, $nombre);
                    break;
                case 41:
                    $nombre='Pintura en interior (en muros y plafones)';
                    $partida->store($request->id, $nombre);
                    break;
                case 42:
                    $nombre='Pintura en exterior';
                    $partida->store($request->id, $nombre);
                    break;
                case 43:
                    $nombre='Barra en patio';
                    $partida->store($request->id, $nombre);
                    break;
                case 44:
                    $nombre='Muebles';
                    $partida->store($request->id, $nombre);
                    break;
                case 45:
                    $nombre='Chapas y puertas';
                    $partida->store($request->id, $nombre);
                    break;
                case 46:
                    $nombre='Acarreos';
                    $partida->store($request->id, $nombre);
                    break;
                case 47:
                    $nombre='Jardineria (tierra en patio y jardin)';
                    $partida->store($request->id, $nombre);
                    break;
                case 48:
                    $nombre='Limpiezas';
                    $partida->store($request->id, $nombre);
                    break;
                case 49:
                    $nombre='Limpieza extra fina en vivienda al entregar la vivienda';
                    $partida->store($request->id, $nombre);
                    break;
            }            
            
        }
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



    public function import(Request $request){
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
                $etapa= Etapa::select('id')
                ->where('num_etapa','=', 'Sin Asignar')
                ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                ->get();

                $modelo= Modelo::select('id','construccion')
                ->where('nombre','=', 'Por Asignar')
                ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                ->get();

                if(Lote::count() > 0){
                    $lotes = Lote::select('id')->get();
                    $id = $lotes->last()->id + 1;
                }
                else
                    $id = 1;
                

                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){
 
                    foreach ($data as $key => $value) {
                            

                        $insert[] = [
                        'fraccionamiento_id' => $request->fraccionamiento_id,
                        'etapa_id' => $etapa[0]->id,
                        'manzana' => $value->manzana,
                        'num_lote' => $value->num_lote,
                        'sublote' => $value->duplex,
                        'modelo_id' => $modelo[0]->id,
                        'empresa_id' => 1,
                        'calle' => $value->calle,
                        'numero' => $value->numero_oficial,
                        'interior' => $value->interior,
                        'terreno' => $value->superficie_terreno,
                        'construccion' => $modelo[0]->construccion,
                        'clv_catastral' =>$value->clave_catastral,
                        'etapa_servicios' =>$value->etapa_servicios,
                        'arquitecto_id' =>1
                        
                        ];

                        $insert2[]  = [
                            'id' => $id++,
                            'perito_dro' => 1
                        ];


                    }
 
                    if(!empty($insert)){
 
                        $insertData = DB::table('lotes')->insert($insert);
                        $insertData2 = DB::table('licencias')->insert($insert2);
                        if ($insertData) {
                            Session::flash('success', 'Your Data has successfully imported');
                        }else {                        
                            Session::flash('error', 'Error inserting the data..');
                            return back();
                        }
                    }
                }
 
                return back();
 
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }

    // public function selectManzana_proyecto(Request $request){
    //     //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
    //     if(!$request->ajax())return redirect('/');

    //     $buscar = $request->buscar;
    //     $manzanas = Manzana::select('manzana','id')
    //     ->where('fraccionamiento_id', '=', $buscar )->get();
    //     return['manzanas' => $manzanas];
    // }

   
    public function select_modelos_etapa(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar1 = $request->buscar1;
        $buscar2 = $request->buscar2;
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->join('modelos','lotes.modelo_id','=','modelos.id')
        ->select('modelos.nombre', 'lotes.modelo_id', 'lotes.fraccionamiento_id',  'lotes.etapa_id')
        ->distinct()
        ->where('lotes.fraccionamiento_id', '=', $buscar1)
        ->where('lotes.etapa_id', '=', $buscar2)->get();
        return['lotes' => $lotes];
    }

    public function select_manzanas_etapa (Request $request){
        if(!$request->ajax())return redirect('/');
        
        $buscar = $request->buscar;
        $buscar1 = $request->buscar1;
        $manzana = Lote::select('lotes.manzana')->distinct()
                        ->where('lotes.fraccionamiento_id','=', $buscar)
                        ->where('lotes.etapa_id','=', $buscar1)->get();

        return ['manzana' => $manzana];

    }

    public function select_lote_manzana (Request $request){
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar1 = $request->buscar1;
        $buscar2 = $request->buscar2;
        $lote_manzana = Lote::select ('lotes.num_lote','lotes.id')
                             ->where('lotes.fraccionamiento_id','=', $buscar)
                             ->where('lotes.etapa_id','=', $buscar1)
                             ->where('lotes.manzana','=', $buscar2)->get();

        return ['lote_manzana' => $lote_manzana];
    }


    public function indexIniObra(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                      'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios')
                ->where('lotes.ini_obra', '=', '0')
                ->orderBy('fraccionamientos.nombre','lotes.id')->paginate(12);
                
        }
                
        else{
            $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                    'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                    'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                    'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios')
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->where('lotes.ini_obra', '=', '0')
                ->orderBy('fraccionamientos.nombre','lotes.id')->paginate(12);
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


}
