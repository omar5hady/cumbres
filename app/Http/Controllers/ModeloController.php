<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PartidaController;
use App\Modelo;
use DB;
use App\Contrato;
use Auth;
use App\Version_modelo;


class ModeloController extends Controller
{
     
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $modelos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
            ->select('modelos.nombre','modelos.tipo','modelos.fraccionamiento_id',
            'fraccionamientos.nombre as fraccionamiento','modelos.terreno','modelos.construccion',
            'modelos.archivo','modelos.id','espec_obra')
            ->where('modelos.nombre', '!=','Por Asignar');
        
        
        if($buscar != '')
                $modelos = $modelos->where($criterio, 'like', '%'. $buscar . '%');

        $modelos = $modelos->orderBy('fraccionamientos.nombre','asc')
                ->orderBy('modelos.nombre','asc')->paginate(8);
        

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

    //funcion para insertar en la tabla
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

    //funcion para actualizar los datos
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

    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $modelo = Modelo::findOrFail($request->id);
        $modelo->delete();
    }

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

    public function downloadFile($fileName){
        $pathtoFile = public_path().'/files/modelos/'.$fileName;
        return response()->download($pathtoFile);
    }

    public function selectModelo_proyecto(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $modelos = Modelo::select('nombre','id')
        ->where('fraccionamiento_id', '=', $buscar )
        //->where('nombre','!=','Por Asignar')
        ->orderBy('nombre','asc')
        ->get();
        return['modelos' => $modelos];
    }

    public function selectModelo_proyecto2(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $modelos = Modelo::select('nombre','id')
        ->where('fraccionamiento_id', '=', $buscar )
        ->where('nombre','!=','Por Asignar')
        //->where('nombre','!=','Por Asignar')
        ->orderBy('nombre','asc')
        ->get();
        return['modelos' => $modelos];
    }

    public function selectConsYTerreno(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $modelosTc = Modelo::select('id','terreno','construccion')
        ->where('id', '=', $buscar )->get();

        return ['modelosTc' => $modelosTc];
    }

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

    public function indexDocs (Request $request){
        if(!$request->ajax())return redirect('/');

        $criterio = $request->criterio;
        $b_fraccionamiento = $request->b_fraccionamiento;
        $b_etapa = $request->b_etapa;
        $b_modelo = $request->b_modelo;

        $query = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','fraccionamientos.id','=','etapas.fraccionamiento_id')
            ->select('modelos.archivo','modelos.nombre as modelo','etapas.num_etapa','etapas.archivo_reglamento',
            'etapas.plantilla_carta_servicios','etapas.costo_mantenimiento','etapas.plantilla_telecom',
            'fraccionamientos.nombre as proyecto','etapas.empresas_telecom','etapas.empresas_telecom_satelital',
            'modelos.id as modeloID','etapas.id as etapaID','fraccionamientos.id as fraccionamientoID')
        ->where('modelos.nombre','!=','Por Asignar')
        ->where('etapas.num_etapa','!=','Sin Asignar');
        
        if($b_fraccionamiento != '')
            $query = $query->where($criterio,'=',$b_fraccionamiento);
        if($b_etapa != '')
            $query = $query->where('etapas.id','=',$b_etapa);
        if($b_modelo != '')
            $query = $query->where('modelos.id','=',$b_modelo);

        $archivos = $query->orderBy('modelos.nombre','asc')->distinct()->paginate(10);

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

    public function descargaCatalogoDocs($modelo_id){
        $archivos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','fraccionamientos.id','=','etapas.fraccionamiento_id')
        ->select('modelos.archivo')
        ->where('modelos.nombre','!=','Por Asignar')
        ->where('etapas.num_etapa','!=','Sin Asignar')
        ->where('modelos.id','=',$modelo_id)
        ->get();

        $pathtoFile = public_path().'/files/modelos/'.$archivos[0]->archivo;
        return response()->download($pathtoFile);
    }
    
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

    public function downloadFileEspecObra($fileName){
        $pathtoFile = public_path().'/files/modelos/'.$fileName;
        return response()->download($pathtoFile);
    }
}
