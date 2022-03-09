<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NotifyAdmin;
use App\Fraccionamiento; //Importar el modelo
use App\Historial_escritura;
use App\Historial_plano;
use App\Etapa;
use App\Modelo;
use App\Lote;
use App\User;
use Carbon\Carbon;
use Excel;
use File;
use Auth;
use DB;

class FraccionamientoController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $fraccionamientos = Fraccionamiento::leftJoin('personal','fraccionamientos.gerente_id','=','personal.id')
                                            ->leftJoin('personal as p','fraccionamientos.arquitecto_id','=','p.id')
                    ->select('fraccionamientos.*',
                                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) as gerente"),
                                DB::raw("CONCAT(p.nombre,' ',p.apellidos) as arquitecto")
                            )
                    ->where('fraccionamientos.id','!=','1')->orderBy('fraccionamientos.id','desc')->paginate(8);
        }
        else{
            $fraccionamientos = Fraccionamiento::leftJoin('personal','fraccionamientos.gerente_id','=','personal.id')
                                            ->leftJoin('personal as p','fraccionamientos.arquitecto_id','=','p.id')
                    ->select('fraccionamientos.*',
                                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) as gerente"),
                                DB::raw("CONCAT(p.nombre,' ',p.apellidos) as arquitecto")
                            )
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->where('fraccionamientos.id','!=','1')
                    ->orderBy('fraccionamientos.id','desc')->paginate(8);
        }

        return [
            'pagination' => [
                'total'         => $fraccionamientos->total(),
                'current_page'  => $fraccionamientos->currentPage(),
                'per_page'      => $fraccionamientos->perPage(),
                'last_page'     => $fraccionamientos->lastPage(),
                'from'          => $fraccionamientos->firstItem(),
                'to'            => $fraccionamientos->lastItem(),
            ],
            'fraccionamientos' => $fraccionamientos
        ];
    }

    public function excelIndex(Request $request)
    {

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $fraccionamientos = Fraccionamiento::where('id','!=','1')->orderBy('id','desc')->get();
        }
        else{
            $fraccionamientos = Fraccionamiento::where($criterio, 'like', '%'. $buscar . '%')
                                                ->where('id','!=','1')
                                                ->orderBy('id','desc')->get();
        }

        return Excel::create('Fraccionamientos', function($excel) use ($fraccionamientos){
            $excel->sheet('Fraccionamientos', function($sheet) use ($fraccionamientos){
                
                $sheet->row(1, [
                    'Fraccionamiento','Tipo de proyecto','Direccion','Colonia','Estado','Delegación'
                ]);

                $sheet->cells('A1:F1', function ($cells) {
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

                foreach($fraccionamientos as $index => $proyecto) {
                    $cont++;

                    if($proyecto->tipo_proyecto == 1) $proyecto->tipo_proyecto = 'Lotificación';
                    elseif($proyecto->tipo_proyecto == 2) $proyecto->tipo_proyecto = 'Departamento';
                    else $proyecto->tipo_proyecto = 'Terreno';
                         

                    $sheet->row($index+2, [
                        $proyecto->nombre, 
                        $proyecto->tipo_proyecto,
                        $proyecto->calle,
                        $proyecto->colonia,
                        $proyecto->estado,
                        $proyecto->delegacion

                    ]);	
                }
                $num='A1:F' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }
   
    //funcion para insertar en la tabla
    public function store(Request $request)
    {

        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $proyecto = $request->nombre;
            $usuario_id = Auth::user()->id;
            $fraccionamiento = new Fraccionamiento();
            $fraccionamiento->nombre = $proyecto;
            $fraccionamiento->numero = $request->numero;
            $fraccionamiento->tipo_proyecto = $request->tipo_proyecto;
            $fraccionamiento->calle = $request->calle;
            $fraccionamiento->colonia = $request->colonia;
            $fraccionamiento->estado = $request->estado;
            $fraccionamiento->ciudad = $request->ciudad;
            $fraccionamiento->delegacion = $request->delegacion;
            $fraccionamiento->cp = $request->cp;
            $fraccionamiento->save();

            
            $etapa = new Etapa();
            $etapa->fraccionamiento_id = $fraccionamiento->id;
            $etapa->num_etapa = "Sin Asignar";
            
            $etapa->personal_id = 1;
            $etapa->save();

            $modelo = new Modelo();
            $modelo->nombre = "Por Asignar";
            $modelo->fraccionamiento_id = $fraccionamiento->id;
            $modelo->tipo = $fraccionamiento->tipo_proyecto;
            $modelo->terreno = 0;
            $modelo->construccion = 0;
            $modelo->save();

            $modelo = new Modelo();
            $modelo->nombre = "Terreno";
            $modelo->fraccionamiento_id = $fraccionamiento->id;
            $modelo->tipo = $fraccionamiento->tipo_proyecto;
            $modelo->terreno = 0;
            $modelo->construccion = 0;
            $modelo->save();

            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',$usuario_id)->get();
            $fecha = Carbon::now();
            $arregloSimPendientes = [
                'notificacion' => [
                    'usuario' => $imagenUsuario[0]->usuario,
                    'foto' => $imagenUsuario[0]->foto_user,
                    'fecha' => $fecha,
                    'msj' => 'Se ha agregado el proyecto '. $proyecto,
                    'titulo' => 'Nuevo Proyecto'
                ]
            ];

            $users = User::select('id')->where('rol_id','=','3')->get();

            foreach($users as $notificar){
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloSimPendientes));
            }
            DB::commit();
                
        } catch (Exception $e){
            DB::rollBack();
        }

    }

    //funcion para actualizar los datos
    public function update(Request $request)
    {

        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $proyecto = $request->nombre;
        $usuario_id = Auth::user()->id;
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $fraccionamiento = Fraccionamiento::findOrFail($request->id);
        $fraccionamiento->nombre = $proyecto;
        $fraccionamiento->tipo_proyecto = $request->tipo_proyecto;
        $fraccionamiento->calle = $request->calle;
        $fraccionamiento->colonia = $request->colonia;
        $fraccionamiento->numero = $request->numero;
        $fraccionamiento->estado = $request->estado;
        $fraccionamiento->ciudad = $request->ciudad;
        $fraccionamiento->delegacion = $request->delegacion;
        $fraccionamiento->cp = $request->cp;
        $fraccionamiento->fecha_ini_venta = $request->fecha_ini_venta;
        if($request->gerente_id != ''){
            $fraccionamiento->gerente_id = $request->gerente_id;
        }
        if($request->arquitecto_id != ''){
            $fraccionamiento->arquitecto_id = $request->arquitecto_id;
        }
        $fraccionamiento->save();

        $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=', $usuario_id)->get();
        $fecha = Carbon::now();
        $arregloSimPendientes = [
            'notificacion' => [
                'usuario' => $imagenUsuario[0]->usuario,
                'foto' => $imagenUsuario[0]->foto_user,
                'fecha' => $fecha,
                'msj' => 'Se han modificado los datos del proyecto '. $proyecto,
                'titulo' => 'Modificación'
            ]
        ];

        $users = User::select('id')->where('rol_id','=','3')->get();

        foreach($users as $notificar){
            User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloSimPendientes));
        }
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fraccionamiento = Fraccionamiento::findOrFail($request->id);

        $contadorModelo=Modelo::where('fraccionamiento_id','=',$fraccionamiento->id)->get()->count();
        $contadorEtapa=Etapa::where('fraccionamiento_id','=',$fraccionamiento->id)->get()->count();

        if($contadorEtapa == 1 && $contadorModelo == 1){
            $modelo=Modelo::select('id')
                ->where('nombre','=','Por Asignar')
                ->where('fraccionamiento_id','=',$fraccionamiento->id)->get();
            $modeloDelete = Modelo::findOrFail($modelo[0]->id);
            $modeloDelete->delete();

            $etapa=Etapa::select('id')
                ->where('num_etapa','=','Sin Asignar')
                ->where('fraccionamiento_id','=',$fraccionamiento->id)->get();
            $etapaDelete = Etapa::findOrFail($etapa[0]->id);
            $etapaDelete->delete();
        }

        $fraccionamiento->delete();
           
  
    }

    public function selectFraccionamiento(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        
        $fraccionamientos = Fraccionamiento::select('nombre','id')->where('id','!=','1');

        if($request->tipo_proyecto != ''){
            $fraccionamientos = $fraccionamientos->where('tipo_proyecto','=',$request->tipo_proyecto);
        }
        
        $fraccionamientos = $fraccionamientos->orderBy('nombre','asc')->get();
        return['fraccionamientos' => $fraccionamientos];
    }

    public function selectFraccionamientoConInventario(Request $request){
        //if(!$request->ajax())return redirect('/');

        $fraccionamientos = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('fraccionamientos.nombre','fraccionamientos.id')
                    ->where('lotes.habilitado','=',1)
                    ->where('lotes.contrato','=',0);
                    //->where('lotes.rentado','=',0)

                    if($request->tipo_proyecto != ''){
                        $fraccionamientos = $fraccionamientos->where('fraccionamientos.tipo_proyecto','=',$request->tipo_proyecto);
                    }
                    
                    $fraccionamientos = $fraccionamientos->orderBy('nombre','asc')
                    ->distinct()
                    ->get();
                    return['fraccionamientos' => $fraccionamientos];
    }

    public function getDatos(Request $request){
        if(!$request->ajax())return redirect('/');
        $datos = Fraccionamiento::where('id','=',$request->id)->first();
        return $datos;
    }

    public function selectFraccionamientoVue(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $filtro = $request->filtro;

        if($filtro==''){
            $fraccionamientos = Fraccionamiento::select('nombre','id')
            ->where('id','!=','1')->get();
        }
        else{
            $fraccionamientos = Fraccionamiento::select('nombre','id')
            ->where('nombre','like','%'.$filtro.'%')->where('id','!=','1')->get();
        }
        
        return['fraccionamientos' => $fraccionamientos];
    }

    public function selectFraccionamientoConLotes(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $fraccionamientos = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')->
        select('fraccionamientos.nombre','fraccionamientos.id')
        ->where('fraccionamientos.id','!=','1')
        ->groupBy('fraccionamientos.id')
        ->orderBy('nombre','asc')->get();
        return['fraccionamientos' => $fraccionamientos];
    }

    public function selectFrac_Tipo(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $fraccionamiento = Fraccionamiento::select('nombre','id')
        ->where('tipo_proyecto', '=', $buscar)
        ->where('id','!=','1')
        ->orderBy('nombre','asc')->get();
        return['fraccionamientos' => $fraccionamiento];
    }

    //funciones para carga y descarga de planos
    public function formSubmitPlanos(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $fileName = time().'.'.$request->archivo_planos->getClientOriginalExtension();
        $moved =  $request->archivo_planos->move(public_path('/files/fraccionamientos/planos'), $fileName);

        if($moved){
            $planos = Fraccionamiento::findOrFail($request->id);
            if( $planos->archivo_planos == NULL)
                $planos->archivo_planos = $fileName;
            else
                $this->almacenarPlano($request,$fileName);
            $planos->id = $id;
            $planos->save(); //Insert

            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
            $fecha = Carbon::now();
            $arregloSimPendientes = [
                'notificacion' => [
                    'usuario' => $imagenUsuario[0]->usuario,
                    'foto' => $imagenUsuario[0]->foto_user,
                    'fecha' => $fecha,
                    'msj' => 'Se han subido los planos para el proyecto '. $planos->nombre,
                    'titulo' => 'Nuevos planos agregados'
                ]
            ];

            $users = User::select('id')->where('rol_id','=','3')->get();
            foreach($users as $notificar){
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloSimPendientes));
            }
        }
        
        return response()->json(['success'=>'You have successfully upload file.']);
    }
 
    public function downloadFilePlanos($fileName){       
        $pathtoFile = public_path().'/files/fraccionamientos/planos/'.$fileName;
        return response()->download($pathtoFile);
    }

    //funciones para carga y descarga de archivos de escrituras
    public function formSubmitEscrituras(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $fileName = time().'.'.$request->archivo_escrituras->getClientOriginalExtension();
        $moved =  $request->archivo_escrituras->move(public_path('/files/fraccionamientos/escrituras'), $fileName);

        if($moved){
            $escrituras = Fraccionamiento::findOrFail($request->id);
            if( $escrituras->archivo_escrituras == NULL)
                $escrituras->archivo_escrituras = $fileName;
            else{
                $this->almacenarEscritura($request,$fileName);
            }
            $escrituras->save(); //Insert

            $imagenUsuario = DB::table('users')->select('foto_user','usuario')->where('id','=',Auth::user()->id)->get();
            $fecha = Carbon::now();
            $arregloSimPendientes = [
                'notificacion' => [
                    'usuario' => $imagenUsuario[0]->usuario,
                    'foto' => $imagenUsuario[0]->foto_user,
                    'fecha' => $fecha,
                    'msj' => 'Se han subido las escrituras para el proyecto '. $escrituras->nombre,
                    'titulo' => 'Nuevas escrituras agregados'
                ]
            ];

            $users = User::select('id')->where('rol_id','=','3')->get();

            foreach($users as $notificar){
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloSimPendientes));
            }

        }
        return response()->json(['success'=>'You have successfully upload file.']);
    }
  
    public function downloadFileEscrituras($fileName){
        
        $pathtoFile = public_path().'/files/fraccionamientos/escrituras/'.$fileName;
        return response()->download($pathtoFile);
    }

    //funciones para carga y descarga de los logos de los fraccionamientos
    public function formSubmitLogoFraccionamiento(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
 
        $fileName = time().'.'.$request->archivo_logo->getClientOriginalExtension();
        $moved =  $request->archivo_logo->move(public_path('/img/logosFraccionamientos/'), $fileName);

        if($moved){
            if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
            $logo = Fraccionamiento::findOrFail($request->id);
            $logo->logo_fracc = $fileName;
            $logo->id = $id;
            $logo->save(); //Insert
    
            }
    }
    public function downloadFileLogoFraccionamiento($fileName){
        
        $pathtoFile = public_path().'/img/logosFraccionamientos/'.$fileName;
        return response()->download($pathtoFile);
    }
   
    // Funcion privada para guardar los planos del fraccionamiento para el historial de versiones
    private function almacenarPlano(Request $request, $filename){
        $plano = new Historial_plano();
        $plano->fraccionamiento_id = $request->fraccionamiento_id;
        $plano->archivo = $filename;
        $plano->version = $request->version;
        $plano->save();
    }
    // Funcion privada para guardar las escrituras del fraccionamiento para el historial de versiones
    private function almacenarEscritura(Request $request, $filename){
        $escritura = new Historial_escritura();
        $escritura->fraccionamiento_id = $request->fraccionamiento_id;
        $escritura->archivo = $filename;
        $escritura->version = $request->version;
        $escritura->save();
    }
    // Funcion para retornar todos los archivos del fraccionamiento.
    public function getArchivos(Request $request){
        $escrituras = Historial_escritura::where('fraccionamiento_id','=',$request->id)->get();
        $planos = Historial_plano::where('fraccionamiento_id','=',$request->id)->get();

        return [
            'planos' => $planos,
            'escrituras' => $escrituras
        ];
    }
    // Funcion para eliminar la escritura del historial
    public function deleteEscrituras(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $pathAnterior = public_path().'/files/fraccionamientos/escrituras/'.$request->archivo;
        File::delete($pathAnterior);

        $escritura = Historial_escritura::findOrFail($request->id);
        $escritura->delete();
    }
    // Funcion para eliminar el plano del historial
    public function deletePlanos(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $pathAnterior = public_path().'/files/fraccionamientos/planos/'.$request->archivo;
        File::delete($pathAnterior);

        $plano = Historial_plano::findOrFail($request->id);
        $plano->delete();
    }
}
