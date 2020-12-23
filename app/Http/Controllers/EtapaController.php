<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Etapa; //Importar el modelo
use App\Precio_etapa; //Importar el modelo
use App\Sobreprecio_etapa; //Importar el modelo
use DB;
use File;
use App\Contrato;
use App\Sobreprecio;
use App\Modelo;
use Excel;
use Auth;

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
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;

        $query = Etapa::join('personal','etapas.personal_id','=','personal.id')
            ->join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
            ->select('etapas.num_etapa','etapas.f_ini',
                'etapas.f_fin','etapas.id','etapas.personal_id', 'etapas.fecha_ini_venta',
                'etapas.factibilidad',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS name"),
                'etapas.fraccionamiento_id','fraccionamientos.nombre as fraccionamiento','etapas.archivo_reglamento',
                'etapas.plantilla_carta_servicios','etapas.costo_mantenimiento','etapas.plantilla_telecom','etapas.empresas_telecom',
                'etapas.empresas_telecom_satelital','etapas.num_cuenta_admin','etapas.clabe_admin','etapas.sucursal_admin',
                'etapas.titular_admin','etapas.banco_admin','etapas.carta_bienvenida');
        
        if($buscar==''){
            $etapas = $query
                    ->where('etapas.num_etapa','!=','Sin Asignar');
        }
        else{
            if($criterio == 'f_ini' || $criterio == 'f_fin')
            {
                $etapas = $query
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->where('etapas.num_etapa','!=','Sin Asignar');
            }
            else{
                $etapas = $query
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('etapas.num_etapa','!=','Sin Asignar');
            }
            
        }

        $etapas = $etapas->orderBy('fraccionamientos.nombre','asc')
                        ->orderBy('etapas.num_etapa','asc')->paginate(8);

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

    public function indexExcel(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;

        $query = Etapa::join('personal','etapas.personal_id','=','personal.id')
            ->join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
            ->select('etapas.num_etapa','etapas.f_ini',
                'etapas.f_fin','etapas.id','etapas.personal_id', 
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS name"),
                'etapas.fraccionamiento_id','fraccionamientos.nombre as fraccionamiento','etapas.archivo_reglamento',
                'etapas.plantilla_carta_servicios','etapas.costo_mantenimiento','etapas.plantilla_telecom','etapas.empresas_telecom',
                'etapas.empresas_telecom_satelital','etapas.num_cuenta_admin','etapas.clabe_admin','etapas.sucursal_admin',
                'etapas.titular_admin','etapas.banco_admin','etapas.carta_bienvenida');
        
        if($buscar==''){
            $etapas = $query
                    ->where('etapas.num_etapa','!=','Sin Asignar');
        }
        else{
            if($criterio == 'f_ini' || $criterio == 'f_fin')
            {
                $etapas = $query
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->where('etapas.num_etapa','!=','Sin Asignar');
            }
            else{
                $etapas = $query
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('etapas.num_etapa','!=','Sin Asignar');
            }
            
        }

        $etapas = $etapas->orderBy('fraccionamientos.nombre','asc')
                            ->orderBy('etapas.num_etapa','asc')->paginate(8);

        return Excel::create('Etapas', function($excel) use ($etapas){
            $excel->sheet('Etapas', function($sheet) use ($etapas){
                
                $sheet->row(1, [
                    'Fraccionamiento','Etapa','Fecha de inicio','Fecha de termino','Encargado'
                ]);

                $sheet->cells('A1:E1', function ($cells) {
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

                foreach($etapas as $index => $etapa) {
                    $cont++;

                    if($etapa->tipo_etapa == 1) $etapa->tipo_etapa = 'Lotificación';
                    elseif($etapa->tipo_etapa == 2) $etapa->tipo_etapa = 'Departamento';
                    else $etapa->tipo_etapa = 'Terreno';
                         

                    $sheet->row($index+2, [
                        $etapa->fraccionamiento, 
                        $etapa->num_etapa,
                        $etapa->f_ini,
                        $etapa->f_fin,
                        $etapa->name

                    ]);	
                }
                $num='A1:E' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

    //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            DB::beginTransaction();
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



            for($i=1;$i<=Sobreprecio::count();$i++){
                $sobreprecio_etapa= new Sobreprecio_etapa();
                $sobreprecio_etapa->etapa_id= $etapa->id;
                $sobreprecio_etapa->sobreprecio_id = $i;
                $sobreprecio_etapa->sobreprecio = 0;
                $sobreprecio_etapa->save();
            }
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
        $etapa = Etapa::findOrFail($request->id);
        $etapa->fraccionamiento_id = $request->fraccionamiento_id;
        $etapa->num_etapa = $request->num_etapa;
        $etapa->f_ini = $request->f_ini;
        $etapa->f_fin = $request->f_fin;
        $etapa->personal_id = $request->personal_id;
        $etapa->fecha_ini_venta = $request->fecha_ini_venta;
        $etapa->save();
    }


    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $etapa = Etapa::findOrFail($request->id);
        $etapa->delete();
    }

    public function contEtapa(Request $request)
    {
        if(!$request->ajax())return redirect('/');
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

    public function selectEtapa(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;

        $etapas = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                ->select('etapas.num_etapa','etapas.id','fraccionamientos.nombre')
                    ->where('fraccionamientos.nombre','=',$buscar)
                    ->where('etapas.num_etapa','!=','Sin Asignar')
                    ->orderBy('etapas.num_etapa','asc')->get();
            
            return['etapas' => $etapas];
    }

    public function uploadReglamento (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $ultimoReglamento = Etapa::select('archivo_reglamento','id')
                                 ->where('id','=',$id)
                                 ->get();

        if($ultimoReglamento->isEmpty()==1){
            $fileName = uniqid().'.'.$request->archivo_reglamento->getClientOriginalExtension();
            $moved =  $request->archivo_reglamento->move(public_path('/files/etapas/reglamentos/'), $fileName);
    
            if($moved){
                if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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
                    if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
                    $reglamentoEtapa = Etapa::findOrFail($request->id);
                    $reglamentoEtapa->archivo_reglamento = $fileName;
                    $reglamentoEtapa->id = $id;
                    $reglamentoEtapa->save(); //Insert
            
                    }
                return back();
            }
    }

    public function uploadPlantillaCartaServicios (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $ultimaPlantilla = Etapa::select('plantilla_carta_servicios','id')
                                 ->where('id','=',$id)
                                 ->get();

        if($ultimaPlantilla->isEmpty()==1){
            $fileName = uniqid().'.'.$request->plantilla_carta_servicios->getClientOriginalExtension();
            $moved =  $request->plantilla_carta_servicios->move(public_path('/files/etapas/plantillasCartaServicios/'), $fileName);
    
            if($moved){
                if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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
                if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
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

    public function descargarReglamentoContrato ($id)
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $costoAnterior = Etapa::select('costo_mantenimiento','empresas_telecom','empresas_telecom_satelital')
                                             ->where('id','=',$id)
                                             ->get();
        if($costoAnterior->isEmpty()==1){
            $costoMantenimiento = new Etapa();
            $costoMantenimiento->costo_mantenimiento = $request->costo_mantenimiento;
            $costoMantenimiento->empresas_telecom = $request->empresas_telecom;
            $costoMantenimiento->empresas_telecom_satelital = $request->empresas_telecom_satelital;
            $costoMantenimiento->save();
        }else{
            $costoMantenimiento = Etapa::findOrFail($request->id);
            $costoMantenimiento->costo_mantenimiento = $request->costo_mantenimiento;
            $costoMantenimiento->empresas_telecom = $request->empresas_telecom;
            $costoMantenimiento->empresas_telecom_satelital = $request->empresas_telecom_satelital;
            $costoMantenimiento->save();
        }
        
    }

    public function uploadPlantillaTelecom (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $plantillaAnterior = Etapa::select('plantilla_telecom','id')
                                            ->where('id','=',$id)
                                            ->get();

                if($plantillaAnterior->isEmpty()==1){
                $fileName = uniqid().'.'.$request->plantilla_telecom->getClientOriginalExtension();
                $moved =  $request->plantilla_telecom->move(public_path('/files/etapas/plantillasTelecom/'), $fileName);

                if($moved){
                    if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
                    $plantillaTelecom = Etapa::findOrFail($request->id);
                    $plantillaTelecom->plantilla_telecom = $fileName;
                    $plantillaTelecom->id = $id;
                    $plantillaTelecom->save(); //Insert

                    }
                return back();
                }
                else{
                $pathAnterior = public_path().'/files/etapas/plantillasTelecom/'.$plantillaAnterior[0]->plantilla_telecom;
                File::delete($pathAnterior); 
                
                $fileName = uniqid().'.'.$request->plantilla_telecom->getClientOriginalExtension();
                $moved =  $request->plantilla_telecom->move(public_path('/files/etapas/plantillasTelecom/'), $fileName);

                if($moved){
                    if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
                    $plantillaTelecom = Etapa::findOrFail($request->id);
                    $plantillaTelecom->plantilla_telecom = $fileName;
                    $plantillaTelecom->id = $id;
                    $plantillaTelecom->save(); //Insert

                    }
                return back();

            }
    }

    public function downloadPlantillaTelecom ($fileName){
        $pathtoFile = public_path().'/files/etapas/plantillasTelecom/'.$fileName;
        return response()->download($pathtoFile);
    }

    public function descargaReglamentoDocs($etapa_id){
        $archivos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
                          ->join('etapas','fraccionamientos.id','=','etapas.fraccionamiento_id')
                          ->select('etapas.archivo_reglamento')
                          ->where('modelos.nombre','!=','Por Asignar')
                          ->where('etapas.num_etapa','!=','Sin Asignar')
                          ->where('etapas.id','=',$etapa_id)
                          ->get();

        $pathtoFile = public_path().'/files/etapas/reglamentos/'.$archivos[0]->archivo_reglamento;
        return response()->download($pathtoFile);
    }

    public function uploadCartaBienvenida (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $ultimaCartaB = Etapa::select('carta_bienvenida','id')
                                 ->where('id','=',$id)
                                 ->get();

        if($ultimaCartaB->isEmpty()==1){
            $fileName = uniqid().'.'.$request->carta_bienvenida->getClientOriginalExtension();
            $moved =  $request->carta_bienvenida->move(public_path('/files/etapas/cartasBienvenida/'), $fileName);
    
            if($moved){
                if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
                $reglamentoEtapa = Etapa::findOrFail($request->id);
                $reglamentoEtapa->carta_bienvenida = $fileName;
                $reglamentoEtapa->id = $id;
                $reglamentoEtapa->save(); //Insert
        
                }
            return back();
            }else{
                $pathAnterior = public_path().'/files/etapas/cartasBienvenida/'.$ultimaCartaB[0]->carta_bienvenida;
                File::delete($pathAnterior);
                $fileName = uniqid().'.'.$request->carta_bienvenida->getClientOriginalExtension();
                $moved =  $request->carta_bienvenida->move(public_path('/files/etapas/cartasBienvenida/'), $fileName);
        
                if($moved){
                    if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
                    $reglamentoEtapa = Etapa::findOrFail($request->id);
                    $reglamentoEtapa->carta_bienvenida = $fileName;
                    $reglamentoEtapa->id = $id;
                    $reglamentoEtapa->save(); //Insert
            
                    }
                return back();
            }
    }

    public function downloadCartaBienvenida ($fileName){
        $pathtoFile = public_path().'/files/etapas/cartasBienvenida/'.$fileName;
        return response()->download($pathtoFile);
    }

    public function formSubmitFactibilidad (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $ultimaCartaB = Etapa::select('factibilidad','id')
                                 ->where('id','=',$id)
                                 ->get();

        if($ultimaCartaB->isEmpty()==1){
            $fileName = uniqid().'.'.$request->factibilidad->getClientOriginalExtension();
            $moved =  $request->factibilidad->move(public_path('/files/etapas/factibilidad/'), $fileName);
    
            if($moved){
                if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
                $reglamentoEtapa = Etapa::findOrFail($request->id);
                $reglamentoEtapa->factibilidad = $fileName;
                $reglamentoEtapa->id = $id;
                $reglamentoEtapa->save(); //Insert
        
                }
            return back();
            }else{
                $pathAnterior = public_path().'/files/etapas/factibilidad/'.$ultimaCartaB[0]->factibilidad;
                File::delete($pathAnterior);
                $fileName = uniqid().'.'.$request->factibilidad->getClientOriginalExtension();
                $moved =  $request->factibilidad->move(public_path('/files/etapas/factibilidad/'), $fileName);
        
                if($moved){
                    if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
                    $reglamentoEtapa = Etapa::findOrFail($request->id);
                    $reglamentoEtapa->factibilidad = $fileName;
                    $reglamentoEtapa->id = $id;
                    $reglamentoEtapa->save(); //Insert
            
                    }
                return back();
            }
    }

    public function downloadFactibilidad ($fileName){
        $pathtoFile = public_path().'/files/etapas/factibilidad/'.$fileName;
        return response()->download($pathtoFile);
    }
}
