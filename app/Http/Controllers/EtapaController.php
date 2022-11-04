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
use App\Amenitie;
use Excel;
use Auth;

use App\Http\Resources\AmenitieResource;

class EtapaController extends Controller
{
    // Función para retornar las etapas registradas
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        //Llamada a la función privada que retorna la query principal.
        $etapas = $this->getEtapasQuery($request);
        $etapas = $etapas->orderBy('fraccionamientos.nombre','asc')
                        ->orderBy('etapas.num_etapa','asc')->paginate(8);

        if(sizeOf($etapas)){
            foreach($etapas as $etapa){
                $etapa->amenidades = AmenitieResource::collection(Amenitie::where('etapa_id','=',$etapa->id)->get());
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

    public function indexExcel(Request $request)
    {
        // Llamada a la función privada que retorna la query principal.
        $etapas = $this->getEtapasQuery($request);
        $etapas = $etapas->orderBy('fraccionamientos.nombre','asc')
                            ->orderBy('etapas.num_etapa','asc')->paginate(8);

        // Creación y retorno del resultado en excel.
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

    // Función privada que retorna la query para las etapas privadas.
    private function getEtapasQuery(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;

        $etapas = Etapa::join('personal','etapas.personal_id','=','personal.id')
            ->join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
            ->select('etapas.num_etapa','etapas.f_ini',
                'etapas.f_fin','etapas.id','etapas.personal_id', 'etapas.fecha_ini_venta',
                'etapas.factibilidad', 'etapas.terreno_m2',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS name"),
                'etapas.fraccionamiento_id','fraccionamientos.nombre as fraccionamiento','etapas.archivo_reglamento', 'etapas.plantilla_carta_servicios2',
                'etapas.plantilla_carta_servicios','etapas.costo_mantenimiento', 'etapas.costo_mantenimiento2','etapas.plantilla_telecom','etapas.empresas_telecom',
                'etapas.empresas_telecom_satelital','etapas.num_cuenta_admin','etapas.clabe_admin','etapas.sucursal_admin',
                'etapas.titular_admin','etapas.banco_admin','etapas.carta_bienvenida', 'etapas.carpeta_ventas')
                ->where('etapas.num_etapa','!=','Sin Asignar');

        if($buscar!=''){
            if($criterio == 'f_ini' || $criterio == 'f_fin') // Busqueda por fecha
                $etapas = $etapas->whereBetween($criterio, [$buscar,$buscar2]);
            else // Busqueda general.
                $etapas = $etapas->where($criterio, 'like', '%'. $buscar . '%');
        }

        return $etapas;
    }

    // Función para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            DB::beginTransaction();
            // Crea registro en la BD de Etapas
            $etapa = new Etapa();
            $etapa->fraccionamiento_id = $request->fraccionamiento_id;
            $etapa->num_etapa = $request->num_etapa;
            $etapa->f_ini = $request->f_ini;
            $etapa->f_fin = $request->f_fin;
            $etapa->personal_id = $request->personal_id;
            $etapa->terreno_m2 = $request->terreno_m2;
            $etapa->save();

            // Crea registro en la BD de precios por etapa para terreno excedente.
            $precio_etapa = new Precio_etapa();
            $precio_etapa->fraccionamiento_id = $request->fraccionamiento_id;
            $precio_etapa->etapa_id = $etapa->id;
            $precio_etapa->precio_excedente = 0;
            $precio_etapa->save();

            // Se asignan sobreprecios por etapa
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
        //Se accede al registro buscado.
        $etapa = Etapa::findOrFail($request->id);
        $etapa->fraccionamiento_id = $request->fraccionamiento_id;
        $etapa->num_etapa = $request->num_etapa;
        $etapa->f_ini = $request->f_ini;
        $etapa->f_fin = $request->f_fin;
        $etapa->personal_id = $request->personal_id;
        $etapa->fecha_ini_venta = $request->fecha_ini_venta;
        $etapa->terreno_m2 = $request->terreno_m2;
        $etapa->save();
    }

    // Función para eliminar el registro de una etapa.
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $etapa = Etapa::findOrFail($request->id);
        $etapa->delete();
    }

    // Funcion que retorna el numero de etapas por proyecto.
    public function contEtapa(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $fraccionamiento_id = $request->fraccionamiento_id;
        $etapas = Etapa::where('fraccionamiento_id','=',$fraccionamiento_id)->get();
        $contador = $etapas->count();

        return $contador + 1;
    }

    // Funcion que retorna las etapas por proyecto.
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

    // Funcion que retorna las etapas con lotes aun disponibles para venta.
    public function selectEtapaDisp(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $etapas = Etapa::join('lotes','etapas.id','lotes.etapa_id')
            ->select('etapas.num_etapa','etapas.id')
            ->where('lotes.habilitado','=',1)
            ->where('lotes.contrato','=',0)
            ->where('etapas.fraccionamiento_id', '=', $buscar )
            //>where('num_etapa', '!=', 'Sin Asignar' )
            ->orderBy('etapas.num_etapa','desc')
            ->distinct()
            ->get();
        return['etapas' => $etapas];
    }

    /* Funcion que retorna las etapas filtrando por proyecto
        sin tomar en cuenta la etapa Sin asignar. */
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

    public function formSubmitCarpetaVentas(Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $etapa = Etapa::findOrFail($id);

        if($etapa->carpeta_ventas != NULL){
            $pathAnterior = public_path().'/files/etapas/carpeta_ventas/'.$etapa->carpeta_ventas;
            File::delete($pathAnterior);
        }
            $fileName = uniqid().'.'.$request->carpeta_ventas->getClientOriginalExtension();
            $moved =  $request->carpeta_ventas->move(public_path('/files/etapas/carpeta_ventas/'), $fileName);

            if($moved){
                if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
                $etapa = Etapa::findOrFail($request->id);
                $etapa->carpeta_ventas = $fileName;
                $etapa->save(); //Insert
            }
                return back();
    }

    // Función para almacenar el reglamento de la etapa.
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
                $reglamentoEtapa->save(); //Insert

                }
            return back();
        }
    }

    // Función para almacenar la Plantilla para Carta de Servicios
    public function uploadPlantillaCartaServicios (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        // Se busca si el registro ya cuenta con una plantilla almacenada.
        $ultimaPlantilla = Etapa::select('plantilla_carta_servicios','id')
                                 ->where('id','=',$id)->get();

        if($ultimaPlantilla->isEmpty()==1){ // Si no hay una plantilla registrada
            // Se guarda el archivo en el servidor
            $fileName = uniqid().'.'.$request->plantilla_carta_servicios->getClientOriginalExtension();
            $moved =  $request->plantilla_carta_servicios->move(public_path('/files/etapas/plantillasCartaServicios/'), $fileName);
        }else{
            // si ya se cuenta con un archivo guardado se elimina el archivo anterior
            $pathAnterior = public_path().'/files/etapas/plantillasCartaServicios/'.$ultimaPlantilla[0]->plantilla_carta_servicios;
            File::delete($pathAnterior);
            // Se guarda el archivo nuevo en el servidor.
            $fileName = uniqid().'.'.$request->plantilla_carta_servicios->getClientOriginalExtension();
            $moved =  $request->plantilla_carta_servicios->move(public_path('/files/etapas/plantillasCartaServicios/'), $fileName);
        }

        // Se verifica que se guardo correctamente el servidor.
        if($moved){
            // Se actualiza el registro
            $plantillaCartaServicios = Etapa::findOrFail($request->id);
            $plantillaCartaServicios->plantilla_carta_servicios = $fileName;
            $plantillaCartaServicios->save(); //Insert
        }
        return back();
    }

    // Función para almacenar la Plantilla para Carta de Servicios para terrenos
    public function uploadPlantillaCartaServicios2 (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        // Se busca si el registro ya cuenta con una plantilla almacenada.
        $ultimaPlantilla = Etapa::select('plantilla_carta_servicios2','id')
                                 ->where('id','=',$id)
                                 ->get();

        if($ultimaPlantilla->isEmpty()==1){// Si no hay una plantilla registrada
            // Se guarda el archivo en el servidor
            $fileName = uniqid().'.'.$request->plantilla_carta_servicios2->getClientOriginalExtension();
            $moved =  $request->plantilla_carta_servicios2->move(public_path('/files/etapas/plantillasCartaServicios/'), $fileName);

        }else{
            // si ya se cuenta con un archivo guardado se elimina el archivo anterior
            $pathAnterior = public_path().'/files/etapas/plantillasCartaServicios/'.$ultimaPlantilla[0]->plantilla_carta_servicios2;
            File::delete($pathAnterior);
            // Se guarda el archivo nuevo en el servidor.
            $fileName = uniqid().'.'.$request->plantilla_carta_servicios2->getClientOriginalExtension();
            $moved =  $request->plantilla_carta_servicios2->move(public_path('/files/etapas/plantillasCartaServicios/'), $fileName);

        }
        // Se verifica que se guardo correctamente el servidor.
        if($moved){
            $plantillaCartaServicios = Etapa::findOrFail($request->id);
            $plantillaCartaServicios->plantilla_carta_servicios2 = $fileName;
            $plantillaCartaServicios->save(); //Insert
        }
        return back();
    }

    // Función para descargar reglamento
    public function downloadReglamento ($fileName){
        $pathtoFile = public_path().'/files/etapas/reglamentos/'.$fileName;
        return response()->file($pathtoFile);
    }

    // Funcion para descargar la plantilla para carta de servicios
    public function downloadPlantillaCartaServicios ($fileName){
        $pathtoFile = public_path().'/files/etapas/plantillasCartaServicios/'.$fileName;
        return response()->file($pathtoFile);
    }

    // Funcion para descargar la plantilla para carta de servicios en terrenos
    public function downloadPlantillaCartaServicios2 ($fileName){
        $pathtoFile = public_path().'/files/etapas/plantillasCartaServicios/'.$fileName;
        return response()->file($pathtoFile);
    }

    // Funcion para descargar la plantilla para carta de servicios en terrenos
    public function downloadCarpetaVentas ($fileName){
        $pathtoFile = public_path().'/files/etapas/carpeta_ventas/'.$fileName;
        return response()->file($pathtoFile);
    }

    // Funcion para descargar reglamento por contrato de venta.
    public function descargarReglamentoContrato ($id)
    {
         $reglamento = Contrato::join('creditos','contratos.id','=','creditos.id')
        ->join('lotes','creditos.lote_id','=','lotes.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->select('etapas.archivo_reglamento')
        ->where('contratos.id','=',$id)->get();

        $pathtoFile = public_path().'/files/etapas/reglamentos/'.$reglamento[0]->archivo_reglamento;
        return response()->file($pathtoFile);
    }

    // Función para registrar el costo por cuota de mantenimiento
    public function registrarCostoMantenimiento(Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $costoMantenimiento = Etapa::findOrFail($request->id);
        $costoMantenimiento->costo_mantenimiento = $request->costo_mantenimiento;
        $costoMantenimiento->costo_mantenimiento2 = $request->costo_mantenimiento2;
        $costoMantenimiento->empresas_telecom = $request->empresas_telecom;
        $costoMantenimiento->empresas_telecom_satelital = $request->empresas_telecom_satelital;
        $costoMantenimiento->save();
    }

    // Función para almacenar la Plantilla para Carta de Servicios de telecomunicaciones
    public function uploadPlantillaTelecom (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        // Se busca si el registro ya cuenta con una plantilla almacenada.
        $plantillaAnterior = Etapa::select('plantilla_telecom','id')
                                            ->where('id','=',$id)
                                            ->get();

        if($plantillaAnterior->isEmpty()==1){// Si no hay una plantilla registrada
            // Se guarda el archivo en el servidor
            $fileName = uniqid().'.'.$request->plantilla_telecom->getClientOriginalExtension();
            $moved =  $request->plantilla_telecom->move(public_path('/files/etapas/plantillasTelecom/'), $fileName);
        }
        else{
            // Si ya se encuentra registrado un archivo, se elimina el anterior.
            $pathAnterior = public_path().'/files/etapas/plantillasTelecom/'.$plantillaAnterior[0]->plantilla_telecom;
            File::delete($pathAnterior);
            // Se guarda el archivo nuevo en el servidor
            $fileName = uniqid().'.'.$request->plantilla_telecom->getClientOriginalExtension();
            $moved =  $request->plantilla_telecom->move(public_path('/files/etapas/plantillasTelecom/'), $fileName);

        }

        // Se verifica que se guardo correctamente el servidor.
        if($moved){
            // se actualiza el registro.
            $plantillaTelecom = Etapa::findOrFail($request->id);
            $plantillaTelecom->plantilla_telecom = $fileName;
            $plantillaTelecom->save(); //Insert
        }
        return back();
    }

    // Función para descargar la plantilla de telecomunicaciones.
    public function downloadPlantillaTelecom ($fileName){
        $pathtoFile = public_path().'/files/etapas/plantillasTelecom/'.$fileName;
        return response()->file($pathtoFile);
    }

    // Funcion para descargar el reglamento de una etapa en especifico.
    public function descargaReglamentoDocs($etapa_id){
        $archivos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
                          ->join('etapas','fraccionamientos.id','=','etapas.fraccionamiento_id')
                          ->select('etapas.archivo_reglamento')
                          ->where('modelos.nombre','!=','Por Asignar')
                          ->where('etapas.num_etapa','!=','Sin Asignar')
                          ->where('etapas.id','=',$etapa_id)
                          ->get();

        $pathtoFile = public_path().'/files/etapas/reglamentos/'.$archivos[0]->archivo_reglamento;
        return response()->file($pathtoFile);
    }

    // Función para almacenar la Carta de Bienvenida.
    public function uploadCartaBienvenida (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        // Se busca si el registro ya cuenta con una plantilla almacenada.
        $ultimaCartaB = Etapa::select('carta_bienvenida','id')
                                 ->where('id','=',$id)
                                 ->get();

        if($ultimaCartaB->isEmpty()==1){// Si no hay una plantilla registrada
            // Se guarda el archivo en el servidor
            $fileName = uniqid().'.'.$request->carta_bienvenida->getClientOriginalExtension();
            $moved =  $request->carta_bienvenida->move(public_path('/files/etapas/cartasBienvenida/'), $fileName);

        }else{
            // Si ya se encuentra registrado un archivo, se elimina el anterior.
            $pathAnterior = public_path().'/files/etapas/cartasBienvenida/'.$ultimaCartaB[0]->carta_bienvenida;
            File::delete($pathAnterior);
            // Se guarda el nuevo archivo en el servidor
            $fileName = uniqid().'.'.$request->carta_bienvenida->getClientOriginalExtension();
            $moved =  $request->carta_bienvenida->move(public_path('/files/etapas/cartasBienvenida/'), $fileName);
        }

         // Se verifica que se guardo correctamente el servidor.
         if($moved){
            // se actualiza el registro.
            $reglamentoEtapa = Etapa::findOrFail($request->id);
            $reglamentoEtapa->carta_bienvenida = $fileName;
            $reglamentoEtapa->save(); //Insert
        }
        return back();
    }

    // Función para descargar carta de bienvenida
    public function downloadCartaBienvenida ($fileName){
        $pathtoFile = public_path().'/files/etapas/cartasBienvenida/'.$fileName;

        return response()->file($pathtoFile);
    }

    // Función para almacenar factibilidad
    public function formSubmitFactibilidad (Request $request, $id){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        // Se busca si el registro ya cuenta con una plantilla almacenada.
        $ultimaCartaB = Etapa::select('factibilidad','id')->where('id','=',$id)->get();

        if($ultimaCartaB->isEmpty()==1){// Si no hay una plantilla registrada
            // Se guarda el archivo en el servidor
            $fileName = uniqid().'.'.$request->factibilidad->getClientOriginalExtension();
            $moved =  $request->factibilidad->move(public_path('/files/etapas/factibilidad/'), $fileName);
        }else{
            // Si ya se encuentra registrado un archivo, se elimina el anterior.
            $pathAnterior = public_path().'/files/etapas/factibilidad/'.$ultimaCartaB[0]->factibilidad;
            File::delete($pathAnterior);
            // Se guarda el archivo en el servidor
            $fileName = uniqid().'.'.$request->factibilidad->getClientOriginalExtension();
            $moved =  $request->factibilidad->move(public_path('/files/etapas/factibilidad/'), $fileName);
        }

        // Se verifica que se guardo correctamente el servidor.
        if($moved){
            // se actualiza el registro.
            $reglamentoEtapa = Etapa::findOrFail($request->id);
            $reglamentoEtapa->factibilidad = $fileName;
            $reglamentoEtapa->save(); //Insert
        }
        return back();
    }

    // Función para descargar la factibilidad.
    public function downloadFactibilidad ($fileName){
        $pathtoFile = public_path().'/files/etapas/factibilidad/'.$fileName;
        return response()->file($pathtoFile);
    }
}
