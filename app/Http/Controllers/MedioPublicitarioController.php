<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medio_publicitario; //Importar el modelo
use Auth;
use App\Cliente;
use App\Contrato;
use Carbon\Carbon;
//Controlador para el modelo Medio_publicitario.
class MedioPublicitarioController extends Controller
{
    //Función que retorna los medios publicitarios registrados.
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
            $medios_publicitarios = Medio_publicitario::orderBy('nombre','asc')->paginate(8);
        else
            $medios_publicitarios = Medio_publicitario::where($criterio, 'like', '%'. $buscar . '%')->orderBy('nombre','asc')->paginate(8);

        return [
            'pagination' => [
                'total'         => $medios_publicitarios->total(),
                'current_page'  => $medios_publicitarios->currentPage(),
                'per_page'      => $medios_publicitarios->perPage(),
                'last_page'     => $medios_publicitarios->lastPage(),
                'from'          => $medios_publicitarios->firstItem(),
                'to'            => $medios_publicitarios->lastItem(),
            ],
            'medios_publicitarios' => $medios_publicitarios
        ];
    }

    //Función para registrar un nuevo medio publicitario.
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $medio_publicitario = new Medio_publicitario();
        $medio_publicitario->nombre = $request->nombre;
        $medio_publicitario->save();
    }
    //Función para actualizar un medio publicitario.
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $medio_publicitario = Medio_publicitario::findOrFail($request->id);
        $medio_publicitario->nombre = $request->nombre;
        $medio_publicitario->save();
    }
    //Función para eliminar el registro de un medio publicitario.
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $medio_publicitario = Medio_publicitario::findOrFail($request->id);
        $medio_publicitario->delete();
    }
    //Función que retorna todos los medios publicitarios
    public function selectMedioPublicitario(Request $request){
             //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
             if(!$request->ajax())return redirect('/');
             $medios_publicitarios = Medio_publicitario::select('nombre','id')->orderBy('nombre','asc')->get();
             return['medios_publicitarios' => $medios_publicitarios];

    }
    //Función que calculan los resultados de medios publicitarios (Ventas, nuevos prospectos)
    public function estadisticas(Request $request){
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $desde      = $request->desde;
        $hasta      = $request->hasta;
        $etapa      = $request->etapa;
        $asesor     = $request->asesor;
        $proyecto   = $request->proyecto;

        /////////// Arreglo medios publicitarios //////////////
        //Se crearn arreglso para ventas, prospectos, descartados y todos los prospectos.
        $publicidadVentas = Medio_publicitario::select('id','nombre as publicidad')->orderBy('publicidad','asc')->get();
        $publicidadProspectos = Medio_publicitario::select('id','nombre as publicidad')->orderBy('publicidad','asc')->get();
        $publicidadProspecAll = Medio_publicitario::select('id','nombre as publicidad')->orderBy('publicidad','asc')->get();
        $descartadosAll = Medio_publicitario::select('id','nombre as publicidad')->orderBy('publicidad','asc')->get();

        ///Filtros             
            ////////// Arreglo de ID de clientes con contrato firmado //////////////
            $clientesID_contrato = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->select('clientes.id','contratos.id as contrato')->where('contratos.status','!=',2);

                        if($proyecto != '')
                            $clientesID_contrato = $clientesID_contrato->where('lotes.fraccionamiento_id','=',$proyecto);
                        if($etapa != '')
                            $clientesID_contrato = $clientesID_contrato->where('lotes.etapa_id','=',$etapa);
                        if($asesor != '')
                            $clientesID_contrato = $clientesID_contrato->where('creditos.vendedor_id','=',$asesor);
                        if($desde != '' && $hasta != ''){
                            $clientesID_contrato = $clientesID_contrato->whereBetween('contratos.fecha', [$desde.' 00:00:00', $hasta.' 23:59:59']);
                        }
                        else{
                            $clientesID_contrato = $clientesID_contrato->whereBetween('clientes.created_at', ['2000-02-01', $hoy]);
                        }
                        
                        
                        $clientesID_contrato = $clientesID_contrato->orderBy('clientes.id','asc')->distinct()->get();

            ////////// Arreglo de ID de todos los prospectos (con y sin contrato) //////////////
            $prospectos = Cliente::select('id')->where('clasificacion','!=',7)
                        ->where('clasificacion','!=',5);
                        if($proyecto != '')
                            $prospectos = $prospectos->where('proyecto_interes_id','=',$proyecto);
                        if($asesor != '')
                            $prospectos = $prospectos->where('vendedor_id','=',$asesor);
                        if($desde != '' && $hasta != ''){
                            $prospectos = $prospectos->whereBetween('created_at', [$desde, $hasta]);
                        }
                        else{
                            $prospectos = $prospectos->whereBetween('clientes.created_at', ['2000-02-01', $hoy]);
                        }

                        $prospectos = $prospectos->orderBy('id','asc')->distinct()->get();

        // Llenado publicidad all Todos los prospectos
            foreach ($publicidadProspecAll as $ep => $publiAll) {
                $publiAll->cant = 0;
                    //Se obtienen los ids de los clientes registrados en la publicidad elegida y que no sean ventas ni coacreditados
                    $res = Cliente::join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
                                    ->join('clientes_observaciones as cb','clientes.id','=','cb.cliente_id')
                                    ->select('clientes.id')
                                    ->where('clientes.publicidad_id','=',$publiAll->id)
                                    ->where('vendedor_id','!=',104)
                                    ->where('clasificacion','!=',7)
                                    ->where('clasificacion','!=',5);
                                    if($proyecto != '')
                                        $res = $res->where('clientes.proyecto_interes_id','=',$proyecto);
                                    if($asesor != '')
                                        $res = $res->where('clientes.vendedor_id','=',$asesor);
                                    if($desde != '' && $hasta != ''){
                                        $res = $res->whereBetween('cb.created_at', [$desde, $hasta]);
                                    }
                                    else{
                                        $res = $res->whereBetween('cb.created_at', ['2000-02-01', $hoy]);
                                    }
                                    $res = $res->distinct()->count('clientes.id');
                    
                        $publiAll->cant = $res;
            }
        
        // Llenado publicidad descartados
        foreach ($descartadosAll as $ep => $descartado) {
            $descartado->cant = 0;
                        //Se obtienen los ids de los clientes registrados en la publicidad elegida y que sean descartados
                        $res = Cliente::join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
                                        ->join('clientes_observaciones as cb','clientes.id','=','cb.cliente_id')
                                        ->select('clientes.id')
                                        ->where('clientes.publicidad_id','=',$descartado->id)
                                        ->where('vendedor_id','=',104)//Vendedor descartado
                                        ->where('clasificacion','!=',7)
                                        ->where('clasificacion','!=',5);
                                        if($proyecto != '')
                                            $res = $res->where('clientes.proyecto_interes_id','=',$proyecto);
                                        if($asesor != '')
                                            $res = $res->where('clientes.vendedor_id','=',$asesor);
                                        if($desde != '' && $hasta != '')
                                            $res = $res->whereBetween('cb.created_at', [$desde, $hasta]);
                                        else
                                            $res = $res->whereBetween('cb.created_at', ['2000-02-01', $hoy]);
                                        $res = $res->distinct()->count('clientes.id');
                        
                            $descartado->cant = $res;
        }

        ////////// Llenado por publicidad para ventas

            foreach ($publicidadVentas as $ep => $publiV) {
                $publiV->cant = 0;
                if(sizeof($clientesID_contrato))
                    foreach ($clientesID_contrato as $et => $cliente) {
                        $res = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->select('creditos.prospecto_id','contratos.publicidad_id')->where('creditos.prospecto_id','=',$cliente->id)
                                ->where('contratos.publicidad_id','=',$publiV->id)
                                ->where('contratos.id','=',$cliente->contrato)
                                ->get();
                        
                        if(sizeof($res)){
                            $publiV->cant ++;
                        }
                    }
            }

        ////////// Llenado por publicidad para prospectos
            foreach ($publicidadProspectos as $ep => $publiC) {
                $publiC->cant = 0;
                if(sizeof($prospectos))
                    foreach ($prospectos as $et => $cliente) {
                        $res = Cliente::join('medios_publicitarios','clientes.publicidad_id','=','medios_publicitarios.id')
                                ->select('clientes.id','clientes.publicidad_id')->where('clientes.id','=',$cliente->id)
                                ->where('clientes.publicidad_id','=',$publiC->id)->get();
                        
                        if(sizeof($res)){
                            $publiC->cant ++;
                        }
                    }
            }

        return[
            'publicidadVentas' => $publicidadVentas,
            'publicidadProspectos' => $publicidadProspectos,
            'publicidadAll' => $publicidadProspecAll,
            'descartadosAll'=> $descartadosAll,
            'clientesVenta'=>$clientesID_contrato,
            'prospectos'=>$prospectos
        ];

    }
}
