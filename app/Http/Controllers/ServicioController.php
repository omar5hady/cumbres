<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servicio;
use App\Contrato;
use Carbon\Carbon;
use NumerosEnLetras;

class ServicioController extends Controller
{
    public function index(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $servicios = Servicio::orderBy('descripcion','asc')->paginate(8);
        }
        else{
            $servicios = Servicio::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','asc')->paginate(8);
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

    public function store(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicios = new Servicio();
        $servicios->descripcion = $request->descripcion;
        $servicios->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicios = Servicio::findOrFail($request->id);
        $servicios->descripcion = $request->descripcion;
        $servicios->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicios = Servicio::findOrFail($request->id);
        $servicios->delete();
    }

    public function selectServicio(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $servicios = Servicio::select('id','descripcion')
        ->orderBy('descripcion', 'asc')->get();
 
        return ['servicios' => $servicios];
    }

    public function servicioTelecomPdf(Request $request, $id)
    {
        $serviciosTele = Contrato::join('creditos','contratos.id','=','creditos.id')
        ->join('personal','creditos.prospecto_id','=','personal.id')
        ->join('clientes','creditos.prospecto_id','=','clientes.id')
        ->join('lotes','creditos.lote_id','=','lotes.id')
        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
        ->select('personal.nombre','personal.apellidos','creditos.fraccionamiento as proyecto','fraccionamientos.empresas_telecom',
         'fraccionamientos.empresas_telecom_satelital','fraccionamientos.plantilla_telecom','creditos.manzana','creditos.num_lote')
        ->where('contratos.id','=',$id)
        ->get();

            $pdf = \PDF::loadview('pdf.contratos.serviciosDeTelecom',['serviciosTele' => $serviciosTele]);
            return $pdf->stream('servicios.pdf');
            // return ['cabecera' => $cabecera];
     }

     public function cartaDeServicioPdf(Request $request, $id)
     {
         
         $datos = Contrato::join('creditos','contratos.id','=','creditos.id')
         ->join('personal','creditos.prospecto_id','=','personal.id')
         ->join('clientes','creditos.prospecto_id','=','clientes.id')
         ->join('lotes','creditos.lote_id','=','lotes.id')
         ->join('etapas','lotes.etapa_id','=','etapas.id')
         ->select('personal.nombre','personal.apellidos','etapas.plantilla_carta_servicios','etapas.costo_mantenimiento')
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

            setlocale(LC_TIME, 'es');
            $now= Carbon::now();
            $datos[0]->fecha_hoy = $now->formatLocalized('%d de %B de %Y');
 
            $datos[0]->costoMantenimientoLetra = NumerosEnLetras::convertir($datos[0]->costo_mantenimiento,'Pesos',false,'Centavos');
 
             $pdf = \PDF::loadview('pdf.contratos.cartaDeServicios',['datos' => $datos , 'servicios' => $servicios]);
             return $pdf->stream('CartaDeservicios.pdf');
             // return ['cabecera' => $cabecera];
      }


     
}
