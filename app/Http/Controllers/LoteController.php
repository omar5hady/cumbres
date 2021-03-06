<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AvanceController;
use Illuminate\Http\Request;
use App\Precio_etapa;
use App\Sobreprecio_modelo;
use App\Notifications\NotifyAdmin;
use App\Precio_modelo;
use App\Lote;
use App\Lote_promocion;
use App\Modelo;
use App\Etapa;
use App\Licencia;
use App\Partida;
use App\Avance;
use App\Promocion;
use App\Contrato;
use Session;
use Excel;
use File;
use DB;
use Carbon\Carbon;
use App\Apartado;
use Auth;
use App\User;
use App\Credito;
use App\Vendedor;
use App\Expediente;
use App\lotes_individuales;
use App\Credito_puente;
use App\Lote_puente;
use App\Precio_puente;

class LoteController extends Controller
{

    public function index(Request $request) //Index para modulo asignar modelo
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $b_modelo = $request->bmodelo;
        $b_lote = $request->blote;
        $b_habilitado = $request->b_habilitado;
        $criterio = $request->criterio;
        $b_puente = $request->b_puente;

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')->join('licencias','lotes.id','=','licencias.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','licencias.avance','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                  'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                  'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id',
                  'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                  'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.etapa_servicios',
                  'lotes.regimen_condom','lotes.extra', 'lotes.extra_ext','lotes.emp_terreno', 'lotes.emp_constructora',
                  'lotes.fecha_termino_ventas');
        
        if($b_habilitado != '')
            $query = $query->where('lotes.habilitado','=', $b_habilitado);

            $lotes = $query;
            if($buscar != '')
                $lotes = $lotes->where($criterio, 'like', '%'. $buscar . '%');
            if($b_puente != '')
                $lotes = $lotes->where('lotes.credito_puente','=',$b_puente);
            if($b_modelo != '')
                $lotes = $lotes->where('modelos.id', '=', $b_modelo );
            if($buscar2 != '')
                $lotes = $lotes->where('lotes.etapa_id', 'like', '%'. $buscar2 . '%');
            if($buscar3 != '')
                $lotes = $lotes->where('lotes.manzana', '=',$buscar3);
            if($b_lote != '')
                $lotes = $lotes->where('lotes.num_lote', '=',$b_lote);
            

        if($request->b_empresa != ''){
            $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        if($request->b_empresa2 != ''){
            $lotes= $lotes->where('lotes.emp_terreno','=',$request->b_empresa2);
        }

        $lotes = $lotes->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')
                        ->orderBy('lotes.etapa_servicios','ASC')->paginate(25);
        
        
        

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

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                    'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                    'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                    'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                    'lotes.emp_terreno', 'lotes.emp_constructora',
                    'lotes.clv_catastral','lotes.etapa_servicios');
        
            $lotes = $query;
            
            if($buscar!='')
                $lotes = $lotes->where($criterio, 'like', '%'. $buscar . '%');
            if($buscar2!='')
                $lotes = $lotes->where('lotes.etapa_servicios', 'like', '%'. $buscar2 . '%');
            if($buscar3!='')
                $lotes = $lotes->where('lotes.manzana', '=', $buscar3);
    
            
        if($request->b_etapa != ''){
            $lotes = $lotes->where('lotes.etapa_id', '=', $request->b_etapa);
        }
        

        if($request->b_empresa != ''){
            $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        if($request->b_empresa2 != ''){
            $lotes= $lotes->where('lotes.emp_terreno','=',$request->b_empresa2);
        }

        $lotes = $lotes->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('etapas.num_etapa','DESC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(25);

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

    //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

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

    //funcion para actualizar los datos
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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

    public function update2(Request $request) //Update asignar modelo
    {
        $siembra = '';
        $terrenoExcedente = 0;
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        
        ///////////// 
       // if(Auth::user()->id == 26516)return redirect('/');

       $etapa= Etapa::select('num_etapa')
       ->where('id','=', $request->etapa_id)
       ->get();

       $modeloOld = Lote::select('modelo_id')
       ->where('id','=',$request->id)
       ->get();

       $nombreModelo = Modelo::select('nombre')
       ->where('id','=',$modeloOld[0]->modelo_id)
       ->get();

       $terrenoModelo = Modelo::select('terreno', 'nombre')
       ->where('id','=',$request->modelo_id)
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
            if($nombreModelo[0]->nombre != "Por Asignar"){
                $licencia->modelo_ant = $nombreModelo[0]->nombre;

                $this->actPuenteLote($request->id, $nombreModelo[0]->nombre, $request->modelo_id);
            }
            $licencia->save();
        }
        $lote->empresa_id = 1;
        $lote->calle = $request->calle;
        $lote->numero = $request->numero;
        $lote->interior = $request->interior;
        $lote->terreno = $request->terreno;
        $lote->construccion = $request->construccion;
        $lote->credito_puente = $request->credito_puente;  
        $lote->lote_comercial = $request->lote_comercial;   
        $lote->casa_muestra = $request->casa_muestra;
        $lote->comentarios = $request->comentarios;
        $lote->regimen_condom = $request->regimen;
        $lote->fecha_termino_ventas = $request->fecha_termino_ventas;
        $lote->extra = $request->extra;
        $lote->extra_ext = $request->extra_ext;

        if($request->habilitado == 1){
            if($terrenoModelo[0]->nombre != "Terreno"){
                $precioTerreno = Precio_etapa::select('precio_excedente','id')
                ->where('fraccionamiento_id','=',$request->fraccionamiento_id)
                ->where('etapa_id','=',$request->etapa_id)->get();
        
                $precioBase = Precio_modelo::select('precio_modelo')
                ->where('modelo_id','=',$request->modelo_id)
                ->where('precio_etapa_id', '=', $precioTerreno[0]->id)
                ->get();
        
                $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
                ->where('sobreprecios_modelos.lote_id','=',$request->id)->get();

                $terrenoExcedente = ($lote->terreno - $terrenoModelo[0]->terreno);
                if($terrenoExcedente > 0)
                    $lote->excedente_terreno = $terrenoExcedente * $precioTerreno[0]->precio_excedente;

                $lote->precio_base = $precioBase[0]->precio_modelo;

                if($sobreprecios[0]->sobreprecios != NULL)
                    $lote->sobreprecio = $sobreprecios[0]->sobreprecios;
                else
                    $lote->sobreprecio = 0;
                
            }
            else{
                $preciom2 = lotes_individuales::select('costom2','id')
                        ->where('etapa_id','=',$request->etapa_id)->get();
                
                $lote->precio_base = $preciom2[0]->costom2 * $lote->terreno;
            }
            
            
        }
        else{
            $lote->excedente_terreno = 0;
            $lote->sobreprecio=0;
            $lote->precio_base=0;
        }
        $lote->habilitado = $request->habilitado;

        $lote->save();
    }

    private function actPuenteLote($lote, $modeloAnt, $modelo_id)
    {

        $lotePuente = Lote_puente::select('id')->where('lote_id','=',$lote)->get();

        if(sizeof($lotePuente)){
            $auxLotePuente = Lote_puente::findOrFail($lotePuente[0]->id);
            $precio = 0;

            $precioModelo = Precio_puente::select('precio')->where('solicitud_id','=',$auxLotePuente->solicitud_id)->where('modelo_id','=',$modelo_id)->get();
            if(sizeof($precioModelo))
                $precio = $precioModelo[0]->precio;
            $auxLotePuente->modeloAnt2 = $auxLotePuente->modeloAnt1;
            $auxLotePuente->precio2 = $auxLotePuente->precio1;
            $auxLotePuente->modeloAnt1 = $modeloAnt;
            $auxLotePuente->precio1 = $auxLotePuente->precio_p;
            $auxLotePuente->modelo_id = $modelo_id;
            $auxLotePuente->precio_p = $precio;
            $auxLotePuente->save();

            $creditoPuente = Credito_puente::findOrFail($auxLotePuente->solicitud_id);
            $creditoPuente->total = $creditoPuente->total - $auxLotePuente->precio1 + $auxLotePuente->precio_p;
            $creditoPuente->save();
        }

    }

    public function asignarMod(Request $request) // EN MASA
    {
        $siembra = '';
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        ///////////// 
        if(Auth::user()->id == 26516)return redirect('/');


        $aviso = $request->aviso;

        $etapa= Etapa::select('num_etapa')
        ->where('id','=', $request->etapa_id)
        ->get();

        $modeloOld = Lote::select('modelo_id','num_lote')
        ->where('id','=',$request->id)
        ->get();

        $fraccionamientos = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
        ->select('fraccionamientos.nombre')
        ->where('lotes.id','=',$request->id)
        ->get();

        $nombreModelo = Modelo::select('nombre')
        ->where('id','=',$modeloOld[0]->modelo_id)
        ->get();

        $modelo= Modelo::select('construccion','nombre')
        ->where('id','=', $request->modelo_id)
        ->get();

        try {
            DB::beginTransaction();
        
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
            $lote->excedente_terreno = 0;
            $lote->sobreprecio=0;
            $lote->precio_base=0;
            $lote->habilitado=0;
            $lote->save();
            
            if($aviso != '0'){

                if($nombreModelo[0]->nombre != "Por Asignar"){
                    $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
                    $fecha = Carbon::now();
                    
                    $arregloSimPendientes = [
                        'notificacion' => [
                            'usuario' => $imagenUsuario[0]->usuario,
                            'foto' => $imagenUsuario[0]->foto_user,
                            'fecha' => $fecha,
                            'msj' => 'Asigno el modelo: '.$modelo[0]->nombre.' a la etapa: '.$etapa[0]->num_etapa.' a '.$aviso.' lotes, del fraccionamiento '.$fraccionamientos[0]->nombre,
                            'titulo' => 'Nuevos modelos asignados'
                        ]
                    ];

                    $users = User::select('id')->where('rol_id','=','1')
                        ->orWhere('rol_id','=','6')
                        ->orWhere('rol_id','=','4')->get();

                    foreach($users as $notificar){
                        User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloSimPendientes));
                    }
                }
                
                
                
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    public function enviarAviso(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $aviso = $request->aviso;
        $id = $request->id;
       
        try {
        DB::beginTransaction();
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lote = Lote::findOrFail($request->id);
        $lote->fecha_ini = $request ->fecha_ini;
        $lote->fecha_fin = $request ->fecha_fin;
        $lote->fecha_termino_ventas = $request ->fecha_termino_ventas;
        $lote->ini_obra = 1;
        $lote->arquitecto_id = $request ->arquitecto_id;
        $lote->ehl_solicitado = Carbon::today()->format('ymd');

        $lote->num_inicio = $request->num_inicio;
        
        $lote->save();

        $lic = Licencia::findOrFail($request->id);
        if($lic->avance == 0)
            $lic->avance = 1;
        $lic->save();

        if($aviso != '0'){
                
            $loteIni = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->select('fraccionamientos.nombre as proyecto','lotes.fecha_fin','lotes.fecha_ini')
            ->where('lotes.id','=',$id)->get();

            setlocale(LC_TIME, 'es_MX.utf8');
            $fecha_fin = new Carbon($loteIni[0]->fecha_fin);
            $loteIni[0]->fecha_fin = $fecha_fin->formatLocalized('%d-%m-%Y');
            $fecha_ini = new Carbon($loteIni[0]->fecha_ini);
            $loteIni[0]->fecha_ini = $fecha_ini->formatLocalized('%d-%m-%Y');

            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
            $fecha = Carbon::now();
            $msj = "Se han enviado ". $aviso ." lotes del Proyecto ". $loteIni[0]->proyecto . " a inicio de obra (" . $loteIni[0]->fecha_ini . " - " . $loteIni[0]->fecha_fin . ") ";
            $iniciosObra = [
                'notificacion' => [
                    'usuario' => $imagenUsuario[0]->usuario,
                    'foto' => $imagenUsuario[0]->foto_user,
                    'fecha' => $fecha,
                    'msj' => $msj,
                    'titulo' => 'Inicio de obra pendiente'
                ]
            ];

            $users = User::select('id')->where('rol_id','=','5')->get();

            foreach($users as $notificar){
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($iniciosObra));
            }
        }

        //Aqui se deberia hacer toda la asignacion para la tabla avances
        $partidas = Partida::select('id','partida')
            ->where('modelo_id','=',$lote->modelo_id)->get();
        
            foreach($partidas as $index => $partida) {
                $n_avance = new AvanceController();
                $n_avance->store($lote->id, $partida->id);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    public function actNumInicio(Request $request){
        $lote = Lote::findOrFail($request->id);
        $lote->num_inicio = $request->num_inicio;
        
        $lote->save();

    }

    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $lote = Lote::findOrFail($request->id);
        $lote->delete();
    }

    public function import(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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

                if(Licencia::count() > 0){
                    $lotes =Licencia::select('id')->get();
                    $id = $lotes->last()->id + 1;
                }
                else{
                    $id = 1;
                }

                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();

                if(!empty($data) && $data->count()){
 
                    foreach ($data as $key => $value) {
                        $emp_terreno = 'Grupo Constructor Cumbres';
                        $emp_constructora = 'Grupo Constructor Cumbres';

                        if($value->empresa_terreno == 2){
                            $emp_terreno = 'CONCRETANIA';
                        }
                        if($value->empresa_constructora == 2){
                            $emp_constructora = 'CONCRETANIA';
                        }

                        $insert[] = [
                        'id' => $id,
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
                        'arquitecto_id' => 1,
                        'emp_constructora' =>$emp_constructora,
                        'emp_terreno' =>$emp_terreno
                        
                        
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
                        ->where('lotes.etapa_id','=', $buscar1)
                        ->orderBy('lotes.manzana','asc')
                        ->get();

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
                             ->where('lotes.manzana','=', $buscar2);

            if($request->puente == 1)
                $lote_manzana = $lote_manzana->where('lotes.credito_puente','=', NULL);

        $lote_manzana = $lote_manzana->get();

        return ['lote_manzana' => $lote_manzana];
    }

    public function indexIniObra(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                  'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                  'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                  'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','lotes.fecha_termino_ventas')
                  ->where('modelos.nombre','!=','Terreno');
        
       
                
        
            $lotes = $query->where('lotes.ini_obra', '=', '0')
                ->where('modelos.nombre','!=','Por Asignar');

                if($buscar != '')
                    $lotes = $lotes->where($criterio, '=',$buscar);
                if($buscar2 != '')
                    $lotes = $lotes->where('lotes.etapa_id', '=',$buscar2 );
                if($buscar3 != '')
                    $lotes = $lotes->where('lotes.manzana', 'like', '%'. $buscar3 . '%');
        

        if($request->b_empresa != ''){
            $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $lotes = $lotes->orderBy('fraccionamientos.nombre','lotes.id')->paginate(15);

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

    public function indexHistorialIniObra(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $fecha = $request->fecha;
        $fecha2 = $request->fecha2;
        $criterio = $request->criterio;

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                  'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                  'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id','lotes.num_inicio',
                  'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                  'lotes.fecha_termino_ventas', 'lotes.ehl_solicitado')
            ->where('modelos.nombre','!=','Por Asignar');

            $lotes = $query;
            if($buscar != '')
                $lotes = $lotes->where($criterio, '=',$buscar);
            if($buscar != '')
                $lotes = $lotes->where('lotes.etapa_id', '=',$buscar2 );
            if($buscar != '')
                $lotes = $lotes->where('lotes.manzana', 'like', '%'. $buscar3 . '%');
            if($fecha == '' && $fecha2 == '')
                $lotes = $lotes->whereBetween('lotes.ehl_solicitado', [$fecha, $fecha2]);
                  
        
        

        if($request->b_empresa != ''){
            $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $lotes = $lotes->orderBy('lotes.ehl_solicitado','desc')
                        ->orderBy('fraccionamientos.nombre','lotes.id')->paginate(15);

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

    public function excelLotes (Request $request, $fraccionamiento_id)
    {

        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->join('modelos','lotes.modelo_id','=','modelos.id')
        ->join('empresas','lotes.empresa_id','=','empresas.id')
        ->select('fraccionamientos.nombre as proyecto','lotes.etapa_servicios','lotes.manzana','lotes.num_lote',
        'lotes.sublote','lotes.calle','lotes.numero as numero_oficial','lotes.interior','lotes.terreno',
        'lotes.clv_catastral','lotes.id')
        ->where('lotes.fraccionamiento_id', 'like', '%'. $fraccionamiento_id. '%')
        ->get();

        return Excel::create('Lotes de '. $lotes[0]->proyecto , function($excel) use ($lotes){
            $excel->sheet('lotes', function($sheet) use ($lotes){
                
                $sheet->row(1, [
                    'Etapa de servicios', 'Fraccionamiento', 'Manzana', 'Num. Lote', 'Duplex', 'Calle',
                    'Num. Oficial', 'Interior', 'Superficie de terreno','Clave catastral'
                ]);


                $sheet->cells('A1:J1', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(13);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                
                $cont=1;

                $sheet->setColumnFormat(array(
                    'J' => '0'
                ));

                foreach($lotes as $index => $lote) {
                    $cont++;       

                    $sheet->row($index+2, [
                        $lote->etapa_servicios, 
                        $lote->proyecto, 
                        $lote->manzana, 
                        $lote->num_lote, 
                        $lote->sublote,
                        $lote->calle,
                        $lote->numero_oficial,
                        $lote->interior,
                        $lote->terreno,
                        $lote->clv_catastral,
                    ]);	
                }
                $num='A1:J' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    public function indexLotesDisponibles (Request $request)
    {
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        $rolId =  $request->rolId;
        $b_modelo = $request->b_modelo ;
        $b_lote = $request->b_lote;
        $b_apartado = $request->b_apartado;

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->leftJoin('apartados','lotes.id','apartados.lote_id')
            ->leftJoin('clientes','apartados.cliente_id','clientes.id')
            ->leftJoin('vendedores','apartados.vendedor_id','vendedores.id')
            ->leftJoin('personal','clientes.id','personal.id')
            ->leftJoin('personal as v','vendedores.id','v.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance','lotes.extra','lotes.extra_ext',
                        'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste','lotes.excedente_terreno','lotes.apartado','lotes.obra_extra','lotes.fecha_termino_ventas',
                        'personal.nombre as c_nombre', 'personal.apellidos as c_apellidos', 'lotes.emp_constructora', 'lotes.emp_terreno',
                        'v.nombre as v_nombre', 'apartados.fecha_apartado');

        $queryVendedores = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                    'modelos.nombre as modelo','lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                    'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                    'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance','lotes.extra','lotes.extra_ext',
                    'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste', 'lotes.emp_constructora', 'lotes.emp_terreno',
                    'lotes.excedente_terreno','lotes.apartado','lotes.obra_extra','lotes.fecha_termino_ventas');

                    // if($request->casa_muestra == 1){
                    //     $query = $query->where('lotes.casa_muestra','=',1);
                    //     $queryVendedores = $queryVendedores->where('lotes.casa_muestra','=',1);
                    // } 

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || $rolId == 8 || $rolId == 11 ){
                 
            $lotes = $query
                        ->where('lotes.habilitado','=',1)
                        ->where('lotes.contrato','=',0);

            if($b_apartado != ''){
                if($b_apartado == 0)
                    $lotes = $lotes->where('lotes.apartado','=',$b_apartado);
                elseif($b_apartado == 1)
                    $lotes  = $lotes->where('lotes.apartado','!=',0);
            }
            

            if($buscar != '')
                $lotes = $lotes->where($criterio, 'like', '%'.$buscar.'%');
            if($b_modelo != '')
                $lotes = $lotes->where('modelos.id', '=', $b_modelo );
            if($buscar2 != '')
                $lotes = $lotes->where('lotes.etapa_id', 'like', '%'.$buscar2.'%');
            if($buscar3 != '')
                $lotes = $lotes->where('lotes.manzana', 'like', '%'.$buscar3.'%');
            if($b_lote != '')
                $lotes = $lotes->where('lotes.num_lote', 'like', '%'.$b_lote.'%');
        

            
        }
        else{
            
                $lotes = $queryVendedores
                            ->where('lotes.habilitado','=',1)
                            ->where('lotes.apartado','=',0)
                            ->where('lotes.contrato','=',0);

                            
                    if($buscar != '')
                        $lotes = $lotes->where($criterio, 'like', '%'. $buscar . '%');
                    if($b_modelo != '')
                        $lotes = $lotes->where('modelos.id', '=', $b_modelo );
                    if($buscar2 != '')
                        $lotes = $lotes->where('lotes.etapa_id', 'like', '%'. $buscar2 . '%');
                    if($buscar3 != '')
                        $lotes = $lotes->where('lotes.manzana', 'like', '%'. $buscar3 . '%');
                    if($b_lote != '')
                        $lotes = $lotes->where('lotes.num_lote', 'like', '%'. $b_lote . '%');
            
        }


        if($request->casa_muestra != ''){
            $lotes = $lotes->where('lotes.casa_muestra','=',$request->casa_muestra);
            //$queryVendedores = $queryVendedores->where('lotes.casa_muestra','=',1);
        }

        if($request->b_empresa != ''){
            $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);
        }        

        $lotes = $lotes->orderBy('fraccionamientos.nombre','DESC')
                    ->orderBy('etapas.num_etapa','ASC')
                    ->orderBy('lotes.manzana','ASC')
                    ->orderBy('lotes.num_lote','ASC');
                    
        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 4)
            $lotes = $lotes->paginate(25);  
        else
            $lotes = $lotes->paginate(8);  
        
        $tipo = 2;
        if(Auth::user()->rol_id == 2)
        {
            $vendedor = Vendedor::findOrFail(Auth::user()->id);
            $tipo = $vendedor->tipo;
        }

        
        
        
        foreach($lotes as $index => $lote) {
            
            $lote->precio_base = $lote->precio_base + $lote->ajuste;
            $lote->tipo = $tipo;
            $lote->precio_venta= $lote->sobreprecio + $lote->precio_base + $lote->excedente_terreno + $lote->obra_extra;
            $promocion=[];
            $promocion = Lote_promocion::join('promociones','lotes_promocion.promocion_id','=','promociones.id')
                ->select('promociones.nombre','promociones.v_ini','promociones.v_fin','promociones.id')
                ->where('lotes_promocion.lote_id','=',$lote->id)
                ->where('promociones.v_fin','>=',Carbon::today()->format('ymd'))->get();
            if(sizeof($promocion) > 0){
                $lote->v_iniPromo = $promocion[0]->v_ini;
                $lote->v_finPromo = $promocion[0]->v_fin;
                $lote->promocion = $promocion[0]->nombre;
            }
            else
                $lote->promocion = 'Sin Promoción';
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

    public function select_etapas_disp(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        
        $fraccionamiento = $request->buscar;
        $lotes_etapas = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
                    ->select('etapas.num_etapa as etapa')
                    ->where('lotes.habilitado','=',1)
                    ->where('lotes.apartado','=',0)
                    ->where('lotes.contrato','=',0)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orWhere('lotes.habilitado','=',1)
                    ->where('apartados.vendedor_id','=',Auth::user()->id)
                    ->where('lotes.contrato','=',0)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orderBy('etapas.num_etapa','DESC')
                    ->distinct()
                    ->get();
        return ['lotes_etapas' => $lotes_etapas];
    }

    public function select_manzanas_disp(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        
        $etapa = $request->buscar;
        $lotes_manzanas = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
                    ->select('lotes.manzana')
                    ->where('lotes.habilitado','=',1)
                    ->where('lotes.apartado','=',0)
                    ->where('lotes.contrato','=',0)
                    ->where('etapas.num_etapa','=',$etapa)
                    ->orWhere('lotes.habilitado','=',1)
                    ->where('apartados.vendedor_id','=',Auth::user()->id)
                    ->where('lotes.contrato','=',0)
                    ->where('etapas.num_etapa','=',$etapa)
                    ->distinct()
                    ->orderBy('lotes.manzana','DESC')
                    ->get();
        return ['lotes_manzanas' => $lotes_manzanas];
    }

    public function select_lotes_disp(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        
        $manzana = $request->buscar;
        $etapa = $request->buscar2;
        $fraccionamiento = $request->buscar3;
        if(Auth::user()->id == 2){
            $lotes_disp = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
                    ->select('lotes.num_lote','lotes.id')
                    ->where('lotes.habilitado','=',1)
                    ->where('lotes.apartado','=',0)
                    ->where('lotes.contrato','=',0)
                    ->where('etapas.num_etapa', 'like', '%'. $etapa .'%' )
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orWhere('lotes.habilitado','=',1)
                    ->where('apartados.vendedor_id','=',Auth::user()->id)
                    ->where('lotes.contrato','=',0)
                    ->where('etapas.num_etapa', 'like', '%'. $etapa .'%' )
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orderBy('lotes.num_lote','ASC')
                    ->get();
        }
        else{
            $lotes_disp = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
                    ->select('lotes.num_lote','lotes.id')
                    ->where('lotes.habilitado','=',1)
                    ->where('lotes.contrato','=',0)
                    ->where('etapas.num_etapa', 'like', '%'. $etapa .'%' )
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    
                    ->orderBy('lotes.num_lote','ASC')
                    ->get();
        }
        
        return ['lotes_disp' => $lotes_disp];
    }

    public function select_datos_lotes_disp(Request $request){

        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
        ->join('licencias','lotes.id','=','licencias.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->join('modelos','lotes.modelo_id','=','modelos.id')
        ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                    'modelos.nombre as modelo','lotes.calle','lotes.numero','lotes.interior','lotes.terreno','lotes.obra_extra',
                    'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                    'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance','lotes.extra','lotes.extra_ext',
                    'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste','lotes.excedente_terreno','lotes.apartado','modelos.terreno as terreno_modelo')
                    ->where('lotes.id','=',$buscar)
                    ->orderBy('fraccionamientos.nombre','DESC')
                    ->orderBy('lotes.etapa_servicios','DESC')->get();

        foreach($lotes as $index => $lote) {
            $lote->precio_base = $lote->precio_base + $lote->ajuste;
            $lote->precio_venta= $lote->sobreprecio + $lote->precio_base + $lote->excedente_terreno + $lote->obra_extra;
            $promocion=[];
            $promocion = Lote_promocion::join('promociones','lotes_promocion.promocion_id','=','promociones.id')
                ->select('promociones.nombre','promociones.v_ini','promociones.v_fin','promociones.id',
                        'promociones.descuento','promociones.descripcion')
                ->where('lotes_promocion.lote_id','=',$lote->id)
                ->where('promociones.v_fin','>=',Carbon::today()->format('ymd'))->get();
            if(sizeof($promocion) > 0){
                $lote->v_iniPromo = $promocion[0]->v_ini;
                $lote->v_finPromo = $promocion[0]->v_fin;
                $lote->promocion = $promocion[0]->nombre;
                $lote->descripcionPromo = $promocion[0]->descripcion;
                $lote->descuentoPromo = $promocion[0]->descuento;
            }
            else{
                $lote->promocion = 'Sin Promoción';
                $lote->descripcionPromo = '';
                $lote->descuentoPromo = 0;
                }
            
            $lote->terreno_tam_excedente = $lote->terreno - $lote->terreno_modelo;
            
        }

        return ['lotes' => $lotes];

    }


    public function exportExcelLotesDisp(Request $request)
    {
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        $rolId =  $request->rolId;
        $b_modelo = $request->b_modelo ;
        $b_lote = $request->b_lote;
        $b_apartado = $request->b_apartado;

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->leftJoin('apartados','lotes.id','apartados.lote_id')
            ->leftJoin('clientes','apartados.cliente_id','clientes.id')
            ->leftJoin('vendedores','apartados.vendedor_id','vendedores.id')
            ->leftJoin('personal','clientes.id','personal.id')
            ->leftJoin('personal as v','vendedores.id','v.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                    'modelos.nombre as modelo','lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                    'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                    'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance',
                    'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste','lotes.excedente_terreno','lotes.apartado','lotes.obra_extra','lotes.fecha_termino_ventas',
                    'personal.nombre as c_nombre', 'personal.apellidos as c_apellidos', 'v.nombre as v_nombre', 'apartados.fecha_apartado');

        $queryVendedores = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->leftJoin('apartados','lotes.id','=','apartados.lote_id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapa','lotes.manzana','lotes.num_lote','lotes.sublote',
                    'modelos.nombre as modelo','lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                    'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id','lotes.fecha_fin',
                    'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios','licencias.avance',
                    'lotes.sobreprecio', 'lotes.precio_base','lotes.ajuste','lotes.excedente_terreno','lotes.apartado','lotes.obra_extra','lotes.fecha_termino_ventas');

                    

                    if($rolId == 1 || $rolId == 4 || $rolId == 6 || $rolId == 8 || $rolId == 11 ){
                 
                        $lotes = $query
                                    ->where('lotes.habilitado','=',1)
                                    ->where('lotes.contrato','=',0);
            
                        if($b_apartado != ''){
                            if($b_apartado == 0)
                                $lotes = $lotes->where('lotes.apartado','=',$b_apartado);
                            elseif($b_apartado == 1)
                                $lotes  = $lotes->where('lotes.apartado','!=',0);
                        }
                        
            
                        if($buscar != '')
                            $lotes = $lotes->where($criterio, 'like', '%'.$buscar.'%');
                        if($b_modelo != '')
                            $lotes = $lotes->where('modelos.id', '=', $b_modelo );
                        if($buscar2 != '')
                            $lotes = $lotes->where('lotes.etapa_id', 'like', '%'.$buscar2.'%');
                        if($buscar3 != '')
                            $lotes = $lotes->where('lotes.manzana', 'like', '%'.$buscar3.'%');
                        if($b_lote != '')
                            $lotes = $lotes->where('lotes.num_lote', 'like', '%'.$b_lote.'%');
                    
            
                        
                    }
                    else{
                        
                            $lotes = $queryVendedores
                                        ->where('lotes.habilitado','=',1)
                                        ->where('lotes.apartado','=',0)
                                        ->where('lotes.contrato','=',0);
            
                                        
                                if($buscar != '')
                                    $lotes = $lotes->where($criterio, 'like', '%'. $buscar . '%');
                                if($b_modelo != '')
                                    $lotes = $lotes->where('modelos.id', '=', $b_modelo );
                                if($buscar2 != '')
                                    $lotes = $lotes->where('lotes.etapa_id', 'like', '%'. $buscar2 . '%');
                                if($buscar3 != '')
                                    $lotes = $lotes->where('lotes.manzana', 'like', '%'. $buscar3 . '%');
                                if($b_lote != '')
                                    $lotes = $lotes->where('lotes.num_lote', 'like', '%'. $b_lote . '%');
                        
                    }
        

        if($request->casa_muestra != ''){
            $lotes = $lotes->where('lotes.casa_muestra','=',$request->casa_muestra);
            //$queryVendedores = $queryVendedores->where('lotes.casa_muestra','=',1);
        }

        if($request->b_empresa != ''){
            $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);
        }

            $lotes = $lotes->orderBy('fraccionamientos.nombre','DESC')
                    ->orderBy('etapas.num_etapa','ASC')
                    ->orderBy('lotes.manzana','ASC')
                    ->orderBy('lotes.num_lote','ASC')->get(); 

                   
            return Excel::create('Relacion lotes disponibles', function($excel) use ($lotes){
                $excel->sheet('lotes', function($sheet) use ($lotes){
                    
                    $sheet->row(1, [
                        'Proyecto', 'Etapa' ,'Manzana', '# Lote', '% Avance', 'Modelo', 'Calle',
                        '# Oficial', 'Terreno', 'Construccion','Precio base','Terreno excedente','Obra extra',
                        'Sobreprecios','Precio venta','Promocion','Fecha de termino', 'Canal de venta'
                    ]);


                    $sheet->cells('A1:R1', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(13);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    
                    $cont=1;
                    
                    $sheet->setColumnFormat(array(
                        'K' => '$#,##0.00',
                        'L' => '$#,##0.00',
                        'M' => '$#,##0.00',
                        'N' => '$#,##0.00',
                        'O' => '$#,##0.00',
                    ));

                    

                    foreach($lotes as $index => $lote) {
                        if($lote->fecha_termino_ventas == NULL){
                            $lote->fecha_termino_ventas = 'Por definir';
                        }else{
                        setlocale(LC_TIME,'es_MX.utf8');
                        $mesAño = new Carbon($lote->fecha_termino_ventas);
                        $lote->fecha_termino_ventas = $mesAño->formatLocalized('%B %Y');
                        }
                        if($lote->casa_muestra == 1){
                            $casaMuestra = 'Casa muestra';
                            
                            
                        }else{
                            $casaMuestra = '';
                        }
                        
                        $lote->precio_base = $lote->precio_base + $lote->ajuste;
                        $lote->precio_venta= $lote->sobreprecio + $lote->precio_base + $lote->excedente_terreno + $lote->obra_extra;
                        $promocion=[];
                        $promocion = Lote_promocion::join('promociones','lotes_promocion.promocion_id','=','promociones.id')
                            ->select('promociones.nombre','promociones.v_ini','promociones.v_fin','promociones.id')
                            ->where('lotes_promocion.lote_id','=',$lote->id)
                            ->where('promociones.v_fin','>=',Carbon::today()->format('ymd'))->get();
                        if(sizeof($promocion) > 0){
                            // $lote->v_iniPromo = $promocion[0]->v_ini;
                            // $lote->v_finPromo = $promocion[0]->v_fin;
                            $lote->promocion = $promocion[0]->nombre;
                        }
                        else
                            $lote->promocion = 'Sin Promoción';


                        $cont++; 
                        if($lote->sublote !=''){
                            $loteConSublote = $lote->num_lote.'-'.$lote->sublote;
                        } else{
                            $loteConSublote = $lote->num_lote;
                        }
                        if($lote->interior !=''){
                            $loteConInterior = $lote->numero.'-'.$lote->interior;
                        } else{
                            $loteConInterior = $lote->numero;
                        }
                        
                        $sheet->row($index+2, [
                            $lote->proyecto, 
                            $lote->etapa,
                            $lote->manzana,
                            $loteConSublote, 
                            $lote->avance, 
                            $lote->modelo, 
                            $lote->calle,
                            $loteConInterior,
                            $lote->terreno,
                            $lote->construccion,
                            $lote->precio_base,
                            $lote->excedente_terreno,
                            $lote->obra_extra,
                            $lote->sobreprecio,
                            $lote->precio_venta,
                            $lote->promocion,
                            $lote->fecha_termino_ventas,
                            $lote->comentarios,
                            $casaMuestra
                           
                        ]);	
                    }


                    $num='A1:R' . $cont;
                    $sheet->setBorder($num, 'thin');
                    $sheet->cells('S1:S'.$cont, function($cells) {

                        
                        $cells->setFontColor('#ff4040');
                    
                    });
                });
            }
            
            )->download('xls');
        
      
    }

    public function LotesConPrecioBase(Request $request){

        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $modelo = $request->modelo;
        $criterio = $request->criterio;

        $lotes = $this->getPreciosBase($buscar,$b_etapa,$b_manzana,$b_lote,$modelo,$criterio);
            $lotes = $lotes->paginate(8);

        if(sizeof($lotes)){
            foreach ($lotes as $index => $lote) {
                $contrato = Contrato    ::  join('creditos','contratos.id','=','creditos.id')
                                            ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                            ->join('expedientes','contratos.id','=','expedientes.id')
                                            ->select('contratos.id')
                                            ->where('creditos.lote_id','=',$lote->id)
                                            ->where('inst_seleccionadas.elegido','=',1)
                                            ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                            ->where('contratos.status','=',3)
                                            ->where('expedientes.liquidado','=',1)
                                            ->get();
                
                if(sizeof($contrato))
                    $lote->firmado = 1;
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

    public function excelPrecioBase(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $modelo = $request->modelo;
        $criterio = $request->criterio;

        $lotes = $this->getPreciosBase($buscar,$b_etapa,$b_manzana,$b_lote,$modelo,$criterio);
            $lotes = $lotes->get();

        return Excel::create('lotes', function($excel) use ($lotes){
                $excel->sheet('lotes', function($sheet) use ($lotes){
                    
                    $sheet->row(1, [
                        'Proyecto', 'Etapa', 'Manzana', 'Lote', 'Modelo', 'Avance', 'Status',
                        'Precio base', 'Ajuste'
                    ]);
    
    
                    $sheet->cells('A1:I1', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Calibri');
    
                        // Set font size
                        $cells->setFontSize(13);
    
                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $sheet->setColumnFormat(array(
                        'H' => '$#,##0.00',
                        'I' => '$#,##0.00',
                    ));
    
                    
                    $cont=1;
    
                    foreach($lotes as $index => $lote) {
                        $cont++;

                        if($lote->contrato == 0)
                            $lote->status = 'Disponible';
                        elseif($lote->contrato == 1 && $lote->firmado == 0)
                            $lote->status = 'Vendida';
                        else
                            $lote->status = 'Individualizada';
                        
                        $sheet->row($index+2, [
                            $lote->proyecto, 
                            $lote->num_etapa, 
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->modelo, 
                            $lote->avance.'%',
                            $lote->status,
                            $lote->precio_base,
                            $lote->ajuste,
                        ]);	
                    }
                    $num='A1:I' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }
            
            )->download('xls');
    }

    private function getPreciosBase($buscar,$b_etapa,$b_manzana,$b_lote,$modelo,$criterio){
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->select('lotes.id','lotes.num_lote','lotes.sublote','lotes.precio_base',
                        'lotes.manzana','licencias.avance',
                        'lotes.contrato','lotes.firmado',
                        'lotes.ajuste','fraccionamientos.nombre as proyecto',
                        'etapas.num_etapa','modelos.nombre as modelo');

            if($buscar != '')
                $lotes = $lotes->where($criterio, 'like', '%'. $buscar . '%');
            
            if($b_etapa != '')
                $lotes = $lotes->where('etapas.num_etapa', 'like', '%'. $b_etapa . '%');

            if($b_manzana !='')
                $lotes = $lotes->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
            
            if($b_lote !='')
                $lotes = $lotes->where('lotes.num_lote', '=', $b_lote);
            
            if($modelo != '')
                $lotes = $lotes->where('modelos.nombre', 'like', '%'. $modelo . '%');

        $lotes=$lotes
            ->where('lotes.precio_base','>','0')
            ->orderBy('fraccionamientos.nombre','ASC')
            ->orderBy('etapas.num_etapa','ASC')
            ->orderBy('lotes.num_lote','ASC');

        return $lotes;
    }

    public function updateAjuste(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $ajuste = Lote::findOrFail($request->id);
        $cambio = $ajuste->ajuste;
        $ajuste->ajuste = $request->ajuste;
        $ajuste->save();

        $creditos = Credito::select('id')
        ->where('contrato','=',0)
        ->where('lote_id','=',$request->id)->get();
        foreach($creditos as $creditosid){
            
            $credito = Credito::findOrFail($creditosid->id);
            $credito->precio_venta = $credito->precio_venta + $request->ajuste - $cambio;
            $credito->precio_base = $credito->precio_base + $request->ajuste - $cambio;
            $credito->save();
        }
    }

    public function exportExcelAsignarModelo(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $b_modelo = $request->bmodelo;
        $b_lote = $request->blote;
        $b_habilitado = $request->b_habilitado;
        $criterio = $request->criterio;
        $b_puente = $request->b_puente;

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')->join('licencias','lotes.id','=','licencias.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                  'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                  'lotes.construccion','lotes.casa_muestra','lotes.habilitado','lotes.lote_comercial','lotes.id',
                  'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios', 'lotes.contrato', 'lotes.firmado',
                  'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.etapa_servicios',
                  'lotes.regimen_condom','licencias.avance');
        
                  if($b_habilitado != '')
                  $query = $query->where('lotes.habilitado','=', $b_habilitado);
      
                  $lotes = $query;
                  if($buscar != '')
                      $lotes = $lotes->where($criterio, 'like', '%'. $buscar . '%');
                  if($b_puente != '')
                      $lotes = $lotes->where('lotes.credito_puente','=',$b_puente);
                  if($b_modelo != '')
                      $lotes = $lotes->where('modelos.id', '=', $b_modelo );
                  if($buscar2 != '')
                      $lotes = $lotes->where('lotes.etapa_id', 'like', '%'. $buscar2 . '%');
                  if($buscar3 != '')
                      $lotes = $lotes->where('lotes.manzana', '=',$buscar3);
                  if($b_lote != '')
                      $lotes = $lotes->where('lotes.num_lote', '=',$b_lote);
                  
      
              if($request->b_empresa != ''){
                  $lotes= $lotes->where('lotes.emp_constructora','=',$request->b_empresa);
              }
      
              if($request->b_empresa2 != ''){
                  $lotes= $lotes->where('lotes.emp_terreno','=',$request->b_empresa2);
              }

        $lotes = $lotes->orderBy('fraccionamientos.nombre','ASC')
                        ->orderBy('etapas.num_etapa','ASC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')
                        ->orderBy('lotes.etapa_servicios','ASC')->get();

        if(sizeOf($lotes)){
            foreach($lotes as $index => $lote){
                $lote->indiv = 0;
                $expedientes = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                            ->join('creditos','creditos.id','=','contratos.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                            ->where('creditos.lote_id','=',$lote->id)
                                            ->where('inst_seleccionadas.elegido','=',1)
                                            ->where('expedientes.liquidado','=',1)
                                            ->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                            ->where('creditos.lote_id','=',$lote->id)
                                            ->where('inst_seleccionadas.elegido','=',1)
                                            ->where('expedientes.fecha_firma_esc','!=',NULL)->get();

                if(sizeOf($expedientes)){
                    $lote->indiv = 1;
                }
            }
            

        }
        
        return Excel::create('lotes', function($excel) use ($lotes){
            $excel->sheet('lotes', function($sheet) use ($lotes){
                
                $sheet->row(1, [
                    'Proyecto', 'Etapa comercial', 'Etapa de servicio', 'Manzana', 'Lote', 'Modelo',
                    'Calle','Numero','Terreno', 'Construcción', 'Credito puente', 'Avance','Casa en Venta','Status','Canal de ventas' 
                ]);


                $sheet->cells('A1:O1', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(13);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                
                $cont=1;

                foreach($lotes as $index => $lote) {
                    $cont++;

                    $status = 'Disponible';

                    if($lote->contrato == 1){
                        $status = 'Vendida';
                    }

                    if($lote->indiv == 1){
                        $status = 'Individualizada';
                    }

                    if($lote->sublote !=''){
                        $loteConSublote = $lote->num_lote.'-'.$lote->sublote;
                    } else{
                        $loteConSublote = $lote->num_lote;
                    }
                    if($lote->interior !=''){
                        $loteConInterior = $lote->numero.'-'.$lote->interior;
                    } else{
                        $loteConInterior = $lote->numero;
                    }
                    if($lote->casa_muestra==0 && $lote->lote_comercial==0 && $lote->habilitado==1){
                        $casaenventa = 'Habilitada';
                    }else{
                        $casaenventa = 'Deshabilitada';
                    }
                    $sheet->row($index+2, [
                        $lote->proyecto, 
                        $lote->etapas, 
                        $lote->etapa_servicios, 
                        $lote->manzana,
                        $loteConSublote,
                        $lote->modelo, 
                        $lote->calle,
                        $loteConInterior,
                        $lote->terreno,
                        $lote->construccion,
                        $lote->credito_puente,
                        $lote->avance.'%',
                        $casaenventa,
                        $status,
                        $lote->comentarios,
                        
                    ]);	
                }
                $num='A1:O' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    public function select_etapas_entregados(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        
        $fraccionamiento = $request->buscar;
        $lotes_etapas = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('creditos','lotes.id','=','creditos.lote_id')
                    ->join('contratos','creditos.id','=','contratos.id')
                    ->select('etapas.num_etapa as etapa')
                    ->where('contratos.entregado','=',1)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orderBy('etapas.num_etapa','DESC')
                    ->distinct()
                    ->get();
        return ['lotes_etapas' => $lotes_etapas];
    }

    public function select_manzanas_entregados(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        
        $etapa = $request->buscar;
        $lotes_manzanas = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('creditos','lotes.id','=','creditos.lote_id')
                    ->join('contratos','creditos.id','=','contratos.id')
                    ->select('lotes.manzana')
                    ->where('contratos.entregado','=',1)
                    ->where('etapas.num_etapa','=',$etapa)
                    ->distinct()
                    ->orderBy('lotes.manzana','DESC')
                    ->get();
        return ['lotes_manzanas' => $lotes_manzanas];
    }

    public function select_lotes_entregados(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        
        $manzana = $request->buscar;
        $etapa = $request->buscar2;
        $fraccionamiento = $request->buscar3;
        
            $lotes_entregados = Lote::join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('creditos','lotes.id','=','creditos.lote_id')
                    ->join('contratos','creditos.id','=','contratos.id')
                    ->select('lotes.num_lote','lotes.id')
                    ->where('contratos.entregado','=',1)
                    ->where('etapas.num_etapa', '=',  $etapa )
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                    ->orderBy('lotes.num_lote','ASC')
                    ->get();
        
        return ['lotes_entregados' => $lotes_entregados];
    }

    public function asignarEmpresa(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lote = Lote::findOrFail($request->id);
        $lote->emp_constructora = $request->emp_constructora;
        $lote->emp_terreno = $request->emp_terreno;
        $lote->save();
    }

    public function selectEmpresaConstructora(Request $request){
        if(!$request->ajax())return redirect('/');
        $empresas = ['Grupo Constructor Cumbres','CONCRETANIA'];

        return ['empresas'=>$empresas];
    }


}
