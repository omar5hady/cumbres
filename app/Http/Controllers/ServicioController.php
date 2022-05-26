<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servicio;
use App\Contrato;
use Carbon\Carbon;
use NumerosEnLetras;
use App\Modelo;
use Auth;

class ServicioController extends Controller
{
    // Funcion para la consulta de servicios disponibles 
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $servicios = Servicio::orderBy('descripcion','asc')->paginate(8);
        }
        else{ 
            $servicios = Servicio::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','asc')->paginate(8); // busqueda por criterio
        }

        return [
            'pagination' => [
                'total'         => $servicios->total(),
                'current_page'  => $servicios->currentPage(),
                'per_page'      => $servicios->perPage(),
                'last_page'     => $servicios->lastPage(),
                'from'          => $servicios->firstItem(),
                'to'            => $servicios->lastItem(),
            ],
            'servicios' => $servicios
        ];

    }

    // crea un nuevo servicio y su descripcion
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $servicios = new Servicio();
        $servicios->descripcion = $request->descripcion;
        $servicios->save();
    }

    // Modifica la descripcion del servicio seleccionado por su id  
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $servicios = Servicio::findOrFail($request->id);
        $servicios->descripcion = $request->descripcion;
        $servicios->save();
    }

    // Elimina registro de la tabla Servicio 
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $servicios = Servicio::findOrFail($request->id);
        $servicios->delete();
    }


    // selecciona todos los registros disponibles  
    public function selectServicio(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicios = Servicio::select('id','descripcion')
        ->orderBy('descripcion', 'asc')->get();
 
        return ['servicios' => $servicios];
    }

    //Funcion para crear el archivo PDF para la instalacion del servicio de telefonia
    public function servicioTelecomPdf($id)
    {
        $serviciosTele = Contrato::join('creditos','contratos.id','=','creditos.id')
        ->join('personal','creditos.prospecto_id','=','personal.id')
        ->join('clientes','creditos.prospecto_id','=','clientes.id')
        ->join('lotes','creditos.lote_id','=','lotes.id')
        ->join('etapas','lotes.etapa_id','=','etapas.id')
        ->select('personal.nombre','personal.apellidos','creditos.fraccionamiento as proyecto','etapas.empresas_telecom',
            'lotes.emp_constructora', 'etapas.num_etapa',
            'etapas.empresas_telecom_satelital','etapas.plantilla_telecom','creditos.manzana','creditos.num_lote')
        ->where('contratos.id','=',$id)
        ->get();

            $pdf = \PDF::loadview('pdf.contratos.serviciosDeTelecom',['serviciosTele' => $serviciosTele]);
            return $pdf->stream('servicios.pdf');
            // return ['cabecera' => $cabecera];
    }

    // crea el documento PDF de la carta de servicios para el cliente 
    public function cartaDeServicioPdf($id)
    {
         
         $datos = Contrato::join('creditos','contratos.id','=','creditos.id')
         ->join('personal','creditos.prospecto_id','=','personal.id')
         ->join('clientes','creditos.prospecto_id','=','clientes.id')
         ->join('lotes','creditos.lote_id','=','lotes.id')
         ->join('modelos','lotes.modelo_id','=','modelos.id')
         ->join('etapas','lotes.etapa_id','=','etapas.id')
         ->select('personal.nombre','personal.apellidos','etapas.plantilla_carta_servicios','etapas.costo_mantenimiento',
            'etapas.costo_mantenimiento2', 'etapas.plantilla_carta_servicios2',
            'lotes.emp_constructora','lotes.emp_terreno','modelos.nombre as modelo','etapas.num_etapa')
         ->where('contratos.id','=',$id)
         ->get();

         $servicios = Contrato::join('creditos','contratos.id','=','creditos.id')
         ->join('lotes','creditos.lote_id','=','lotes.id')
         ->join('etapas','lotes.etapa_id','=','etapas.id')
         ->join('serv_etapas','etapas.id','=','serv_etapas.etapa_id')
         ->join('servicios','serv_etapas.serv_id','=','servicios.id')
         ->select('servicios.descripcion')
         ->where('contratos.id','=',$id)
         ->get();

            setlocale(LC_TIME, 'es_MX.utf8');
            $now= Carbon::now();
            $datos[0]->fecha_hoy = $now->formatLocalized('%d de %B de %Y');

            //convierte los numeros a letras para el archivo 
            $datos[0]->costoMantenimientoLetra = NumerosEnLetras::convertir($datos[0]->costo_mantenimiento,'Pesos',true,'Centavos');
            $datos[0]->costoMantenimientoLetra2 = NumerosEnLetras::convertir($datos[0]->costo_mantenimiento2,'Pesos',true,'Centavos');
 
            $pdf = \PDF::loadview('pdf.contratos.cartaDeServicios',['datos' => $datos , 'servicios' => $servicios]);
             return $pdf->stream('CartaDeservicios.pdf');
             
    }

    // crea el documento PDF de la carta de servicios para el apartado de documentos anexos
    public function cartaDeServicioDocs($etapa_id){
        $archivos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','fraccionamientos.id','=','etapas.fraccionamiento_id')
        ->select('etapas.plantilla_carta_servicios','etapas.costo_mantenimiento','etapas.num_etapa')
        ->where('modelos.nombre','!=','Por Asignar')
        ->where('etapas.num_etapa','!=','Sin Asignar')
        ->where('etapas.id','=',$etapa_id)
        ->get();

        $servicios = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','fraccionamientos.id','=','etapas.fraccionamiento_id')
        ->join('serv_etapas','etapas.id','=','serv_etapas.etapa_id')
        ->join('servicios','serv_etapas.serv_id','=','servicios.id')
        ->select('servicios.descripcion')
        ->where('etapas.id','=',$etapa_id)
        ->distinct()->get();

        setlocale(LC_TIME, 'es_MX.utf8');
        $now= Carbon::now();
        $archivos[0]->fecha_hoy = $now->formatLocalized('%d de %B de %Y');

        $archivos[0]->costoMantenimientoLetra = NumerosEnLetras::convertir($archivos[0]->costo_mantenimiento,'Pesos',true,'Centavos');

        $pdf = \PDF::loadview('pdf.Docs.cartaDeServicios',['archivos' => $archivos , 'servicios' => $servicios]);
        return $pdf->stream('CartaDeservicios.pdf');
    }

      //Funcion para crear el archivo PDF para la instalacion del servicio de telefonia del apartado de documentos anexos
    public function cartaDeTelecomunicacionesDocs($etapa_id){
        $archivos = Modelo::join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
        ->join('etapas','fraccionamientos.id','=','etapas.fraccionamiento_id')
        ->select('etapas.plantilla_telecom','fraccionamientos.nombre as proyecto','etapas.empresas_telecom',
        'etapas.num_etapa',
        'etapas.empresas_telecom_satelital')
        ->where('modelos.nombre','!=','Por Asignar')
        ->where('etapas.num_etapa','!=','Sin Asignar')
        ->where('etapas.id','=',$etapa_id)
        ->distinct()->get();

        
        $pdf = \PDF::loadview('pdf.Docs.serviciosDeTelecom',['archivos' => $archivos]);
        return $pdf->stream('servicios.pdf');
    }


     
}
