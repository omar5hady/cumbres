<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PartidaController;
use App\Modelo;
use App\Specification;
use DB;
use App\Contrato;
use Auth;
use App\Version_modelo;

use App\Http\Resources\SpecificationResource;

//Controlador para el modelo Modelo.
class ModeloController extends Controller
{
    //Función que retorna los modelos registrados
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        //Query
        $modelos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
            ->select('modelos.nombre','modelos.tipo','modelos.fraccionamiento_id',
            'fraccionamientos.nombre as fraccionamiento','modelos.terreno','modelos.construccion',
            'modelos.archivo','modelos.id','espec_obra')
            ->where('modelos.nombre', '!=','Por Asignar');//Diferente a modelo por asignar
        if($buscar != '')//Busqueda general
                $modelos = $modelos->where($criterio, 'like', '%'. $buscar . '%');
        $modelos = $modelos->orderBy('fraccionamientos.nombre','asc')
                ->orderBy('modelos.nombre','asc')->paginate(8);

        if(sizeOf($modelos)){
            foreach($modelos as $modelo){
                $modelo->especificaciones = Specification::select('general')->where('modelo_id','=',$modelo->id)->distinct()->get();
                if(sizeof($modelo->especificaciones)){
                    foreach($modelo->especificaciones as $generales){
                        $generales->detalle = SpecificationResource::collection(
                            Specification::where('modelo_id','=',$modelo->id)->where('general','=',$generales->general)->get());
                    }
                }
            }
        }

        return [
            'pagination' => [
                'total'         => $modelos->total(),
                'current_page'  => $modelos->currentPage(),
                'per_page'      => $modelos->perPage(),
                'last_page'     => $modelos->lastPage(),
                'from'          => $modelos->firstItem(),
                'to'            => $modelos->lastItem(),
            ],
            'modelos' => $modelos
        ];
    }

    //Función para registrar un nuevo modelo.
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $modelo = new Modelo();
        $modelo->nombre = $request->nombre;
        $modelo->tipo = $request->tipo;
        $modelo->fraccionamiento_id = $request->fraccionamiento_id;
        $modelo->terreno = $request->terreno;
        $modelo->construccion = $request->construccion;
        $modelo->archivo = $request->archivo;
        $modelo->save();
        //Se crean las partidas correspondientes para el nuevo modelo.
        for($i =1;$i<=49;$i++){
            $partida = new PartidaController();
            switch($i){
                case 1:
                    $nombre='Preliminares y drenaje';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 2:
                    $nombre='Cimentación (Incluye dentellones)';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 3:
                    $nombre='Firmes';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 4:
                    $nombre='Muros planta baja';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 5:
                    $nombre='Cimbras planta baja';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 6:
                    $nombre='Acero en losa de entrepiso';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 7:
                    $nombre='Concreto en losa de entrepiso';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 8:
                    $nombre='Muros planta alta';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 9:
                    $nombre='Cimbras planta alta';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 10:
                    $nombre='Acero en losa de azotea';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 11:
                    $nombre='Concreto en losa de azotea';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 12:
                    $nombre='Pergolas en azotea y/o losa inclinada';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 13:
                    $nombre='Escalera';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 14:
                    $nombre='Barra en cocina';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 15:
                    $nombre='Pretiles';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 16:
                    $nombre='Aplanados en azotea';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 17:
                    $nombre='Impermeabilizaciones';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 18:
                    $nombre='Instalacione hidraúlica';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 19:
                    $nombre='Instalaciòn eléctrica';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 20:
                    $nombre='Suministro de herreria';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 21:
                    $nombre='Colocaciones herreria';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 22:
                    $nombre='Aluminio';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 23:
                    $nombre='Aplanados interiores planta baja';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 24:
                    $nombre='Aplanados interiores planta alta';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 25:
                    $nombre='Aplanados exteriores';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 26:
                    $nombre='Pisos interiores planta baja';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 27:
                    $nombre='Pisos interiores planta alta';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 28:
                    $nombre='Piso en escalera';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 29:
                    $nombre='Antiderrapante';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 30:
                    $nombre='Azulejos';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 31:
                    $nombre='Fachada';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 32:
                    $nombre='Patio';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 33:
                    $nombre='Acceso';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 34:
                    $nombre='Lavadero';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 35:
                    $nombre='Acometida';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 36:
                    $nombre='Cimentacion en bardas';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 37:
                    $nombre='Bardas';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 38:
                    $nombre='Yeso planta baja';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 39:
                    $nombre='Yeso planta alta';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 40:
                    $nombre='Barra en baños';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 41:
                    $nombre='Pintura en interior (en muros y plafones)';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 42:
                    $nombre='Pintura en exterior';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 43:
                    $nombre='Barra en patio';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 44:
                    $nombre='Muebles';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 45:
                    $nombre='Chapas y puertas';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 46:
                    $nombre='Acarreos';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 47:
                    $nombre='Jardineria (tierra en patio y jardin)';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 48:
                    $nombre='Limpiezas';
                    $partida->store($modelo->id, $nombre);
                    break;
                case 49:
                    $nombre='Limpieza extra fina en vivienda al entregar la vivienda';
                    $partida->store($modelo->id, $nombre);
                    break;
            }
        }
    }

    //Función para actualizar un modelo.
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $modelo = Modelo::findOrFail($request->id);
        $modelo->nombre = $request->nombre;
        $modelo->tipo = $request->tipo;
        $modelo->fraccionamiento_id = $request->fraccionamiento_id;
        $modelo->terreno = $request->terreno;
        $modelo->construccion = $request->construccion;
        $modelo->save();
    }
    //Función para eliminar el registro de un modelo.
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $modelo = Modelo::findOrFail($request->id);
        $modelo->delete();
    }
    //Función para subir el archivo de especificaciones de un modelo.
    public function formSubmit(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fileName = time().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/modelos'), $fileName);

        if($moved){
            if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
            $modelo = Modelo::findOrFail($request->id);
            $modelo->archivo = $fileName;
            $modelo->id = $id;
            $modelo->save(); //Insert

            }
    	return response()->json(['success'=>'You have successfully upload file.']);
    }
    //Función para descargar el archivo especificaciones de modelo.
    public function downloadFile($fileName){
        $pathtoFile = public_path().'/files/modelos/'.$fileName;
        return response()->file($pathtoFile);
    }
    //Función que retorna los modelos ligados a un proyecto
    public function selectModelo_proyecto(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $modelos = Modelo::select('nombre','id')
            ->where('fraccionamiento_id', '=', $buscar )
            ->where('nombre','!=','Por Asignar');
        if($request->mostrar == 2)//No mostrar terreno
            $modelos= $modelos->where('nombre','!=','Terreno');
        $modelos= $modelos->orderBy('nombre','asc')
        ->get();
        return['modelos' => $modelos];
    }
    //Función para retornar los modelos de lotes disponibles para venta
    public function selectModeloDisp(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $modelos = Modelo::join('lotes','modelos.id','lotes.modelo_id')
        ->select('modelos.nombre','modelos.id')
        ->where('modelos.fraccionamiento_id', '=', $buscar )
        ->where('lotes.habilitado','=',1)
        ->where('lotes.contrato','=',0)
        ->where('modelos.nombre','!=','Por Asignar');
        if($request->mostrar == 2)//Diferente a terreno
            $modelos= $modelos->where('modelos.nombre','!=','Terreno');
        $modelos= $modelos->orderBy('modelos.nombre','asc')
        ->distinct()
        ->get();
        return['modelos' => $modelos];
    }
    //Función que retorna el tamaño de construcción y terreno de un modelo
    public function selectConsYTerreno(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $modelosTc = Modelo::select('id','terreno','construccion')->where('id', '=', $buscar )->get();
        return ['modelosTc' => $modelosTc];
    }
    //Función que retorna el nombre del archivo de especificacion de un lote para contrato.
    public function modeloArchivoContrato (Request $request, $id)
    {
         $modelo = Contrato::join('creditos','contratos.id','=','creditos.id')
        ->join('lotes','creditos.lote_id','=','lotes.id')
        ->join('modelos','lotes.modelo_id','=','modelos.id')
        ->select('modelos.archivo','lotes.nombre_archivo')
        ->where('contratos.id','=',$id)->get();
        if($modelo[0]->nombre_archivo != NULL){
            $version = Version_modelo::select('archivo')->where('version','=',$modelo[0]->nombre_archivo)->get();
            $modelo[0]->archivo = $version[0]->archivo;
        }
        return ['modelo' => $modelo];
    }

    //Función que retorna los modelos registrados con sus archivos correspondientes,
    //Archivos ligados a ventas (Reglamentos, cartas de telecomunicaciones, especificaciones, etc.)
    public function indexDocs (Request $request){
        if(!$request->ajax())return redirect('/');

        $criterio = $request->criterio;
        $b_fraccionamiento = $request->b_fraccionamiento;
        $b_etapa = $request->b_etapa;
        $b_modelo = $request->b_modelo;
        //Query principal
        $archivos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','fraccionamientos.id','=','etapas.fraccionamiento_id')
            ->select('modelos.archivo','modelos.nombre as modelo','etapas.num_etapa','etapas.archivo_reglamento',
            'etapas.plantilla_carta_servicios','etapas.costo_mantenimiento','etapas.plantilla_telecom',
            'fraccionamientos.nombre as proyecto','etapas.empresas_telecom','etapas.empresas_telecom_satelital',
            'etapas.carpeta_ventas',
            'modelos.id as modeloID','etapas.id as etapaID','fraccionamientos.id as fraccionamientoID')
        ->where('modelos.nombre','!=','Por Asignar')
        ->where('etapas.num_etapa','!=','Sin Asignar');
        if($b_fraccionamiento != '')//Busqueda por proyecto
            $archivos = $archivos->where($criterio,'=',$b_fraccionamiento);
        if($b_etapa != '')//Busqueda por etapa
            $archivos = $archivos->where('etapas.id','=',$b_etapa);
        if($b_modelo != '')//Busqueda por modelo
            $archivos = $archivos->where('modelos.id','=',$b_modelo);
        $archivos = $archivos->orderBy('modelos.nombre','asc')->distinct()->paginate(10);

        return [
        'pagination' => [
            'total'         => $archivos->total(),
            'current_page'  => $archivos->currentPage(),
            'per_page'      => $archivos->perPage(),
            'last_page'     => $archivos->lastPage(),
            'from'          => $archivos->firstItem(),
            'to'            => $archivos->lastItem(),
        ],
        'archivos' => $archivos
        ];
    }
    //Función para descargargar el catalogo de especificaciones de un modelo
    public function descargaCatalogoDocs($modelo_id){
        $archivos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','fraccionamientos.id','=','etapas.fraccionamiento_id')
        ->select('modelos.archivo')
        ->where('modelos.nombre','!=','Por Asignar')
        ->where('etapas.num_etapa','!=','Sin Asignar')
        ->where('modelos.id','=',$modelo_id)
        ->get();

        $pathtoFile = public_path().'/files/modelos/'.$archivos[0]->archivo;
        return response()->file($pathtoFile);
    }
    //Función para subir el archivo de especificaciones de obra
    public function formSubmitEspecObra(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fileName = time().'.'.$request->archivoObra->getClientOriginalExtension();
        $moved =  $request->archivoObra->move(public_path('/files/modelos'), $fileName);

        if($moved){
            if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
            $modelo = Modelo::findOrFail($request->id);
            $modelo->espec_obra = $fileName;
            $modelo->id = $id;
            $modelo->save(); //Insert
        }
    	return response()->json(['success'=>'You have successfully upload file.']);
    }
    //Función para descargar el archivo de especificaciones de obra.
    public function downloadFileEspecObra($fileName){
        $pathtoFile = public_path().'/files/modelos/'.$fileName;
        return response()->file($pathtoFile);
    }
}
