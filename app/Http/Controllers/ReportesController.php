<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Excel;
use Auth;
use Carbon\Carbon;
use App\Etapa;
use App\Empresa;
use App\Fraccionamiento;
use App\Credito;
use App\Contrato;
use App\Expediente;
use App\EquipLote;
use App\Entrega;
use App\Dep_credito;
use App\Lote;
use App\Pago_contrato;
use App\Historial_descartado;
use App\Modelo;
use App\Solic_detalle;
use App\Contratista;
use App\inst_seleccionada;
use App\Cat_detalle_subconcepto;
use App\Cliente;
use App\Vendedor;
use App\Detalle_previo;
use App\Revision_previa;
use App\Reubicacion;
use App\Descripcion_detalle;

class ReportesController extends Controller
{
    // Función para generar el reporte de inventarios
    public function reporteInventario(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $proyecto = $request->proyecto;
        $etapa = $request->etapa;
        $empresa_const = $request->empresa;
        $empresa_terreno = $request->empresa2;

        //Query para obtener las etapas y fraccionamientos
        $proyectos = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
            ->select('etapas.num_etapa','fraccionamientos.nombre as proyecto','etapas.id','etapas.fraccionamiento_id');
        if($proyecto != '') // filtra por proyecto
            $proyectos = $proyectos->where('etapas.fraccionamiento_id','=',$proyecto);
        if($etapa != '')  // filtra por etapa
            $proyectos = $proyectos->where('etapas.id','=',$etapa);

        $proyectos = $proyectos->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')->get();

        foreach($proyectos as $index => $proyecto){ // para cada proyecto
            //Se obtiene el total de lotes dentro del proyecto y la etapa.
            $proyecto->totalLotes = Lote::where('fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->where('etapa_id','=',$proyecto->id);
                if($empresa_terreno != '')//
                    $proyecto->totalLotes = $proyecto->totalLotes->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_const != '')
                    $proyecto->totalLotes = $proyecto->totalLotes->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
                $proyecto->totalLotes = $proyecto->totalLotes->count(); // cuenta el total de lotes del proyecto

            //Query para obtener las ventas con escrituras firmadas en el periodo elegido.
            $firmadasAct = Expediente::join('contratos','expedientes.id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->where('lotes.etapa_id','=',$proyecto->id)
                    ->whereBetween('contratos.fecha', [$fecha1, $fecha2]);
                if($empresa_terreno != '')//Filtro por empresa de terreno
                    $firmadasAct = $firmadasAct->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_const != '')//Filtro para empresa constructora
                    $firmadasAct = $firmadasAct->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
                $firmadasAct = $firmadasAct->distinct()->count(); //

            //Query para obtener las ventas con escrituras firmadas antes de las fechas seleccionadas.
            $firmadasAnt = Expediente::join('contratos','expedientes.id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('contratos.fecha','<',$fecha1)
                    ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->where('lotes.etapa_id','=',$proyecto->id);// cuenta los lotes vendidos antes de la fecha1
                if($empresa_terreno != '')//Filtro por empresa de terreno
                    $firmadasAnt = $firmadasAnt->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_const != '')//Filtro para empresa constructora
                    $firmadasAnt = $firmadasAnt->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
                $firmadasAnt = $firmadasAnt->distinct()->count();
            //Query para obtener las ventas con credito directo liquidadas en el periodo seleccionado
            $contadoAct = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                    ->where('contratos.status','=',3)
                    ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->whereBetween('contratos.fecha', [$fecha1, $fecha2])
                    ->where('lotes.etapa_id','=',$proyecto->id);
                if($empresa_terreno != '')//Filtro por empresa de terreno
                    $contadoAct = $contadoAct->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_const != '')//Filtro para empresa constructora
                    $contadoAct = $contadoAct->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
                $contadoAct = $contadoAct->distinct()->count(); // cuenta los lotes con contrato en status firmado entre la fecha1 y fecha2
            //Query para obtener las ventas con credito directo liquidadas antes de las fechas seleccionadas.
            $contadoAnt = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                    ->where('contratos.status','=',3)
                    ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->where('contratos.fecha','<',$fecha1)
                    ->where('lotes.etapa_id','=',$proyecto->id);
                if($empresa_terreno != '')//Filtro por empresa de terreno
                    $contadoAnt = $contadoAnt->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_terreno != '')//Filtro para empresa constructora
                    $contadoAnt = $contadoAnt->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
                    $contadoAnt = $contadoAnt->distinct()->count(); //cuenta los lotes con contrato en status firmado antes de la fecha1

            $proyecto->descAnt = $firmadasAnt + $contadoAnt; // Sumatoria individualizadas anteriormente.
            $proyecto->descAct = $firmadasAct + $contadoAct; // Sumatoria individualizadas actualmente.
            $proyecto->totalDescarga = $proyecto->descAnt + $proyecto->descAct; //Sumatoria total individualizadas
            $proyecto->inventario = $proyecto->totalLotes - $proyecto->totalDescarga; //Diferencia
        }

        return ['resumen'=>$proyectos];
    }
    // Función para generar el reporte de ventas por vendedor
    public function reporteVendedores(Request $request){
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        //rango de fechas 30, 60 y 90 dias despues
        $fecha30 = Carbon::parse($fecha2)->addDays(30)->format('Y-m-d');
        $fecha60 = Carbon::parse($fecha2)->addDays(60)->format('Y-m-d');
        $fecha90 = Carbon::parse($fecha2)->addDays(90)->format('Y-m-d');
        //Query para obtener los vendedores activos.
        $vendedores = Vendedor::join('personal','vendedores.id','=','personal.id')
                    ->join('users','personal.id','=','users.id')
                    ->select('vendedores.id','personal.nombre','personal.apellidos')
                    ->where('users.condicion','=',1)
                    ->orderBy('personal.nombre','asc')
                    ->get();

        //Se verifica que si se envia busqueda por fecha para determinar la información a mostrar.
        if($fecha1 != '') $mostrar = 1;
        else $mostrar = 0;
            //Se recorre el arreglo de vendedores
            foreach($vendedores as $index => $vendedor){
                //Inicialización de variables.
                $vendedor->ventas30 = $vendedor->ventas60 = $vendedor->ventas90 = 0;
                $vendedor->por_venta = 0;
                $vendedor->por_cancel = 0;
                $vendedor->por_bat = 0;
                //Query para obtener todos los clientes registrados por el vendedor.
                $vendedor->clientes = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','!=',7);
                if($fecha1 != '')//Filtro por fecha de registro.
                    $vendedor->clientes = $vendedor->clientes->whereBetween('created_at', [$fecha1, $fecha2]);
                $vendedor->clientes = $vendedor->clientes->count();
                //Query para obtener todos los clientes tipo A registrados por el vendedor.
                $vendedor->tipoA = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',2);
                if($fecha1 != '')//Filtro por fecha de registro.
                    $vendedor->tipoA = $vendedor->tipoA->whereBetween('created_at', [$fecha1, $fecha2]);
                $vendedor->tipoA = $vendedor->tipoA->count();
                //Query para obtener todos los clientes tipo B registrados por el vendedor.
                $vendedor->tipoB = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',3);
                if($fecha1 != '')//Filtro por fecha de registro.
                    $vendedor->tipoB = $vendedor->tipoB->whereBetween('created_at', [$fecha1, $fecha2]);
                $vendedor->tipoB = $vendedor->tipoB->count();
                //Query para obtener todos los clientes tipo C registrados por el vendedor.
                $vendedor->tipoC = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',4);
                if($fecha1 != '')//Filtro por fecha de registro.
                    $vendedor->tipoC = $vendedor->tipoC->whereBetween('created_at', [$fecha1, $fecha2]);
                $vendedor->tipoC = $vendedor->tipoC->count();
                //Query para obtener todos los clientes no viables registrados por el vendedor.
                $vendedor->noViable = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',1);
                if($fecha1 != '')//Filtro por fecha de registro.
                    $vendedor->noViable = $vendedor->noViable->whereBetween('created_at', [$fecha1, $fecha2]);
                $vendedor->noViable = $vendedor->noViable->count();
                //Query para obtener las ventas generadas por el vendedor
                $vendedor->ventas = $this->getNumContratos($vendedor->id, $fecha1, $fecha2, $fecha1, $fecha2, $request->proyecto);

                if($fecha1 != ''){ //Busquedas solo al seleccionar fechas de busqueda
                    //Query para obtener las ventas realizadas 30 dias posteriores a la fecha de busqueda
                    $vendedor->ventas30 = $this->getNumContratos($vendedor->id, $fecha1, $fecha2, $fecha2, $fecha30, $request->proyecto);
                    //Query para obtener las ventas realizadas 60 dias posteriores a la fecha de busqueda
                    $vendedor->ventas60 = $this->getNumContratos($vendedor->id, $fecha1, $fecha2, $fecha30, $fecha60, $request->proyecto);
                    //Query para obtener las ventas realizadas 90 dias posteriores a la fecha de busqueda
                    $vendedor->ventas90 = $this->getNumContratos($vendedor->id, $fecha1, $fecha2, $fecha60, $fecha90, $request->proyecto);
                }

                //Query para obtener los contratos cancelados
                $vendedor->canceladas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->where('creditos.vendedor_id','=',$vendedor->id)
                    ->where('contratos.status','=',0)
                    ->where('clientes.clasificacion','!=',7)
                    ->where('clientes.user_alta','=',$vendedor->id);
                    if($request->proyecto != '')//Filtro por proyecto
                        $vendedor->canceladas = $vendedor->canceladas->where('lotes.fraccionamiento_id','=',$request->proyecto);
                    if($fecha1 != '')//Filtro para contratos creados en la fecha seleccionada
                        $vendedor->canceladas = $vendedor->canceladas->whereBetween('contratos.fecha', [$fecha1,$fecha90])
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2]);//Filtro para clientes creados en la fecha seleccionada
                    $vendedor->canceladas = $vendedor->canceladas->count();
                //Query para obtener los prospectos descartados
                $vendedor->nv = Historial_descartado::where('vendedor_id','=',$vendedor->id);
                                if($fecha1 != '')//Filtro para fecha en que se descarto.
                                    $vendedor->nv = $vendedor->nv->whereBetween('created_at', [$fecha1,$fecha2]);
                            $vendedor->nv = $vendedor->nv->count();

                if($vendedor->clientes != 0)
                    $vendedor->por_venta=(($vendedor->ventas + $vendedor->ventas30 + $vendedor->ventas60 + $vendedor->ventas90)/$vendedor->clientes)*100;
                if($vendedor->ventas != 0)
                    $vendedor->por_cancel=(($vendedor->canceladas)/($vendedor->ventas + $vendedor->ventas30 + $vendedor->ventas60 + $vendedor->ventas90))*100;
                if($vendedor->clientes != 0)
                    $vendedor->por_bat=($vendedor->nv/$vendedor->clientes)*100;
            }

        return ['vendedores' => $vendedores,'mostrar' => $mostrar];
    }
    //Función privada que retorna el numero de contratos generados,
    //Recibe como parametro el id del vendedor a buscar, proyecto en el que compro y el rango de fechas
    private function getNumContratos($vendedorId, $fecha1, $fecha2, $fecha3, $fecha4, $proyecto){
        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->whereIn('contratos.status',[3,1])//Contratos firmados y pendientes
                    ->where('clientes.clasificacion','!=',7)
                    ->where('creditos.vendedor_id','=',$vendedorId)
                    ->where('clientes.user_alta','=',$vendedorId)
                    ->whereBetween('clientes.created_at', [$fecha1,$fecha2])//Clientes dados de alta en la fecha seleccionada
                    ->whereBetween('contratos.fecha', [$fecha3,$fecha4]);//Contratos creados en las fechas seleccionadas
                    if($proyecto != '')//Filtro por proyecto
                        $contratos = $contratos->where('lotes.fraccionamiento_id','=',$proyecto);
                    $contratos = $contratos->count();
        return $contratos;
    }
    // Función para retorna los datos para generar el Reporte de Inicio, Termino, Ventas y Cobranza
    public function reporteLotesVentas(Request $request){
        //if(!$request->ajax())return redirect('/');
        $empresa = $request->emp_constructora;
        // Query para obtener las etapas registradas
        $lotes = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                        ->select('fraccionamientos.id as proyectoId','etapas.id as etapaId',
                                    'etapas.num_etapa','fraccionamientos.nombre as proyecto')
                        ->orderBy('proyecto','asc')->orderBy('num_etapa','asc')->get();
        //Inicialización de variables para almacenar los totales.
        $total1= $total2= $total3= $total4= $total5= $total6= $total7= $total8= $total9= $total10= $total11 = 0;
        // Se recorren los resultados obtenidos.
        foreach($lotes as $index => $lote){
            //Query para obtener el total de lotes en la etapa
            $lote->lotes = Lote::where('fraccionamiento_id','=',$lote->proyectoId)
                                ->where('etapa_id','=',$lote->etapaId);
            if($empresa != '')//Filtro por empresa constructora.
                $lote->lotes = $lote->lotes->where('lotes.emp_constructora','=', $empresa);
            $lote->lotes = $lote->lotes->count();

        /// CONSULTAS PARA TODAS LAS DISPONIBLES (lotes.contrato = 0)
            $lote->terminadaDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','>',97)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId);
                if($empresa != '')//Filtro para empresa constructora
                    $lote->terminadaDisponible = $lote->terminadaDisponible->where('lotes.emp_constructora','=', $empresa);
            $lote->terminadaDisponible = $lote->terminadaDisponible->count();

        //  Consulta para obtener los lotes disponibles en proceso de construcción
            $lote->procesoDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->whereBetween('licencias.avance', [1, 97]) //Avance entre 1 al 97 %
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId);
                if($empresa != '')//Filtro para empresa constructora
                    $lote->procesoDisponible = $lote->procesoDisponible->where('lotes.emp_constructora','=', $empresa);
            $lote->procesoDisponible = $lote->procesoDisponible->count();

            //  Consulta para obtener los lotes disponibles en sin avance de construcción
            $lote->sinAvanceDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','=',0)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId);
                if($empresa != '')//Filtro para empresa constructora
                    $lote->sinAvanceDisponible = $lote->sinAvanceDisponible->where('lotes.emp_constructora','=', $empresa);
            $lote->sinAvanceDisponible = $lote->sinAvanceDisponible->count();

        // CONSULTAS PARA LOS CONTRATOS COBRADOS (Créditos Directos individualizados)
            $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.liquidado','=',1)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId);
                if($empresa != '')//Filtro para empresa constructora
                    $indivContado = $indivContado->where('lotes.emp_constructora','=', $empresa);
            $indivContado = $indivContado->distinct('contratos.id')->count('contratos.id');

        // CONSULTAS PARA LOS CONTRATOS INDIVIDUALIZADOs (Ventas por financiamiento bancario)
            $indivCredito = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.fecha_firma_esc','!=',NULL)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId);
                if($empresa != '')//Filtro para empresa constructora
                    $indivCredito = $indivCredito->where('lotes.emp_constructora','=', $empresa);
            $indivCredito = $indivCredito->distinct('contratos.id')->count('contratos.id');

            $lote->cobradas = $indivContado + $indivCredito;//Se calcula el total de contratos individualizados.

        //Contratos por venta Directa con lotes en Proceso vendidas no cobradas
            $procVenNoCobrada1 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->where('expedientes.liquidado','=',0)//Sin liquidar
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                         ->whereBetween('licencias.avance', [1, 97]);//avance menor al 97%
                if($empresa != '')//Filtro para empresa constructora
                    $procVenNoCobrada1 = $procVenNoCobrada1->where('lotes.emp_constructora','=', $empresa);
            $procVenNoCobrada1 = $procVenNoCobrada1->distinct('contratos.id')->count('contratos.id');

        //Contratos con financiamiento bancario con lotes en Proceso vendidas no cobradas
            $procVenNoCobrada2 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->whereNull('expedientes.fecha_firma_esc')//Sin firma de escrituras
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                         ->whereBetween('licencias.avance', [1, 97]);//avance menor al 97%
                if($empresa != '')//Filtro para empresa constructora
                    $procVenNoCobrada2 = $procVenNoCobrada2->where('lotes.emp_constructora','=', $empresa);
            $procVenNoCobrada2 = $procVenNoCobrada2->distinct('contratos.id')->count('contratos.id');

            //Contratos con estatus Pendiete (Sin firma de contrato de compra venta) con lotes en Proceso
            $procVenNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',1)//Estatus pendiente
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                         ->whereBetween('licencias.avance', [1, 97]);//avance menor al 97%
                if($empresa != '')//Filtro para empresa constructora
                    $procVenNoCobrada3 = $procVenNoCobrada3->where('lotes.emp_constructora','=', $empresa);
            $procVenNoCobrada3 = $procVenNoCobrada3->count('contratos.id');

            //Contratos con estatus Firmado (Firma de contrato de compra venta) y sin integrar expediente con lotes en Proceso
            $procVenNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)//Estatus firmado
                                        ->where('contratos.integracion','=',0)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                         ->whereBetween('licencias.avance', [1, 97]);//Avance menor al 97%
                if($empresa != '')//Filtro para empresa constructora
                    $procVenNoCobrada4 = $procVenNoCobrada4->where('lotes.emp_constructora','=', $empresa);
            $procVenNoCobrada4 = $procVenNoCobrada4->count('contratos.id');

            //Sumatoria de contratos con lotes en proceso sin cobrar
            $lote->procVendidaNoCobrada = $procVenNoCobrada2 + $procVenNoCobrada1  + $procVenNoCobrada3 + $procVenNoCobrada4;

            //Contratos por venta Directa con lotes terminados vendidas no cobradas
            $termVendidaNoCobrada1 = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)//Estatus firmado
                                        ->where('contratos.integracion','=',1)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('expedientes.liquidado','=',0)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',97);//Avane mayor al 97%
                if($empresa != '')//Filtro para empresa constructora
                    $termVendidaNoCobrada1 = $termVendidaNoCobrada1->where('lotes.emp_constructora','=', $empresa);
            $termVendidaNoCobrada1 = $termVendidaNoCobrada1->distinct('contratos.id')->count('contratos.id');

            //Contratos por venta con financiamiento bancario con lotes terminados vendidas no cobradas
            $termVendidaNoCobrada2 = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)//Estatus firmado
                                        ->where('contratos.integracion','=',1)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->whereNull('expedientes.fecha_firma_esc')
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',97);//Avance mayor al 97%
                if($empresa != '')//Filtro para empresa constructora
                    $termVendidaNoCobrada2 = $termVendidaNoCobrada2->where('lotes.emp_constructora','=', $empresa);
            $termVendidaNoCobrada2 = $termVendidaNoCobrada2->distinct('contratos.id')->count('contratos.id');

            //Contratos con estatus Pendiente (Sin firma de contrato de compra venta) y sin integrar expediente con lotes terminados
            $termVendidaNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',97)//Avance mayor al 97%
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('contratos.integracion','=',0)
                                        ->where('contratos.status','=',1);//Estatus pendiente
                if($empresa != '')//Filtro para empresa consturctora
                    $termVendidaNoCobrada3 = $termVendidaNoCobrada3->where('lotes.emp_constructora','=', $empresa);
            $termVendidaNoCobrada3 = $termVendidaNoCobrada3->count('contratos.id');

            //Contratos con estatus Firmado (Firma de contrato de compra venta) y sin integrar expediente con lotes terminados
            $termVendidaNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)//Estatus firmado
                                        ->where('contratos.integracion','=',0)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',97);//Avance mayor al 97%
                if($empresa != '')//Filtro para empresa constructora
                    $termVendidaNoCobrada4 = $termVendidaNoCobrada4->where('lotes.emp_constructora','=', $empresa);
            $termVendidaNoCobrada4 = $termVendidaNoCobrada4->count('contratos.id');

            //Casas muestras finalizadas
            $lote->muestraTerminada = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->where('licencias.avance','>',97)//Avance mayor al 97%
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId);
                if($empresa != '')//Filtro por empresa constructora
                    $lote->muestraTerminada = $lote->muestraTerminada->where('lotes.emp_constructora','=', $empresa);
            $lote->muestraTerminada = $lote->muestraTerminada->count();

            //Casas muestra en proceso de construcción
            $lote->muestraProceso = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->whereBetween('licencias.avance', [0, 97])//Avance menor al 97%
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId);
                if($empresa != '')//Filtro para empresa constructora
                    $lote->muestraProceso = $lote->muestraProceso->where('lotes.emp_constructora','=', $empresa);
            $lote->muestraProceso = $lote->muestraProceso->count();

            //Sumatoria de contratos con lotes terminados sin cobrar
            $lote->termVendidaNoCobrada = $termVendidaNoCobrada1 + $termVendidaNoCobrada2 + $termVendidaNoCobrada3 + $termVendidaNoCobrada4;
            //Sumatoria total de lotes terminados sin cobrar
            $lote->terminadaNoCobrada = $lote->terminadaDisponible + $lote->termVendidaNoCobrada;
            //Sumatoria total de lotes en proceso sin cobrar
            $lote->procesoNoCobrada = $lote->procVendidaNoCobrada + $lote->procesoDisponible;

            $total1 += $lote->lotes;
            $total2 += $lote->cobradas;
            $total3 += $lote->terminadaNoCobrada;
            $total4 += $lote->termVendidaNoCobrada;
            $total5 += $lote->terminadaDisponible;
            $total6 += $lote->procesoNoCobrada;
            $total7 += $lote->procVendidaNoCobrada;
            $total8 += $lote->procesoDisponible;
            $total9 += $lote->sinAvanceDisponible;
            $total10 += $lote->muestraTerminada;
            $total11 += $lote->muestraProceso;

        }

        return ['lotes'=>$lotes,
                'total1'=>$total1,
                'total2'=>$total2,
                'total3'=>$total3,
                'total4'=>$total4,
                'total5'=>$total5,
                'total6'=>$total6,
                'total7'=>$total7,
                'total8'=>$total8,
                'total9'=>$total9,
                'total10'=>$total10,
                'total11'=>$total11
            ];
    }
    // Función para retorna el Reporte de Inicio, Termino, Ventas y Cobranza en excel
    public function excelReporteLotesVentas(Request $request){
        //Llamada a la funcion que retorna los datos necesarios
        $reporte = $this->reporteLotesVentas($request);
        //Se inicializan las variables con la informacion obtenida
        $lotes = $reporte['lotes'];
        $total1 = $reporte['total1'];
        $total2 = $reporte['total2'];
        $total3 = $reporte['total3'];
        $total4 = $reporte['total4'];
        $total5 = $reporte['total5'];
        $total6 = $reporte['total6'];
        $total7 = $reporte['total7'];
        $total8 = $reporte['total8'];
        $total9 = $reporte['total9'];
        $total10 = $reporte['total10'];
        $total11 = $reporte['total11'];

        //Creación y retorno de excel.
        return Excel::create('Reporte', function($excel) use ($lotes, $total1, $total2, $total3, $total4, $total5, $total6, $total7, $total8, $total9, $total10, $total11){
            $excel->sheet('Reoorte lotes', function($sheet) use ($lotes, $total1, $total2, $total3, $total4, $total5, $total6, $total7, $total8, $total9, $total10, $total11){
                //Encabezados
                $sheet->row(1, [
                    'Fraccionamiento',
                    'Etapa',
                    'Total Casas o Lotes',
                    'Cobradas',
                    'Terminadas No Cobradas',
                    'Terminadas Vendidas No Cobradas',
                    'Terminadas Disponibles',
                    'En Proceso de Construccion No Cobradas',
                    'En Proceso Vendidas No Cobradas',
                    'En Proceso Disponible',
                    'Disponibles Sin Avance',
                    'Casa muestra terminada',
                    'Casa muestra en proceso'
                ]);

                //Formato a encabezado
                $sheet->cells('A1:K1', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $cont=3;
                $renglon = 2;
                //Se recorre el arreglo
                foreach($lotes as $index => $lote) {
                    $cont++;
                    //Se valida que el proyecto a mostrar tenga información
                    if($lote->lotes != 0){
                        //Llenado del renglon
                        $sheet->row($renglon, [
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->lotes,
                            $lote->cobradas,
                            $lote->terminadaNoCobrada,
                            $lote->termVendidaNoCobrada,
                            $lote->terminadaDisponible,
                            $lote->procesoNoCobrada,
                            $lote->procVendidaNoCobrada,
                            $lote->procesoDisponible,
                            $lote->sinAvanceDisponible,
                            $lote->muestraTerminada,
                            $lote->muestraProceso
                        ]);
                        $renglon ++;
                    }
                }
                //Totales
                $sheet->row($renglon+2,[
                    '',
                    'Total',
                    $total1,
                    $total2,
                    $total3,
                    $total4,
                    $total5,
                    $total6,
                    $total7,
                    $total8,
                    $total9,
                    $total10,
                    $total11,

                ]);

                $renglon = $renglon + 2;
                $num='A1:M' . $renglon;
                $sheet->setBorder($num, 'thin');
                $sheet->cells('A'.$renglon.':'.'M'.$renglon, function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(11);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                });
            });
        }
        )->download('xls');
    }
    //Función para retornar los datos para el reporte de ventas y cancelaciones.
    public function reporteVentas(Request $request){
        //Llamada a la funcion que retorna los registros de ventas
        $ventas = $this->getVentas($request);
        //Llamada a la funcion que retorna los registros de cancelaciones
        $cancelaciones = $this->getCancelaciones($request);
        $contVentas = $ventas->count();//Se almacena el total de ventas
        $contCancelaciones = $cancelaciones->count();//Se almacena el total de cancelaciones
        //Llamada a la función que retorna los registros de reubicaciones
        $reubicaciones = $this->getReubicaciones($request);
        $contReubicaciones = $reubicaciones->count();//Se almacena el total de reubicaciones.

        return[
            'ventas'=>$ventas,
            'contVentas'=>$contVentas,
            'contCancelaciones'=>$contCancelaciones,
            'cancelaciones'=>$cancelaciones,
            'reubicaciones'=>$reubicaciones,
            'contReubicaciones'=>$contReubicaciones,
        ];
    }
    //Función privada que retorna los registros de reubicaciones
    private function getReubicaciones(Request $request){
        $empresa = $request->empresa;
        $empresa2 = $request->empresa2;
        $publicidad = $request->publicidad;
        //Query principal
        $reubicaciones = Reubicacion::join('lotes','reubicaciones.lote_id','=','lotes.id')
                                ->join('contratos','reubicaciones.contrato_id','=','contratos.id')
                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                ->join('etapas','lotes.etapa_id','=','etapas.id')
                                ->join('personal as cliente','reubicaciones.cliente_id','=','cliente.id')
                                ->join('personal as asesor','reubicaciones.asesor_id','=','asesor.id')
                                ->select('reubicaciones.*',
                                        'lotes.num_lote','lotes.manzana','lotes.emp_constructora','lotes.emp_terreno',
                                        'etapas.num_etapa as etapa', 'fraccionamientos.nombre as proyecto',
                                        'cliente.nombre as c_nombre', 'cliente.apellidos as c_apellidos',
                                        'asesor.nombre as a_nombre', 'asesor.apellidos as a_apellidos'
                                )
                                //Fechas de busqueda
                                ->whereBetween('reubicaciones.fecha_reubicacion', [$request->fecha, $request->fecha2]);
                        if($empresa != '')//Filtro por empresa constructora
                            $reubicaciones = $reubicaciones->where('lotes.emp_constructora','=', $empresa);
                        if($empresa2 != '')//Filtro por empresa de terreno
                            $reubicaciones = $reubicaciones->where('lotes.emp_terreno','=', $empresa2);
                        if($publicidad != '')//Filtro por publicidad
                            $reubicaciones = $reubicaciones->where('contratos.publicidad_id','=', $publicidad);
                        $reubicaciones = $reubicaciones->orderBy('reubicaciones.fecha_reubicacion','asc')
                        ->get();
        //Se recorren los resultados obtenidos
        foreach ($reubicaciones as $key => $reubicacion) {
            //Query para obtener los datos concretos de la venta relacionada a la reubicacion
            $reubicacion->venta = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('personal as cliente','creditos.prospecto_id','=','cliente.id')
                    ->join('personal as asesor','creditos.vendedor_id','=','asesor.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->select(
                        'lotes.num_lote','lotes.manzana','lotes.emp_constructora','lotes.emp_terreno','lotes.firmado',
                        'contratos.id', 'contratos.fecha', 'etapas.num_etapa as etapa',
                        'creditos.valor_terreno', 'creditos.promocion',
                        'fraccionamientos.nombre as proyecto',
                        'cliente.nombre as c_nombre', 'cliente.apellidos as c_apellidos',
                        'asesor.nombre as a_nombre', 'asesor.apellidos as a_apellidos',
                        'ins.tipo_credito','ins.institucion','contratos.status'
                    )
                    ->where('ins.elegido','=',1)
                    ->where('contratos.id','=',$reubicacion->contrato_id)->first();

        }
        return $reubicaciones;
    }
    //Función privada que retorna los registros de cancelaciones
    private function getCancelaciones(Request $request){
        $empresa = $request->empresa;
        $empresa2 = $request->empresa2;
        $publicidad = $request->publicidad;
        //Query principal para obtener los registros de cancelaciones
        $cancelaciones = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('personal as a','creditos.vendedor_id','=','a.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta',
                                'contratos.avance_lote', 'modelos.nombre as modelo',
                                'a.nombre as a_nombre','a.apellidos as a_apellidos',
                                'medios_publicitarios.nombre as publicidad', 'contratos.publicidad_id',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete','contratos.motivo_cancel',
                                'contratos.fecha_status')
                        ->where('ins.elegido','=',1)
                        ->where('contratos.status','=',0)//Estatus cancelado
                        //Busqueda por rango de fechas
                        ->whereBetween('contratos.fecha_status', [$request->fecha, $request->fecha2]);
                        if($empresa != '')//Filtro para empresa constructora
                            $cancelaciones = $cancelaciones->where('lotes.emp_constructora','=', $empresa);
                        if($empresa2 != '')//Filtro para empresa de terreno
                            $cancelaciones = $cancelaciones->where('lotes.emp_terreno','=', $empresa2);
                        if($publicidad != '')//Filtro para medio de publicidad
                            $cancelaciones = $cancelaciones->where('contratos.publicidad_id','=', $publicidad);
                        $cancelaciones = $cancelaciones->orderBy('contratos.fecha_status','asc')
                        ->get();
                    return $cancelaciones;

    }
    //Función privada que retorna los registros de ventas
    private function getVentas(Request $request){
        $empresa = $request->empresa;
        $empresa2= $request->empresa2;
        $publicidad = $request->publicidad;
        //Query principal que retorna los registros de ventas
        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('personal as a','creditos.vendedor_id','=','a.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'modelos.nombre as modelo',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete', 'lotes.firmado',
                                'creditos.costo_descuento', 'creditos.descuento_terreno', 'creditos.costo_alarma',
                                'creditos.costo_cuota_mant', 'creditos.costo_protecciones','contratos.id',
                                'medios_publicitarios.nombre as publicidad','contratos.publicidad_id',
                                'contratos.avance_lote', 'contratos.motivo_cancel',
                                'a.nombre as a_nombre','a.apellidos as a_apellidos',
                                'creditos.rfc_fisc', 'creditos.archivo_fisc','creditos.fecha_archivo', 'creditos.fecha_rfc',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta','contratos.status')
                        ->whereIn('contratos.status',[0,1,3])//Estatus firmado,pendiente y cancelado
                        ->where('ins.elegido','=',1)
                        //Busqueda por rango de fechas
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2]);
                        if($empresa != '')//Filtro de empresa constructora
                            $ventas = $ventas->where('lotes.emp_constructora','=', $empresa);
                        if($empresa2 != '')//Filtro para empresa de terreno
                            $ventas = $ventas->where('lotes.emp_terreno','=', $empresa2);
                        if($publicidad != '')//Filtro para medio de publicidad
                            $ventas = $ventas->where('contratos.publicidad_id','=', $publicidad);
                        $ventas = $ventas->orderBy('contratos.status','desc')
                        ->orderBy('contratos.fecha','asc')
                        ->get();

                        return $ventas;
    }
    //Función para retornar los datos para el reporte de ventas y cancelaciones en Excel.
    public function reporteVentasExcel(Request $request){
        $empresa = $request->empresa;
        //Llamada a la funcion que retorna los registros de ventas
        $ventas = $this->getVentas($request);
        //Llamada a la funcion que retorna los registros de cancelaciones
        $cancelaciones = $this->getCancelaciones($request);
                //Se inicializan variables para fechas
                        setlocale(LC_TIME, 'es_MX.utf8');
                        $fecha1 = new Carbon($request->fecha);
                        $fecha1 = $fecha1->formatLocalized('%d de %B de %Y');

                        $fecha2 = new Carbon($request->fecha2);
                        $fecha2 = $fecha2->formatLocalized('%d de %B de %Y');

                        $periodo = 'Del '.$fecha1.' al '.$fecha2;
        //Llamada a la función que retorna los registros de reubicaciones
        $reubicaciones = $this->getReubicaciones($request);
        //Creación y retorno de los resultados en Excel.
        return Excel::create('Reporte de ventas y cancelaciones', function($excel) use ($ventas,$cancelaciones,$periodo,$empresa, $reubicaciones){
            $excel->sheet('Ventas', function($sheet) use ($ventas,$periodo, $empresa){

                $sheet->mergeCells('A1:M1');
                $sheet->mergeCells('A2:M2');
                $sheet->mergeCells('C4:G4');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE C.V.');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');

                });
                $sheet->cell('A2', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE VENTAS');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');

                });

                $sheet->cell('B4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');

                });

                $sheet->row(4,[
                    '',
                    'Periodo: ',
                    $periodo
                ]);

                $sheet->cell('C4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                });

                $sheet->setColumnFormat(array(

                    'N' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'P' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                    'S' => '$#,##0.00',
                    'T' => '$#,##0.00',
                    'U' => '$#,##0.00',
                ));

                if($empresa == 'CONCRETANIA'){

                    $sheet->row(8, [
                        'No.',
                        'Fraccionamiento',
                        'Etapa',
                        'Manzana',
                        'Modelo',
                        'Lote',
                        'Cliente',
                        'Asesor',
                        'Fecha de venta',
                        'Crédito',
                        'Institución',
                        'Medio Publicitario',
                        'Promoción / Paquete',
                        'Valor de escrituración',
                        'Valor de terreno',
                        'Valor de construccion',
                        'Descuento precio de casa o equipamiento',
                        'Descuento en el terreno',
                        'Costo de Alarma',
                        'Cuota de mantenimiento',
                        'Protecciones'
                    ]);


                    $sheet->cells('A8:U8', function ($cells) {
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(12);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $cont=9;



                    foreach($ventas as $index => $lote) {
                        $cont++;

                        $paquete = '';

                        $status='';
                        if($lote->status == 0)
                            $status = 'Cancelado';
                        elseif($lote->status == 1 || $lote->status == 3 && $lote->firmado == 0)
                            $status = 'Vendida';
                        elseif($lote->status == 3 && $lote->firmado == 1)
                            $status = 'Individualizada';

                        if($lote->descripcion_promocion == null && $lote->descripcion_paquete == null)
                            $paquete = '';
                        elseif($lote->descripcion_promocion != null && $lote->descripcion_paquete == null)
                            $paquete = 'Promo: '.$lote->descripcion_promocion;
                        elseif($lote->descripcion_promocion == null && $lote->descripcion_paquete != null)
                            $paquete = 'Paquete: '.$lote->descripcion_paquete;
                        else
                            $paquete = 'Promo: ' . $lote->descripcion_promocion . ' / Paquete:' . $lote->descripcion_paquete;

                        $sheet->row($index+9, [
                            $index+1,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->modelo,
                            $lote->num_lote,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->a_nombre.' '.$lote->a_apellidos,
                            $lote->fecha,
                            $lote->tipo_credito,
                            $lote->institucion,
                            $lote->publicidad,
                            $paquete,
                            $lote->precio_venta,
                            $lote->valor_terreno,
                            $lote->precio_venta - $lote->valor_terreno,
                            $lote->costo_descuento,
                            $lote->descuento_terreno,
                            $lote->costo_alarma,
                            $lote->costo_cuota_mant,
                            $lote->costo_protecciones,
                            $status,
                        ]);
                    }

                    $num='A8:T' . $cont;
                    $sheet->setBorder($num, 'thin');

                }
                else{
                    $sheet->row(8, [
                        'No.',
                        'Fraccionamiento',
                        'Etapa',
                        'Manzana',
                        'Modelo',
                        'Lote',
                        'Cliente',
                        'Asesor',
                        'Fecha de venta',
                        'Crédito',
                        'Institución',
                        'Medio Publicitario',
                        'Promoción / Paquete',
                        'Valor de escrituración',

                        'Descuento precio de casa o equipamiento',
                        'Descuento en el terreno',
                        'Costo de Alarma',
                        'Cuota de mantenimiento',
                        'Protecciones'
                    ]);


                    $sheet->cells('A8:U8', function ($cells) {
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(12);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $cont=9;



                    foreach($ventas as $index => $lote) {
                        $cont++;

                        $paquete = '';

                        $status='';
                        if($lote->status == 0)
                            $status = 'Cancelado';
                        elseif($lote->status == 1 || $lote->status == 3 && $lote->firmado == 0)
                            $status = 'Vendida';
                        elseif($lote->status == 3 && $lote->firmado == 1)
                            $status = 'Individualizada';

                        if($lote->descripcion_promocion == null && $lote->descripcion_paquete == null)
                            $paquete = '';
                        elseif($lote->descripcion_promocion != null && $lote->descripcion_paquete == null)
                            $paquete = 'Promo: '.$lote->descripcion_promocion;
                        elseif($lote->descripcion_promocion == null && $lote->descripcion_paquete != null)
                            $paquete = 'Paquete: '.$lote->descripcion_paquete;
                        else
                            $paquete = 'Promo: ' . $lote->descripcion_promocion . ' / Paquete:' . $lote->descripcion_paquete;

                        $sheet->row($index+9, [
                            $index+1,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->modelo,
                            $lote->num_lote,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->a_nombre.' '.$lote->a_apellidos,
                            $lote->fecha,
                            $lote->tipo_credito,
                            $lote->institucion,
                            $lote->publicidad,
                            $paquete,
                            $lote->precio_venta,
                            $lote->costo_descuento,
                            $lote->descuento_terreno,
                            $lote->costo_alarma,
                            $lote->costo_cuota_mant,
                            $lote->costo_protecciones,
                            $status,
                        ]);
                    }

                    $num='A8:U' . $cont;
                    $sheet->setBorder($num, 'thin');
                }




            });

            $excel->sheet('Cancelaciones', function($sheet) use ($cancelaciones,$periodo, $empresa){

                $sheet->mergeCells('A1:N1');
                $sheet->mergeCells('A2:N2');
                $sheet->mergeCells('C4:G4');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE C.V.');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');

                });
                $sheet->cell('A2', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE CANCELACIONES');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');

                });

                $sheet->cell('B4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');

                });

                $sheet->row(4,[
                    '',
                    'Periodo: ',
                    $periodo
                ]);

                $sheet->cell('C4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                });

                $sheet->setColumnFormat(array(
                    'O' => '$#,##0.00',
                    'P' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                ));

                if($empresa == 'CONCRETANIA'){

                    $sheet->row(8, [
                        'No.',
                        'Fraccionamiento',
                        'Etapa',
                        'Manzana',
                        'Modelo',
                        'Lote',
                        'Cliente',
                        'Asesor',
                        'Fecha de cancelación',
                        'Fecha de venta',
                        'Crédito',
                        'Institución',
                        'Medio Publicitario',
                        'Promoción / Paquete',
                        'Valor de escrituración',
                        'Valor de terreno',
                        'Valor de construccion'
                    ]);


                    $sheet->cells('A8:M8', function ($cells) {
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(12);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $cont=9;



                    foreach($cancelaciones as $index => $lote) {
                        $cont++;

                        $paquete = '';

                        if($lote->descripcion_promocion == null && $lote->descripcion_paquete == null)
                            $paquete = '';
                        elseif($lote->descripcion_promocion != null && $lote->descripcion_paquete == null)
                            $paquete = 'Promo: '.$lote->descripcion_promocion;
                        elseif($lote->descripcion_promocion == null && $lote->descripcion_paquete != null)
                            $paquete = 'Paquete: '.$lote->descripcion_paquete;
                        else
                            $paquete = 'Promo: ' . $lote->descripcion_promocion . ' / Paquete:' . $lote->descripcion_paquete;

                        $sheet->row($index+9, [
                            $index+1,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->modelo,
                            $lote->num_lote,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->a_nombre.' '.$lote->a_apellidos,
                            $lote->fecha_status,
                            $lote->fecha,
                            $lote->tipo_credito,
                            $lote->institucion,
                            $lote->publicidad,
                            $paquete,
                            $lote->precio_venta,
                            $lote->valor_terreno,
                            $lote->precio_venta - $lote->valor_terreno
                        ]);
                    }

                    $num='A8:O' . $cont;
                    $sheet->setBorder($num, 'thin');

                }
                else{
                    $sheet->row(8, [
                        'No.',
                        'Fraccionamiento',
                        'Etapa',
                        'Manzana',
                        'Modelo',
                        'Lote',
                        'Cliente',
                        'Asesor',
                        'Fecha de cancelación',
                        'Fecha de venta',
                        'Crédito',
                        'Institución',
                        'Medio Publicitario',
                        'Promoción / Paquete',
                        'Valor de escrituración',

                    ]);


                    $sheet->cells('A8:M8', function ($cells) {
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(12);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $cont=9;



                    foreach($cancelaciones as $index => $lote) {
                        $cont++;

                        $paquete = '';

                        if($lote->descripcion_promocion == null && $lote->descripcion_paquete == null)
                            $paquete = '';
                        elseif($lote->descripcion_promocion != null && $lote->descripcion_paquete == null)
                            $paquete = 'Promo: '.$lote->descripcion_promocion;
                        elseif($lote->descripcion_promocion == null && $lote->descripcion_paquete != null)
                            $paquete = 'Paquete: '.$lote->descripcion_paquete;
                        else
                            $paquete = 'Promo: ' . $lote->descripcion_promocion . ' / Paquete:' . $lote->descripcion_paquete;

                        $sheet->row($index+9, [
                            $index+1,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->modelo,
                            $lote->num_lote,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->a_nombre.' '.$lote->a_apellidos,
                            $lote->fecha_status,
                            $lote->fecha,
                            $lote->tipo_credito,
                            $lote->institucion,
                            $lote->publicidad,
                            $paquete,
                            $lote->precio_venta,

                        ]);
                    }

                    $num='A8:P' . $cont;
                    $sheet->setBorder($num, 'thin');
                }


            });

            $excel->sheet('Reubicaciones', function($sheet) use ($reubicaciones,$periodo, $empresa){

                $sheet->mergeCells('A1:O1');
                $sheet->mergeCells('A2:O2');
                $sheet->mergeCells('C4:G4');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE C.V.');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');

                });
                $sheet->cell('A2', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE REUBICACIONES');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');

                });

                $sheet->cell('B4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');

                });

                $sheet->row(4,[
                    '',
                    'Periodo: ',
                    $periodo
                ]);

                $sheet->cell('C4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                });

                $sheet->setColumnFormat(array(

                    'I' => '$#,##0.00'
                ));


                    $sheet->row(8, [
                        'Fecha',
                        'Folio',
                        'Fraccionamiento',
                        'Etapa',
                        'Manzana',
                        'Lote',
                        'Asesor',
                        'Cliente',
                        'Valor de terreno',
                        'Tipo de Crédito',
                        'Institución',
                        'Promoción',
                        'Emp Constructora',
                        'Emp Terreno',
                        'Observación'
                    ]);


                    $sheet->cells('A8:O8', function ($cells) {
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(12);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $cont=9;



                    foreach($reubicaciones as $index => $lote) {

                        $sheet->row($index+$cont, [
                            $lote->fecha_reubicacion,
                            $lote->contrato_id,
                            $lote->proyecto,
                            $lote->etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->a_nombre.' '.$lote->a_apellidos,
                            $lote->c_nombre.' '.$lote->c_apellidos,
                            $lote->valor_terreno,
                            $lote->tipo_credito,
                            $lote->institucion,
                            $lote->promocion,
                            $lote->emp_constructora,
                            $lote->emp_terreno,
                            $lote->observacion
                        ]);
                        $sheet->row($index+$cont+1, [
                            $lote->venta->fecha,
                            $lote->venta->id,
                            $lote->venta->proyecto,
                            $lote->venta->etapa,
                            $lote->venta->manzana,
                            $lote->venta->num_lote,
                            $lote->venta->a_nombre.' '.$lote->venta->a_apellidos,
                            $lote->venta->c_nombre.' '.$lote->venta->c_apellidos,
                            $lote->venta->valor_terreno,
                            $lote->venta->tipo_credito,
                            $lote->venta->institucion,
                            $lote->venta->promocion,
                            $lote->venta->emp_constructora,
                            $lote->venta->emp_terreno,
                            '',
                        ]);

                        $sheet->row($index+$cont+2, [
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                        ]);

                        $cont+=2;
                    }

                    $num='A8:O' . $cont;
                    $sheet->setBorder($num, 'thin');



            });

        }

        )->download('xls');
    }
    //Función para retornar los datos de reporte acumulado
    //Expedientes, Escrituras e Ingresos de Créditos
    public function reporteAcumulado(Request $request){
        $opcion = $request->opcion;
        $mes = $request->mes;
        $anio = $request->anio;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $empresa = $request->empresa;

        //Se elige el tipo de reporte a generar
        switch($opcion){
            //Reporte de expedientes
            case 'Expedientes':{
                //Llamadas a las funciones privadas que obtienen los datos necesarios
                $expCreditos = $this->getRepExpedientes($mes, $anio, $empresa);//Depositos de Crédito ingresados en el periodo seleccionado
                $expContado = $this->getRepExpContado($mes, $anio, $empresa);//Contratos de Crédito Directo con firmas de escrituras en el periodo
                $sinEntregar = $this->getSinEntregarRep($mes,$anio, $empresa);//Contratos con firma de escrituras, pero pendientes por cobrar financiamiento bancario
                return ['expCreditos'=>$expCreditos,
                    'expContado'=>$expContado,
                    'pendientes'=>$sinEntregar

                ];
                break;
            }
            case 'Escrituras':{
                $escrituras = $this->getEscriturasRep($mes, $anio, $empresa);//Ventas con escrituras firmadas en el periodo seleccionado.
                $contadoSinEscrituras = $this->getContadoSinEscrituras($mes, $anio, $empresa);//Ventas directas liquidadas sin firma de escrituras.
                return [
                    'escrituras'=>$escrituras,
                    'contadoSinEscrituras'=>$contadoSinEscrituras
                ];
                break;
            }
            case 'Ingresos':{
                $ingresosCobranza = $this->getIngresosCobranza($fecha1,$fecha2, $empresa);//Ingresos institucionales realizados en el periodo seleccionado.
                return ['ingresosCobranza'=>$ingresosCobranza];
                break;
            }
        }

    }
    //Función para retornar los datos de reporte acumulado de expedientes en Excel
    public function excelExpedientes(Request $request){
        $mes = $request->mes;
        $anio = $request->anio;
        $empresa = $request->empresa;
        //Llamadas a las funciones privadas que obtienen los datos necesarios
        $expCreditos = $this->getRepExpedientes($mes, $anio, $empresa);//Depositos de Crédito ingresados en el periodo seleccionado
        $expContado = $this->getRepExpContado($mes, $anio, $empresa);//Contratos de Crédito Directo con firmas de escrituras en el periodo
        $sinEntregar = $this->getSinEntregarRep($mes,$anio, $empresa);//Contratos con firma de escrituras, pero pendientes por cobrar financiamiento bancario

        //Creación y retorno de los resultados en Excel.
        return Excel::create('Reporte de expedientes', function($excel) use ($expCreditos,$expContado,$sinEntregar){
            $excel->sheet('Expedientes', function($sheet) use ($expCreditos,$sinEntregar){
                $sheet->mergeCells('A1:H4');
                $sheet->mergeCells('A5:H5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A5', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE EXPEDIENTES ENTREGADOS');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cell('C6', function($cell) {
                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cells('A7:H7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');
                    // Set font size
                    $cells->setFontSize(11);
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row(7,[
                    '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Emp. Const.'
                ]);

                $cont = 7;

                foreach($expCreditos as $index => $lote) {
                    if($lote->flag == 1 && $lote->mes == 1){
                        $cont++;

                        $sheet->row($cont, [
                            $lote->id,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->tipo_credito,
                            $lote->emp_constructora
                        ]);
                    }
                }
                $num='A5:H' . $cont;
                $sheet->setBorder($num, 'thin');

            });

            $excel->sheet('Expedientes de contado', function($sheet) use ($expContado){
                $sheet->mergeCells('A1:H4');
                $sheet->mergeCells('A5:H5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');

                });
                $sheet->cell('A5', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE EXPEDIENTES DE CONTADO' );
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');

                });

                $sheet->cell('C6', function($cell) {
                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');

                });

                $sheet->cells('A7:H7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');
                    // Set font size
                    $cells->setFontSize(11);
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row(7,[
                    '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Emp. Const.'
                ]);
                $cont = 7;

                foreach($expContado as $index => $lote) {
                        $cont++;
                        $sheet->row($cont, [
                            $lote->id,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->tipo_credito,
                            $lote->emp_constructora
                        ]);
                }

                $num='A5:H' . $cont;
                $sheet->setBorder($num, 'thin');
            });

            $excel->sheet('Pendientes de cobro', function($sheet) use ($sinEntregar){
                $sheet->mergeCells('A1:H4');
                $sheet->mergeCells('A5:H5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A5', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'PENDIENTES DE COBRO' );
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cell('C6', function($cell) {
                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cells('A7:H7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');
                    // Set font size
                    $cells->setFontSize(11);
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row(7,[
                    '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Emp. Const.'
                ]);
                $cont = 7;

                foreach($sinEntregar as $index => $lote) {
                        $cont++;
                        $sheet->row($cont, [
                            $lote->id,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->tipo_credito,
                            $lote->emp_constructora
                        ]);

                }

                $num='A5:H' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        })->download('xls');
    }
    //Función para retornar los datos de reporte acumulado de ingresos institucionales en Excel
    public function excelIngresos(Request $request){
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $empresa = $request->empresa;
        //Ingresos institucionales realizados en el periodo seleccionado.
        $ingresosCobranza = $this->getIngresosCobranza($fecha1,$fecha2, $empresa);
        //Creación y retorno de los resultados en excel.
        return Excel::create('Reporte mensual de ingresos', function($excel) use ($ingresosCobranza,$empresa){
            $excel->sheet('Cobranza institucional', function($sheet) use ($ingresosCobranza,$empresa){

                $sheet->mergeCells('A1:K4');
                $sheet->mergeCells('A5:K5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A5', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE INGRESOS (COBRANZA INSTITUCIONAL)');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('C6', function($cell) {
                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                    $sheet->cells('A7:K7', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Arial Narrow');
                        // Set font size
                        $cells->setFontSize(11);
                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $sheet->setColumnFormat(array('G' => '$#,##0.00'));

                    $sheet->row(7,[
                        '', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote',
                        'Monto Crédito Neto', 'Fecha', 'Banco','Empresa const', 'Firma de escrituras'
                    ]);

                    $cont = 7;
                    foreach($ingresosCobranza as $index => $lote) {
                        $cont++;
                        $sheet->row($cont, [
                            $index+1,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->cant_depo,
                            $lote->fecha_deposito,
                            $lote->banco,
                            $lote->emp_constructora,
                            $lote->escrituras
                        ]);
                    }

                    $num='A5:K' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        )->download('xls');
    }
    //Función para retornar los datos de reporte acumulado de escrituras en Excel
    public function excelEscrituras(Request $request){
        $mes = $request->mes;
        $anio = $request->anio;

        $empresa = $request->empresa;
        //Ventas escrituradas en el periodo seleccionado
        $escrituras = $this->getEscriturasRep($mes, $anio, $empresa);
        //Ventas liquidadas pendientes por escriturar
        $contadoSinEscrituras = $this->getContadoSinEscrituras($mes, $anio, $empresa);
        //Creación y retorno de los resultados en excel
        return Excel::create('Reporte mensual de escrituras', function($excel) use ($escrituras,$contadoSinEscrituras,$empresa){
            $excel->sheet('Ventas de crédito', function($sheet) use ($escrituras,$empresa){
                $sheet->mergeCells('A1:J4');
                $sheet->mergeCells('A5:J5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE C.V.');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A5', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'ESCRITURAS VENTAS DE CRÉDITO');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cell('C6', function($cell) {
                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cells('A7:J7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');
                    // Set font size
                    $cells->setFontSize(11);
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                if($empresa == 'CONCRETANIA'){
                    $sheet->cells('A7:L7', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Arial Narrow');
                        // Set font size
                        $cells->setFontSize(11);
                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $sheet->setColumnFormat(array(
                        'H' => '$#,##0.00',
                        'I' => '$#,##0.00',
                        'K' => '$#,##0.00'
                    ));

                    $sheet->row(7,[
                        '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Valor de terreno', 'Valor de casa', 'Fecha de firma de escrituras', 'Valor de escrituración', 'Notaria'
                    ]);

                    $cont = 7;

                    foreach($escrituras as $index => $lote) {
                        $cont++;
                        $sheet->row($cont, [
                            $lote->id,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->tipo_credito.' ('.$lote->institucion.')',
                            $lote->valor_terreno,
                            $lote->valor_escrituras-$lote->valor_terreno,
                            $lote->fecha_firma_esc,
                            $lote->valor_escrituras,
                            $lote->notaria
                        ]);
                    }

                    $num='A5:L' . $cont;
                }
                else{
                    $sheet->setColumnFormat(array('I' => '$#,##0.00'));
                    $sheet->row(7,[
                        '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Fecha de firma de escrituras', 'Valor de escrituración', 'Notaria'
                    ]);

                    $cont = 7;
                    foreach($escrituras as $index => $lote) {
                        $cont++;
                        $sheet->row($cont, [
                            $lote->id,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->tipo_credito.' ('.$lote->institucion.')',
                            $lote->fecha_firma_esc,
                            $lote->valor_escrituras,
                            $lote->notaria
                        ]);
                    }
                    $num='A5:J' . $cont;
                }
                $sheet->setBorder($num, 'thin');
            });

            $excel->sheet('Pendientes de escriturar',
                function($sheet) use ($contadoSinEscrituras){

                $sheet->mergeCells('A1:I4');
                $sheet->mergeCells('A5:I5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {
                    // manipulate the cell
                    $cell->setValue( '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A5', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'CONTADOS PENDIENTES DE ESCRITURAR' );
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cell('C6', function($cell) {
                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cells('A7:I7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');
                    // Set font size
                    $cells->setFontSize(11);
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row(7,[
                    '# Ref.', 'Cliente','Fraccionamiento', 'Etapa',
                    'Manzana', 'Lote', 'Crédito', 'Fecha de venta', 'Responsable'
                ]);

                $cont = 7;

                foreach($contadoSinEscrituras as $index => $lote) {
                    $cont++;
                    $sheet->row($cont, [
                        $lote->id,
                        $lote->nombre.' '.$lote->apellidos,
                        $lote->proyecto,
                        $lote->num_etapa,
                        $lote->manzana,
                        $lote->num_lote,
                        $lote->tipo_credito.' ('.$lote->institucion.')',
                        $lote->fecha,
                        $lote->nombre_gestor
                    ]);
                }

                $num='A5:I' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }

        )->download('xls');
    }
    //Función privada que retorna los Depositos de Crédito ingresados en el periodo seleccionado
    private function getRepExpedientes($mes, $anio, $empresa){
        $mes2 = $mes + 1;
        $fecha = $anio.'-'.$mes2.'-01';
        //Query para obtener todos los depositos agrupados por el credito.
        $depositos = Dep_credito::join('inst_seleccionadas as ins','dep_creditos.inst_sel_id','=','ins.id')
                ->join('creditos','ins.credito_id','=','creditos.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select(//'dep_creditos.inst_sel_id',
                            'contratos.id','p.nombre','p.apellidos',
                            'f.nombre as proyecto','et.num_etapa',
                            'lotes.manzana','lotes.num_lote', 'lotes.emp_constructora',
                            DB::raw("SUM(dep_creditos.cant_depo) as totalDep")
                            )
                ->where('dep_creditos.fecha_deposito','<',$fecha);//Fecha anteriores a la seleccionada.
                if($empresa != '')//Busqueda por empresa constructora
                    $depositos = $depositos->where('lotes.emp_constructora','=',$empresa);
                $depositos = $depositos->groupBy('ins.credito_id')//Agrupados por financiamiento.
                ->distinct()->get();
        if(sizeOf($depositos)){
            //Se recorren los resultados obtenidos
            foreach($depositos as $index => $deposito){
                //Se accede a la información del financiamiento.
                $inst = inst_seleccionada::select('monto_credito','segundo_credito','tipo_credito','institucion')
                        ->where('elegido','=',1)
                        ->where('credito_id','=',$deposito->id)->get();
                //Se obtienen los depositos que sean del mes y año elegido
                $act = Dep_credito::join('inst_seleccionadas as ins','dep_creditos.inst_sel_id','=','ins.id')
                        ->join('creditos','ins.credito_id','=','creditos.id')
                        ->join('contratos','creditos.id','=','contratos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('dep_creditos.fecha_deposito')
                        ->where('ins.credito_id','=',$deposito->id)
                        ->where('dep_creditos.banco','!=','0000000-Grupo Cumbres')//Diferentes a la cuenta de ajustes
                        ->whereYear('dep_creditos.fecha_deposito',$anio) //Año seleccionado
                        ->whereMonth('dep_creditos.fecha_deposito',$mes); //Mes seleccionado
                    if($empresa != '')
                        $act = $act->where('lotes.emp_constructora','=',$empresa);
                    $act = $act->get();
                //Se obtienen las fechas en las que se envio, recibio y se audito el expediente.
                $contrato = Contrato::select('send_exp','received_exp','fecha_audit')->where('id','=',$deposito->id)->get();

                $deposito->send_exp = $contrato[0]->send_exp;
                $deposito->received_exp = $contrato[0]->received_exp;
                $deposito->fecha_audit = $contrato[0]->fecha_audit;
                $deposito->totalCredito = round($inst[0]->monto_credito,2); //El monto total del financiamiento
                $deposito->totalDep = round($deposito->totalDep,2);//Monto total de los depositos realizados
                $deposito->tipo_credito = $inst[0]->tipo_credito;
                $deposito->institucion = $inst[0]->institucion;
                $deposito->flag = 0;
                $deposito->mes = 0;
                if($deposito->totalCredito <= $deposito->totalDep) //Se verifica que el monto depositado supere o igual al monto del credito
                    $deposito->flag = 1;
                if(sizeOf($act))
                    $deposito->mes = 1;
            }
        }
        return $depositos;
    }
    //Función privada que retorna los Contratos con firma de escrituras, pero pendientes por cobrar financiamiento bancario
    private function getSinEntregarRep($mes, $anio, $empresa){
        //Query para obtener las ventas que se han escriturado
        $sinEntregar = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->join('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select(//'dep_creditos.inst_sel_id',
                            'inst_seleccionadas.id as crd_id',
                            'contratos.id','p.nombre','p.apellidos',
                            'f.nombre as proyecto','et.num_etapa',
                            'inst_seleccionadas.tipo_credito','inst_seleccionadas.institucion',
                            'lotes.manzana','lotes.num_lote','lotes.emp_constructora',
                            'inst_seleccionadas.cobrado','inst_seleccionadas.monto_credito'
                            )
                ->where('inst_seleccionadas.tipo','=',0)//Financiamiento principal
                ->where('inst_seleccionadas.elegido','=',1)//financiamiento elegido
                ->where('inst_seleccionadas.status','=',2)//Aprobado
                ->where('expedientes.fecha_firma_esc','!=', NULL)//Venta escriturada
                ->where('contratos.status','=',3)//Contrato firmado
                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                ->whereRaw('inst_seleccionadas.cobrado < inst_seleccionadas.monto_credito');//Con monto pendiente por cobrar
                if($empresa != '')//Filtro por empresa constructora
                    $sinEntregar = $sinEntregar->where('lotes.emp_constructora','=',$empresa);
            $sinEntregar = $sinEntregar->orWhere('inst_seleccionadas.tipo','=',1)//segundo financiamiento en caso de tener
                ->where('inst_seleccionadas.elegido','=',0)
                ->whereRaw('inst_seleccionadas.cobrado < inst_seleccionadas.monto_credito')//Con monto pendiente por cobrar
                ->where('inst_seleccionadas.status','=',2)//Aprobado
                ->where('expedientes.fecha_firma_esc','!=', NULL)//Venta escriturada
                ->where('contratos.status','=',3)//Contrato firmado
                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo');
                if($empresa != '')//Filtro por empresa constructora
                    $sinEntregar = $sinEntregar->where('lotes.emp_constructora','=',$empresa);
                $sinEntregar = $sinEntregar->orderBy('contratos.fecha','asc')->get();
        return $sinEntregar;
    }
    //Función privada que retorna los Contratos de Crédito Directo con firmas de escrituras en el periodo
    private function getRepExpContado($mes, $anio, $empresa){
        //Query principal
        $expContado = Expediente::join('contratos','expedientes.id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.num_lote', 'lotes.emp_constructora',
                        'contratos.received_exp','contratos.send_exp', 'contratos.fecha_audit',
                        'lotes.manzana','ins.tipo_credito','ins.institucion','expedientes.fecha_firma_esc')
                ->where('contratos.status','=',3)//Contrato firmado
                ->where('ins.elegido','=',1)
                ->where('ins.tipo_credito','=','Crédito Directo')//Venta directa
                ->whereMonth('expedientes.fecha_firma_esc',$mes)//Venta escriturada en el mes seleccionado
                ->whereYear('expedientes.fecha_firma_esc',$anio);//Venta escriturada en el año seleccionado
                if($empresa != '')//Filtro por empresa constructora
                    $expContado = $expContado->where('lotes.emp_constructora','=',$empresa);
                $expContado = $expContado->orderBy('expedientes.fecha_firma_esc','asc')->get();
        return $expContado;
    }
    //Función privada que retoran las Ventas por financiamiento bancario con escrituras firmadas en el periodo seleccionado.
    private function getEscriturasRep($mes, $anio, $empresa){
        //Query principal
        $escrituras = Expediente::join('contratos','expedientes.id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'creditos.valor_terreno',
                        'lotes.manzana','lotes.num_lote','ins.tipo_credito','ins.institucion',
                        'expedientes.doc_escrituras','expedientes.doc_date',
                        'expedientes.fecha_firma_esc','expedientes.valor_escrituras','expedientes.notaria')
                ->where('contratos.status','=',3)//Contrato firmado
                ->where('ins.elegido','=',1)
                ->where('expedientes.fecha_firma_esc','!=',NULL)
                ->whereMonth('expedientes.fecha_firma_esc',$mes)//Venta escriturada en el mes seleccionado
                ->whereYear('expedientes.fecha_firma_esc',$anio);//Venta escriturada en el año seleccionado
                if($empresa != '')//Filtro para empresa constructora
                    $escrituras = $escrituras->where('lotes.emp_constructora','=',$empresa);
            $escrituras = $escrituras->orderBy('expedientes.fecha_firma_esc','desc')->get();
        return $escrituras;
    }
    //Función privada que retorna las Ventas directas liquidadas sin escriturar.
    private function getContadoSinEscrituras($mes, $anio, $empresa){
        //Query principal
        $contadoSinEscrituras = Expediente::join('contratos','expedientes.id','=','contratos.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('personal as p','creditos.prospecto_id','=','p.id')
            ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
            ->join('etapas as et','lotes.etapa_id','=','et.id')
            ->join('personal as g','expedientes.gestor_id','=','g.id')
            ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                    'lotes.manzana','lotes.num_lote','ins.tipo_credito','ins.institucion',
                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor"),'contratos.fecha',
                    'expedientes.fecha_firma_esc','expedientes.valor_escrituras','expedientes.notaria')
            ->where('contratos.status','=',3)//Contrato firmado
            ->where('contratos.saldo','<=',0)//Sin saldo por liquidar
            ->where('ins.elegido','=',1)//Financiamiento elegido
            ->where('expedientes.fecha_firma_esc','=',NULL)//Escriturado
            ->where('ins.tipo_credito','=','Crédito Directo');//Ventas de contado
            if($empresa != '')//Filtro por empresa constructora
                    $contadoSinEscrituras = $contadoSinEscrituras->where('lotes.emp_constructora','=',$empresa);
            $contadoSinEscrituras = $contadoSinEscrituras->orderBy('contratos.fecha')->get();

        return $contadoSinEscrituras;
    }
    //Función privada que obtiene los Ingresos institucionales realizados en el periodo seleccionado.
    private function getIngresosCobranza($fecha1, $fecha2, $empresa){
        //Query principal
        $ingresosCobranza = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('dep_creditos as dep','ins.id','=','dep.inst_sel_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.emp_constructora','contratos.id',
                        'lotes.manzana','lotes.num_lote','dep.cant_depo','dep.fecha_deposito','dep.banco')
                ->whereBetween('dep.fecha_deposito',[$fecha1, $fecha2]);//Filtro por fecha de ingreso
                if($empresa != '')//Filtro para empresa constructora
                    $ingresosCobranza = $ingresosCobranza->where('lotes.emp_constructora','=',$empresa);
            $ingresosCobranza = $ingresosCobranza->orderBy('dep.fecha_deposito')->get();
            //Se recorren los registros obtenidos para asignar la fecha de escrituras en caso de tener.
            foreach($ingresosCobranza as $index => $ingreso){
                $exp = Expediente::select('fecha_firma_esc')->where('id','=',$ingreso->id)->get();
                if(sizeOf($exp))
                    $ingreso->escrituras = $exp[0]->fecha_firma_esc;
                else
                    $ingreso->escrituras = '';
            }
        return $ingresosCobranza;
    }
    //Función para generar el reporte de lotes construidos sin Credito puente.
    public function reporteRecursosPropios(Request $request){
        $lotes = [];
        $suma1=0;
        $suma2=0;
        $suma3=0;
        //Llamada a la funcion privada que retorna la query que obtiene los lotes necesarios para el reporte
        $lotes = $this->getLotesRecProp($request);

        $lotes2 = $lotes->get();//En otra variable se añaden todos los resultados
        //En la variable principal se almacenan los resultados con paginacion
        $lotes = $lotes->orderBy('proyecto','asc')
                        ->orderBy('etapas.num_etapa','asc')->paginate(25);
        //Si hay resultados en la busqueda
        if(sizeOf($lotes)){
            //Se recorren los resultados
            foreach($lotes as $index => $lote){
                $monto = $this->retornarMontosRP($lote);
                $lote->valor_venta = $monto['valor_venta'];
                $lote->porCobrar = $monto['porCobrar'];
                $lote->status = $monto['status'];
                $lote->cobrado = $monto['cobrado'];
            }
        }
        //Si hay resultados en la busqueda
        if(sizeOf($lotes2)){
            //Se recorren los resultados obtenidos
            foreach($lotes2 as $index => $lote){
                $monto = $this->retornarMontosRP($lote);
                $lote->valor_venta = $monto['valor_venta'];
                $lote->porCobrar = $monto['porCobrar'];
                $lote->status = $monto['status'];
                $lote->cobrado = $monto['cobrado'];
                //Se calculan los valors totales de todos los lotes obtenidos.
                $suma1 += $lote->valor_venta;
                $suma2 += $lote->cobrado;
                $suma3 += $lote->porCobrar;
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
            'lotes' => $lotes,
            'suma1' => $suma1,
            'suma2' => $suma2,
            'suma3' => $suma3
        ];
    }
    //Función privada para calcular los montos de venta, status y montos cobrados de un lote para el reporte de recursos propios
    private function retornarMontosRP($lote){
        $costoEquipamiento = 0;
        $lote->equipamiento = EquipLote::where('status','>',3)->where('lote_id','=',$lote->id)->get();
        if(sizeOf($lote->equipamiento))
            foreach($lote->equipamiento as $eq){
                $costoEquipamiento += $eq->costo;
            }

        $lote->ajuste += $costoEquipamiento;
        //Se asigna el valor de venta del lote
        $lote->valor_venta = $lote->precio_base + $lote->excedente_terreno + $lote->sobreprecio +
                                $lote->obra_extra - $lote->ajuste;
        $lote->porCobrar = 0;
        $lote->status = 0;
        $lote->cobrado = 0;
        //Se busca si el lote cuenta con un contrato de venta vigente
        $contratos = Contrato::join('creditos','creditos.id','=','contratos.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->select('creditos.precio_venta','contratos.id')
                ->where('contratos.status', '=', 3)
                ->where('i.elegido','=',1)
                ->where('creditos.lote_id','=',$lote->id)
                ->orWhere('contratos.status','=', 1)
                ->where('i.elegido','=',1)
                ->where('creditos.lote_id','=',$lote->id)
                ->get();
        //En caso de encontrar resultados en la busqueda
        if(sizeOf($contratos) > 0){
            //Se asigna al lote el valor de venta con el que se cerro el contrato
            $lote->valor_venta = $contratos[0]->precio_venta;
            $lote->status = 1;//se asigna status 1 al lote para indicar la venta
            //Se accede a los pagares para obtener el monto cobrado y lo pendiente por cobrar
            $pagos = Pago_contrato::select(
                DB::raw("SUM(monto_pago) as sumMontoPago"),
                DB::raw("SUM(restante) as sumRestante"))
                        ->where('contrato_id','=',$contratos[0]->id)
                        ->get();
            $lote->pagos = $pagos;
            $lote->cobrado = $pagos[0]->sumMontoPago - $pagos[0]->sumRestante;
            $lote->porCobrar =$lote->valor_venta - $lote->cobrado;
        }

        return [
            'valor_venta' => $lote->valor_venta,
            'porCobrar' => $lote->porCobrar,
            'status' => $lote->status,
            'cobrado' => $lote->cobrado
        ];
    }
    //Función privada para obtener la query que retorna los lotes disponibles y pendientes por cobrar que no pertenezcan a un crédito puente
    private function getLotesRecProp(Request $request){
        //Query para obtener todas las ventas individualizadas
        $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->select('lotes.id')
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')//Ventas directas
                                ->where('contratos.status','=',3)//Contrato firmado
                                ->where('expedientes.liquidado','=',1)//Liquidado
                                ->where('inst_seleccionadas.elegido', '=', '1');//Financiamiento principal
                                if($request->proyecto != '')//Filtro por proyecto
                                    $indivContado = $indivContado->where('lotes.fraccionamiento_id','=',$request->proyecto);
                                if($request->etapa != '')//Filtro por etapa
                                    $indivContado = $indivContado->where('lotes.etapa_id','=',$request->etapa);
                                $indivContado = $indivContado->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')//O Financiamiento bancario
                                ->where('contratos.status','=',3)//Contrato firmado
                                ->where('expedientes.fecha_firma_esc','!=',NULL)//Venta escriturada
                                ->where('inst_seleccionadas.elegido', '=', '1');//Financiamiento elegido
                                if($request->proyecto != '')//Filtro por proyecto
                                    $indivContado = $indivContado->where('lotes.fraccionamiento_id','=',$request->proyecto);
                                if($request->etapa != '')//Filtro por etapa
                                    $indivContado = $indivContado->where('lotes.etapa_id','=',$request->etapa);
                                $indivContado = $indivContado->orderBy('lotes.id','asc')
                                ->distinct('contratos.id')->get();
        $indiv = [];

        if(sizeof($indivContado))
            foreach($indivContado as $index => $individual)
                //Se agregan los identificadores de las ventas individualizadas en un arreglo
                array_push($indiv,$individual->id);
        //Query para obtener los lotes sin credito puente, en proceso o liquidado que no han sido individualizados
        $lotes = Lote::join('fraccionamientos','fraccionamientos.id','=','lotes.fraccionamiento_id')
                        ->join('etapas','etapas.id','=','lotes.etapa_id')
                        ->join('licencias','licencias.id','=','lotes.id')
                        ->select('etapas.num_etapa','lotes.manzana','fraccionamientos.nombre as proyecto',
                                'licencias.id', 'lotes.credito_puente','licencias.avance', 'lotes.num_lote',
                                'lotes.precio_base', 'lotes.excedente_terreno','lotes.sobreprecio',
                                'lotes.ajuste','lotes.obra_extra');
                $lotes = $lotes->where('lotes.credito_puente','like','NO TIENE CREDITO PUENTE%')//Sin credito puente
                        ->whereNotIn('lotes.id',$indiv)//que no pertenezcan dentro de las ventas individualizadas
                        ->where('licencias.avance','>',0);//con avance en construccion
                        if($request->proyecto != '')//Filtro por proyecto
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')//Filtro por etapa
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);
                        $lotes = $lotes->orWhere('lotes.credito_puente','like','EN PROCESO%')// O lotes con credito puente en proceso
                        ->whereNotIn('lotes.id',$indiv)//que no pertenezcan dentro de las ventas individualizadas
                        ->where('licencias.avance','>',0);
                        if($request->proyecto != '')//Filtro por proyecto
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')//Filtro por etapa
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);
                        $lotes = $lotes->orWhere('lotes.credito_puente','like','LIQUIDADO%')//O Crédito puente liquidado
                        ->whereNotIn('lotes.id',$indiv)//que no pertenezcan dentro de las ventas individualizadas
                        ->where('licencias.avance','>',0);
                        if($request->proyecto != '')//Filtro por proyecto
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')//Filtro por etapa
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);

        return $lotes;
    }
    //Función para generar el reporte de lotes construidos sin Credito puente en excel.
    public function excelRecursosPropios(Request $request){
        $lotes = [];
        $suma1=0;
        $suma2=0;
        $suma3=0;
        //Llamada a la funcion privada que retorna la query que obtiene los lotes necesarios para el reporte
        $lotes = $this->getLotesRecProp($request);
        $lotes = $lotes->orderBy('proyecto','asc')
                        ->orderBy('etapas.num_etapa','asc')
                        ->orderBy('etapas.num_etapa','asc')->get();
        //Si hay resultados en la busqueda
        if(sizeOf($lotes)){
            //Se recorren los resultados
            foreach($lotes as $index => $lote){
                //Llamada a la funcion privada que retorna los montos del lote
                $monto = $this->retornarMontosRP($lote);
                //Se asignan los valores
                $lote->valor_venta = $monto['valor_venta'];
                $lote->porCobrar = $monto['porCobrar'];
                $lote->status = $monto['status'];
                $lote->cobrado = $monto['cobrado'];
                //Se calculan los valores totales de todos los lotes obtenidos.
                $suma1 += $lote->valor_venta;
                $suma2 += $lote->cobrado;
                $suma3 += $lote->porCobrar;
            }
        }

       //Creación y retorno del reporte en excel.
        return Excel::create('Reporte', function($excel) use ($lotes, $suma1, $suma2, $suma3){
            $excel->sheet('Reoorte recursos propios', function($sheet) use ($lotes, $suma1, $suma2, $suma3){

                $sheet->row(1, ['',
                    'Fraccionamiento',
                    'Etapa',
                    'Manzana',
                    'Lote',
                    'Estatus',
                    'Avance',
                    'Valor de Venta',
                    'Cobrado',
                    'Por Cobrar',
                    'Credito Puente'
                ]);

                $sheet->cells('A1:K1', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->setColumnFormat(array(
                    'H' => '$#,##0.00',
                    'I' => '$#,##0.00',
                    'J' => '$#,##0.00',
                ));

                $cont=3;
                $renglon = 2;

                $i = 1;

                foreach($lotes as $index => $lote) {
                    $cont++;

                    if($lote->status == 0)
                        $status = 'Disponible';
                    else
                        $status = 'Vendida';

                        $sheet->row($renglon, [
                            $i,
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $status,
                            $lote->avance.'%',
                            $lote->valor_venta,
                            $lote->cobrado,
                            $lote->porCobrar,
                            $lote->credito_puente,

                        ]);
                        $renglon ++;
                        $i++;

                }
                $sheet->row($renglon+1,[
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'Total',
                    $suma1,
                    $suma2,
                    $suma3,
                   ''

                ]);

                $renglon = $renglon + 1;

                $num='A1:K' . $renglon;
                $sheet->setBorder($num, 'thin');

                $sheet->cells('A'.$renglon.':'.'K'.$renglon, function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(11);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                });
            });
        }

        )->download('xls');
    }
    //Función para generar el reporte de lotes construidos con Credito puente con saldo pendiente
    public function reporteCasasCreditoPuente(Request $request){
        $lotes = [];
        $suma1=0;
        $suma2=0;
        $suma3=0;
        //Llamada a la funcion privada que retorna la query que obtiene los lotes necesarios para el reporte
        $lotes = $this->getLotesCreditoPuente($request);
        $lotes2 = $lotes->get();//En otra variable se añaden todos los resultados
        //En la variable principal se almacenan los resultados con paginacion
        $lotes = $lotes->orderBy('proyecto','asc')
                        ->orderBy('etapas.num_etapa','asc')
                        ->orderBy('etapas.num_etapa','asc')->paginate(25);
        //Si hay resultados en la busqueda
        if(sizeOf($lotes)){
            //Se recorren los resultados obtenidos
            foreach($lotes as $index => $lote){
                //Llamada a la funcion privada que retorna los montos del lote
                $monto = $this->retornarMontosRP($lote);
                //Se asignan los valores
                $lote->valor_venta = $monto['valor_venta'];
                $lote->porCobrar = $monto['porCobrar'];
                $lote->status = $monto['status'];
                $lote->cobrado = $monto['cobrado'];
            }
        }

        if(sizeOf($lotes2)){
            foreach($lotes2 as $index => $lote){
                //Llamada a la funcion privada que retorna los montos del lote
                $monto = $this->retornarMontosRP($lote);
                //Se asignan los valores
                $lote->valor_venta = $monto['valor_venta'];
                $lote->porCobrar = $monto['porCobrar'];
                $lote->status = $monto['status'];
                $lote->cobrado = $monto['cobrado'];
                //Se calculan los valores totales de todos los lotes obtenidos.
                $suma1 += $lote->valor_venta;
                $suma2 += $lote->cobrado;
                $suma3 += $lote->porCobrar;
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
            'lotes' => $lotes,
            'suma1' => $suma1,
            'suma2' => $suma2,
            'suma3' => $suma3
        ];
    }
    //Función para generar el reporte de lotes construidos con Credito puente con saldo pendiente en excel
    public function excelCasasCreditoPuente(Request $request){
        $lotes = [];
        $suma1=0;
        $suma2=0;
        $suma3=0;
        //Llamada a la funcion privada que retorna la query que obtiene los lotes necesarios para el reporte
        $lotes = $this->getLotesCreditoPuente($request);
        $lotes = $lotes->orderBy('proyecto','asc')
                        ->orderBy('etapas.num_etapa','asc')
                        ->orderBy('etapas.num_etapa','asc')->get();

        //Si hay resultados en la busqueda
        if(sizeOf($lotes)){
            //Se recorren los resultados obtenidos
            foreach($lotes as $index => $lote){
                //Llamada a la funcion privada que retorna los montos del lote
                $monto = $this->retornarMontosRP($lote);
                //Se asignan los valores
                $lote->valor_venta = $monto['valor_venta'];
                $lote->porCobrar = $monto['porCobrar'];
                $lote->status = $monto['status'];
                $lote->cobrado = $monto['cobrado'];
                //Se calculan los valores totales de todos los lotes obtenidos.
                $suma1 += $lote->valor_venta;
                $suma2 += $lote->cobrado;
                $suma3 += $lote->porCobrar;
            }
        }
        //Creación y retorno del reporte en excel.
        return Excel::create('Reporte', function($excel) use ($lotes, $suma1, $suma2, $suma3){
            $excel->sheet('Casas credito puente', function($sheet) use ($lotes, $suma1, $suma2, $suma3){

                $sheet->row(1, ['',
                    'Fraccionamiento',
                    'Etapa',
                    'Manzana',
                    'Lote',
                    'Estatus',
                    'Avance',
                    'Valor de Venta',
                    'Cobrado',
                    'Por Cobrar',
                    'Credito Puente'
                ]);

                $sheet->cells('A1:K1', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->setColumnFormat(array(
                    'H' => '$#,##0.00',
                    'I' => '$#,##0.00',
                    'J' => '$#,##0.00',
                ));

                $cont=3;
                $renglon = 2;

                $i = 1;

                foreach($lotes as $index => $lote) {
                    $cont++;
                    if($lote->status == 0)
                        $status = 'Disponible';
                    else
                        $status = 'Vendida';
                    $sheet->row($renglon, [
                        $i,
                        $lote->proyecto,
                        $lote->num_etapa,
                        $lote->manzana,
                        $lote->num_lote,
                        $status,
                        $lote->avance.'%',
                        $lote->valor_venta,
                        $lote->cobrado,
                        $lote->porCobrar,
                        $lote->credito_puente,

                    ]);
                    $renglon ++;
                    $i++;
                }
                $sheet->row($renglon+1,[
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'Total',
                    $suma1,
                    $suma2,
                    $suma3,
                   ''
                ]);

                $renglon = $renglon + 1;
                $num='A1:K' . $renglon;
                $sheet->setBorder($num, 'thin');

                $sheet->cells('A'.$renglon.':'.'K'.$renglon, function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(11);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                });
            });
        }
        )->download('xls');
    }
    //Función privada para obtener la query que retorna los lotes disponibles y pendientes por cobrar que pertenezcan a un crédito puente
    private function getLotesCreditoPuente(Request $request){
        $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                            ->join('creditos','contratos.id', '=', 'creditos.id')
                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('lotes.id')
                            ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                            ->where('contratos.status','=',3)
                            ->where('expedientes.liquidado','=',1)
                            ->where('inst_seleccionadas.elegido', '=', '1');
                            if($request->proyecto != '')
                                $indivContado = $indivContado->where('lotes.fraccionamiento_id','=',$request->proyecto);
                            if($request->etapa != '')
                                $indivContado = $indivContado->where('lotes.etapa_id','=',$request->etapa);
                            $indivContado = $indivContado->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                            ->where('contratos.status','=',3)
                            ->where('expedientes.fecha_firma_esc','!=',NULL)
                            ->where('inst_seleccionadas.elegido', '=', '1');
                            if($request->proyecto != '')
                                $indivContado = $indivContado->where('lotes.fraccionamiento_id','=',$request->proyecto);
                            if($request->etapa != '')
                                $indivContado = $indivContado->where('lotes.etapa_id','=',$request->etapa);
                            $indivContado = $indivContado
                            ->orderBy('lotes.id','asc')
                            ->distinct('contratos.id')->get();
        $indiv = [];

        if(sizeof($indivContado))
            foreach($indivContado as $index => $individual){
                array_push($indiv,$individual->id);
            }


        $lotes = Lote::join('fraccionamientos','fraccionamientos.id','=','lotes.fraccionamiento_id')
                        ->join('etapas','etapas.id','=','lotes.etapa_id')
                        ->join('licencias','licencias.id','=','lotes.id')
                        ->select('etapas.num_etapa','lotes.manzana','fraccionamientos.nombre as proyecto',
                                'licencias.id', 'lotes.credito_puente','licencias.avance', 'lotes.num_lote',
                                'lotes.precio_base', 'lotes.excedente_terreno','lotes.sobreprecio',
                                'lotes.ajuste','lotes.obra_extra')
                        ->where('lotes.credito_puente','not like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.credito_puente','NOT LIKE','EN PROCESO%')
                        ->where('lotes.credito_puente','NOT LIKE','LIQUIDADO%');
                        if($request->proyecto != '')
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);

        return $lotes;
    }
    //Función para generar el reporte ventas y disponibilidad de lotes por modelo
    public function reporteModelos(Request $request){
        $fraccionamiento = $request->fraccionamiento;
        $etapa = $request->etapa;
        $diff_in_months = 0;//variable para determinar el numero de meses que hay desde el alta de un proyecto o privada
        $fechaIni = $request->fecha1;
        $fechaFin = $request->fecha2;
        //Query para obtener todos los modelos registrados
        $modelos = Modelo::select('nombre')->where('nombre','!=','Por Asignar');
        if($fraccionamiento != '')//Busqueda por proyecto
           $modelos =  $modelos->where('fraccionamiento_id','=',$fraccionamiento);
        $modelos = $modelos->orderBy('nombre','asc')->distinct()->get();
        //Query para obtener la fecha de la ultima venta
        $vendidasFin = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->select('contratos.fecha')
                    ->where('contratos.status','=',3)//Contrato firmado
                    ->where('lotes.habilitado','=',1);
                if($fraccionamiento != '')//Busqueda por proyecto
                    $vendidasFin = $vendidasFin->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                if($etapa != '')//Busqueda por etapa
                    $vendidasFin = $vendidasFin->where('lotes.etapa_id','=',$etapa);
                if($fechaIni != '' && $fechaFin != '')//Filtro por rango de fecha
                    $vendidasFin = $vendidasFin->whereBetween('contratos.fecha', [$fechaIni, $fechaFin]);
            $vendidasFin = $vendidasFin->orderBy('contratos.fecha','desc')->get();

        if($fraccionamiento != ''){// Si se busca un proyecto en especifico
            //Se accede a la fecha de inicio de ventas del proyecto
            $fracc = Fraccionamiento::select('fecha_ini_venta')->where('id','=',$fraccionamiento)->get();
            $fecha = $fracc[0]->fecha_ini_venta;
            if($fecha){
                $to = Carbon::createFromFormat('Y-m-d', $vendidasFin[0]->fecha);
                $from = Carbon::createFromFormat('Y-m-d', $fecha);
                $diff_in_months = $to->diffInMonths($from);
            }
        }
        if($etapa != ''){// Si se busca una etapa en especifico
            //Se accede a la fecha de inicio de ventas de la etapa
            $fracc = Etapa::select('fecha_ini_venta')->where('id','=',$etapa)->where('fraccionamiento_id','=',$fraccionamiento)->get();
            $fecha = $fracc[0]->fecha_ini_venta;
            if($fecha){
                $to = Carbon::createFromFormat('Y-m-d', $vendidasFin[0]->fecha);
                $from = Carbon::createFromFormat('Y-m-d', $fecha);
                $diff_in_months = $to->diffInMonths($from);
            }
        }
        //Se recorren los registros de los modelos.
        foreach($modelos as $index => $modelo){
            $modelo->total = 0;//Se inicializa el total del modelo en 0
            if($fechaIni != '' && $fechaFin != ''){//En caso de buscar por rango de fecha
                //Query para obtener los lotes disponibles actualmente
                //Lotes terminados
                $dispTerm = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->where('licencias.avance','>=',90)
                ->where('lotes.contrato','=',0)
                ->where('lotes.habilitado','=',1)->where('modelos.nombre','=',$modelo->nombre);
                //Lotes en proceso
                $dispProc = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->where('licencias.avance','<',90)
                ->where('lotes.contrato','=',0)
                ->where('lotes.habilitado','=',1)->where('modelos.nombre','=',$modelo->nombre);
                if($fraccionamiento != ''){//Filtro por proyecto
                    $dispTerm = $dispTerm->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                    $dispProc = $dispProc->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                }
                if($etapa != ''){//Filtro por etapa
                    $dispTerm = $dispTerm->where('lotes.etapa_id','=',$etapa);
                    $dispProc = $dispProc->where('lotes.etapa_id','=',$etapa);
                }
                $modelo->dispTerm = $dispTerm->count();
                $modelo->dispProc = $dispProc->count();
                $modelo->totalDisp = $modelo->dispTerm + $modelo->dispProc;
            }
            //Query para obtener el total de lotes habilitados terminados
            $lotesTerm = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->where('licencias.avance','>=',90)
                ->where('lotes.habilitado','=',1)->where('modelos.nombre','=',$modelo->nombre);
            //Query para obtener el total de lotes habilitados en proceso
            $lotesProc = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->where('licencias.avance','<',90)
                ->where('lotes.habilitado','=',1)->where('modelos.nombre','=',$modelo->nombre);
            if($fraccionamiento != ''){//filtro por proyecto
                $lotesTerm = $lotesTerm->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                $lotesProc = $lotesProc->where('lotes.fraccionamiento_id','=',$fraccionamiento);
            }
            if($etapa != ''){//Filtro por etapa
                $lotesTerm = $lotesTerm->where('lotes.etapa_id','=',$etapa);
                $lotesProc = $lotesProc->where('lotes.etapa_id','=',$etapa);
            }
            $lotesTerm = $lotesTerm->count();
            $lotesProc = $lotesProc->count();

            $modelo->total = $lotesProc + $lotesTerm;//Se asigna el total de lotes por modelo
            $modelo->lotesTerm = $lotesTerm;//Se asigna el total de lotes terminados por modelo
            $modelo->lotesProc = $lotesProc;//Se asigna el total de lotes habilitados por modelo
            //Query para obtener todos los contratos firmados
            $contratosPeriodo = Contrato::join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->select('contratos.id')
                            ->where('contratos.status','=',3)
                            ->where('modelos.nombre','=',$modelo->nombre);
                            if($fraccionamiento != '')//Filtro por proyecto
                                $contratosPeriodo=$contratosPeriodo->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                            if($etapa != '')//Filtro por etapa
                                $contratosPeriodo=$contratosPeriodo->where('lotes.etapa_id','=',$etapa);
                            if($fechaIni != '' && $fechaFin != '')//Filtro para fecha de venta
                                $contratosPeriodo=$contratosPeriodo->whereBetween('contratos.fecha',[$fechaIni, $fechaFin]);
            $contratosPeriodo = $contratosPeriodo->get();

            //Query para obtener las ventas por crédito directo individualizadas de lotes en proceso de construcción
            $indivContadoProc =  Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('expedientes.liquidado','=',1)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                ->whereIn('contratos.id',$contratosPeriodo);
            //Query para obtener las ventas por crédito directo individualizadas de lotes terminados
            $indivContadoTerm =  Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('expedientes.liquidado','=',1)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                ->whereIn('contratos.id',$contratosPeriodo);
                $indivContadoTerm = $indivContadoTerm->where('licencias.avance','>=',90);
                $indivContadoProc = $indivContadoProc->where('licencias.avance','<',90);
                                $indivContadoTerm = $indivContadoTerm->distinct('contratos.id')
                                                            ->count('contratos.id');
                                $indivContadoProc = $indivContadoProc->distinct('contratos.id')
                                                            ->count('contratos.id');

                $indivContado = $indivContadoTerm + $indivContadoProc;
            //Query para obtener las ventas por financiamiento bancario individualizadas de lotes terminados
            $indivCreditoTerm = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->whereIn('contratos.id',$contratosPeriodo);
            //Query para obtener las ventas por financiamiento bancario individualizadas de lotes en proceso de construcción
            $indivCreditoProc = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->whereIn('contratos.id',$contratosPeriodo);
                $indivCreditoTerm = $indivCreditoTerm->where('licencias.avance','>=',90);
                $indivCreditoProc = $indivCreditoProc->where('licencias.avance','<',90);
                                $indivCreditoTerm=$indivCreditoTerm->distinct('contratos.id')
                                                            ->count('contratos.id');
                                $indivCreditoProc=$indivCreditoProc->distinct('contratos.id')
                                                            ->count('contratos.id');

                $indivCredito = $indivCreditoTerm + $indivCreditoProc;
            //Query para obtener las ventas del periodo para lotes en construccion
            $contratosProc = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('licencias','lotes.id','=','licencias.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->where('licencias.avance','<',90)
                            ->whereIn('contratos.id',$contratosPeriodo);
                            $contratosProc=$contratosProc->distinct('contratos.id')
                                ->count('contratos.id');
            //Query para obtener las ventas del periodo para lotes terminados
            $contratosTerm = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('licencias','lotes.id','=','licencias.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->where('licencias.avance','>=',90)
                            ->whereIn('contratos.id',$contratosPeriodo);
                            $contratosTerm=$contratosTerm->distinct('contratos.id')
                            ->count('contratos.id');

            $contratos = $contratosTerm + $contratosProc;
            //Contratos indivualizados tanto para directos como financiados
            $indiv = $indivContado + $indivCredito;

            $indivTerm = $indivCreditoTerm + $indivContadoTerm;
            $indivProc = $indivCreditoProc + $indivContadoProc;

            $modelo->indiv = $indiv;
            //Lotes vendidos en total (Sin contar los individualizados)
            $modelo->vendida = $contratos - $indiv;
            if($modelo->vendida < 0)
                $modelo->vendida = 0;
            //Lotes terminados vendidos (Sin contar los individualizados)
            $modelo->vendidaTerm = $contratosTerm - $indivTerm;
            if($modelo->vendidaTerm < 0)
                $modelo->vendidaTerm = 0;
            //Lotes en proceso vendidos (Sin contar los individualizados)
            $modelo->vendidaProc = $contratosProc - $indivProc;
            if($modelo->vendidaProc < 0)
                $modelo->vendidaProc = 0;
            //Total de lotes vendidos (contando individualizados)
            $modelo->total_vendidas = $modelo->indiv + $modelo->vendida;
            //Total de lotes disponibles para venta
            $modelo->disponible = $modelo->total - ($modelo->indiv + $modelo->vendida );
            //Total de lotes en proceso disponibles para venta
            $modelo->disponibleProc = $lotesProc - ($indivProc + $modelo->vendidaProc );
            //Total de lotes terminados disponibles para venta
            $modelo->disponibleTerm = $lotesTerm - ($indivTerm + $modelo->vendidaTerm );
            if($fechaIni != '' && $fechaFin != ''){
                $modelo->disponible = $modelo->totalDisp;
                $modelo->disponibleProc = $modelo->dispProc;
                $modelo->disponibleTerm = $modelo->dispTerm;
            }
        }

        return [
            'modelos'=>$modelos,
            'diferencia'=>$diff_in_months,
            ];

    }
    //Función para generar el reporte ventas y disponibilidad de lotes por modelo en excel
    public function excelReporteModelos(Request $request){
        $reporte = $this->reporteModelos($request);

        // $modelos =  $reporte['modelos'];
        // $modelos =  $reporte['modelos'];

        //Creación y retorno de los resultados en Excel.
        return Excel::create('Reporte por modelo', function($excel) use ($reporte){
                $excel->sheet('Reporte', function($sheet) use ($reporte){
                    $sheet->row(1, [
                        'Modelo', 'Total', 'Individualizadas',
                        'Vendidas proceso', 'Vendidas terminadas',
                        'Vendidas', 'Total vendidas', 'Disponible proceso',
                        'Disponible terminadas', 'Disponibles', 'Inventario',
                        'Promedio mensual'
                    ]);

                    $sheet->cells('A1:L1', function ($cells) {
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
                    foreach($reporte['modelos'] as $index => $modelo) {
                        $prom = 0;
                        if($reporte['diferencia'] != 0)
                            $prom = ($modelo->vendida + $modelo->indiv)/$reporte['diferencia'];
                        $cont++;

                        $sheet->row($index+2, [
                            $modelo->nombre,
                            $modelo->total,
                            $modelo->indiv,
                            $modelo->vendidaProc,
                            $modelo->vendidaTerm,
                            $modelo->vendida,
                            $modelo->total_vendidas,
                            $modelo->disponibleProc,
                            $modelo->disponibleTerm,
                            $modelo->disponible,
                            $modelo->disponible + $modelo->vendida,
                            $prom

                        ]);
                    }
                    $num='A1:L' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
                }
        )->download('xls');
    }
    //Función privada para obtener el listado de detalles
    private function getCatalogoDetalles(){
        return Cat_detalle_subconcepto::join('cat_detalles_generales as gral','cat_detalles_subconceptos.id_gral','=','gral.id')
                                ->select('cat_detalles_subconceptos.subconcepto','gral.general','cat_detalles_subconceptos.id',
                                        DB::raw("CONCAT(gral.general,'/ ',cat_detalles_subconceptos.subconcepto) AS detalles"))
                                ->orderBy('gral.general','desc')
                                ->orderBy('cat_detalles_subconceptos.subconcepto','desc')
                                ->get();
    }
    //Funcion privada que retorna el resumen de Solicitudes de detalles
    private function getResumenDetalles(Request $request){
        //Query para obtener las solicitudes de detalles generadas
        $resumen = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                    ->join('descripcion_detalles as det','solic_detalles.id','=','det.solicitud_id')
                                    ->join('cat_detalles as d','det.detalle_id','=','d.id')
                                    ->join('cat_detalles_subconceptos as sub','d.id_sub','=','sub.id')
                                    ->join('cat_detalles_generales as gral','sub.id_gral','=','gral.id')
                                    ->join('contratistas as c','solic_detalles.contratista_id','=','c.id')
                                    ->join('creditos as cr','contratos.id','=','cr.id')
                                    ->join('lotes','cr.lote_id','=','lotes.id')
                                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                    ->select('solic_detalles.cliente','solic_detalles.status','lotes.num_lote',
                                            'fraccionamientos.nombre as proyecto','det.id', 'solic_detalles.created_at',
                                            'etapas.num_etapa','lotes.manzana','d.detalle','sub.subconcepto','gral.general',
                                            DB::raw("CONCAT(gral.general,'/ ',sub.subconcepto) AS detalles"),
                                            'c.nombre','solic_detalles.contratista_id');
                if($request->contratista != '')//Filtro por contratista
                    $resumen = $resumen->where('c.id','=',$request->contratista);
                if($request->proyecto != '')//Filtro por proyecto
                    $resumen = $resumen->where('fraccionamientos.id','=',$request->proyecto);
                if($request->etapa != '')//Filtro por etapa
                        $resumen = $resumen->where('etapas.id','=',$request->etapa);
                if($request->desde != '' && $request->hasta != '')//Filtro por fecha de solicitud
                    $resumen = $resumen->whereBetween('solic_detalles.created_at', [$request->desde.' 00:00:00', $request->hasta.' 23:59:59']);

        return $resumen;
    }

    private function getReporteDetalle(Request $request){
        $detalles = $this->getCatalogoDetalles();

        foreach($detalles as $det){
            $det->solic = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('descripcion_detalles as det','solic_detalles.id','=','det.solicitud_id')
                            ->join('contratistas as c','solic_detalles.contratista_id','=','c.id')
                            ->join('creditos as cr','contratos.id','=','cr.id')
                            ->join('lotes','cr.lote_id','=','lotes.id')
                            ->join('etapas','lotes.etapa_id','=','etapas.id')
                            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                            ->select('solic_detalles.cliente','solic_detalles.status','lotes.num_lote',
                                    'fraccionamientos.nombre as proyecto','det.id', 'solic_detalles.created_at',
                                    'etapas.num_etapa','lotes.manzana', 'solic_detalles.status',
                                    'det.detalle', 'det.subconcepto','det.general',
                                    'c.nombre','solic_detalles.contratista_id')
                            //->where('det.detalle','=',$det->detalles)
                            ->where('det.general','=',$det->general)
                            ->where('det.subconcepto','=',$det->subconcepto);
                if($request->contratista != '')//Filtro por contratista
                    $det->solic = $det->solic->where('c.id','=',$request->contratista);
                if($request->proyecto != '')//Filtro por proyecto
                    $det->solic = $det->solic->where('fraccionamientos.id','=',$request->proyecto);
                if($request->etapa != '')//Filtro por etapa
                    $det->solic = $det->solic->where('etapas.id','=',$request->etapa);
                if($request->desde != '' && $request->hasta != '')//Filtro por fecha de solicitud
                    $det->solic = $det->solic->whereBetween('solic_detalles.created_at', [$request->desde.' 00:00:00', $request->hasta.' 23:59:59']);

                $det->solic = $det->solic->get();
                $det->conteo = $det->solic->count();
        }

        return $detalles;
    }

    private function getReporteContratista(Request $request){
        $contratistas = Contratista::select('id', 'nombre');
        if($request->contratista)
            $contratistas = $contratistas->where('id','=',$request->contratista);
        $contratistas = $contratistas->orderBy('nombre','asc')->get();

        foreach($contratistas as $c){
            $c->solic = $c->solic = $this->getQuerySolicitudes(
                $c->id,$request->proyecto, $request->etapa, $request->desde, $request->hasta
            );
            $c->pendientes = $c->pendientes = $this->getQuerySolicitudes(
                $c->id,$request->proyecto, $request->etapa, $request->desde, $request->hasta, '0'
            );
            $c->proceso = $c->proceso = $this->getQuerySolicitudes(
                $c->id,$request->proyecto, $request->etapa, $request->desde, $request->hasta, '1'
            );
            $c->concluidos = $c->concluidos = $this->getQuerySolicitudes(
                $c->id,$request->proyecto, $request->etapa, $request->desde, $request->hasta, '2'
            );
            $c->cancelados = $c->cancelados = $this->getQuerySolicitudes(
                $c->id,$request->proyecto, $request->etapa, $request->desde, $request->hasta, '3'
            );
            $c->solic = $c->solic->get();
            $c->pendientes = $c->pendientes->get();
            $c->proceso = $c->proceso->get();
            $c->concluidos = $c->concluidos->get();
            $c->cancelados = $c->cancelados->get();
            $c->conteo = $c->solic->count();

            $c->num_proceso = $c->proceso->count();
            $c->num_concluidos = $c->concluidos->count();
            $c->num_cancelados = $c->cancelados->count();
            $c->num_pendientes = $c->pendientes->count();
        }

        return $contratistas;
    }

    //Función para generar el reporte de desperfectos en casas vendidas
    public function reporteDetalles(Request $request){
        $reporteDetalles = $this->getReporteDetalle($request);
        $reporteContratista = $this->getReporteContratista($request);

        $solicitudes = $this->getQuerySolicitudes(
            $request->contratista,$request->proyecto, $request->etapa, $request->desde, $request->hasta,$request->status
        );
        $solicitudes = $solicitudes->paginate(15);
        foreach($solicitudes as $solicitud){
            $solicitud->descripcion = $this->getDescripcionDetalles($solicitud->id);
        }

        return [
            'detalles' => $reporteDetalles,
            'contratistas' => $reporteContratista,
            'solicitudes' => $solicitudes
        ];
    }

    private function getQuerySolicitudes($contratista, $proyecto, $etapa, $desde, $hasta,$status=''){
        $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                        ->join('contratistas as c','solic_detalles.contratista_id','=','c.id')
                        ->join('creditos as cr','contratos.id','=','cr.id')
                        ->join('lotes','cr.lote_id','=','lotes.id')
                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->select('solic_detalles.cliente','solic_detalles.status','lotes.num_lote', 'lotes.sublote',
                                'fraccionamientos.nombre as proyecto', 'solic_detalles.created_at',
                                'etapas.num_etapa','lotes.manzana', 'solic_detalles.status',
                                'c.nombre','solic_detalles.contratista_id','solic_detalles.id');
            if($contratista != '')//Filtro por contratista
                $solicitudes = $solicitudes->where('c.id','=',$contratista);
            if($proyecto != '')//Filtro por proyecto
                $solicitudes = $solicitudes->where('fraccionamientos.id','=',$proyecto);
            if($etapa != '')//Filtro por etapa
                $solicitudes = $solicitudes->where('etapas.id','=',$etapa);
            if($desde != '' && $hasta != '')//Filtro por fecha de solicitud
                $solicitudes = $solicitudes->whereBetween('solic_detalles.created_at', [$desde.' 00:00:00', $hasta.' 23:59:59']);
            if($status != '')//Filtro por etapa
                $solicitudes = $solicitudes->where('solic_detalles.status','=',$status);

            return $solicitudes;
    }

    private function getDescripcionDetalles($solicitud_id){
        return Descripcion_detalle::where('solicitud_id','=',$solicitud_id)->get();
    }

    //Función para generar el reporte de desperfectos en casas vendidas en excel.
    public function reporteDetallesExcel(Request $request){
        //Query para obtener todos los contratistas registrados
        $contratistas = Contratista::select('id', 'nombre')->orderBy('nombre','asc')->get();
        //Llamada a la funcion privada que retorna la query principal para la obtencion de solicitudes
        $resumen = $this->getResumenDetalles($request);
        //En la variable se almacenan todas las solicitudes
        $resumen = $resumen->orderBy('solic_detalles.status','desc')->get();
        //llamada a la funcion privada que retorna todo el catalogo de detalles (general/subconcepto)
        $detalles = $this->getCatalogoDetalles();

        if(sizeOf($resumen))
            //Se recorren todos los resultados de contratistas obtenidos
            foreach($contratistas as $det => $contratista){
                $contratista->cont=0;//Se inicializa en 0 el contador por contratista
                foreach($resumen as $index => $detalle){//Se recorren los resultados de las solicitudes de detalles
                    if($detalle->contratista_id == $contratista->id)
                        //Se aumenta el valor si el detalle coincide con el contratista
                        $contratista->cont++;
                }
            }

        if(sizeOf($resumen))
            //Se recorren todos los resultados del catalogo de detalles obtenidos
            foreach($detalles as $det => $detalle){
                $detalle->cont=0;//Se inicializa en 0 el contador del detalle
                foreach($resumen as $index => $res){
                    if($res->detalles == $detalle->detalles)
                        //Se aumenta el valor si el detalle de la solicitud coincide con el catalogo
                        $detalle->cont++;
                }
            }

        //Creación y retorno de los resultados en Excel.
        return Excel::create('Reporte de detalles', function($excel) use ($contratistas,$resumen,$detalles){
            $excel->sheet('Resumen', function($sheet) use ($resumen){//Hoja para mostrar el resumen de solicitudes

                $sheet->mergeCells('A1:K1');
                $sheet->mergeCells('A2:K2');
                $sheet->mergeCells('C4:G4');

                $sheet->cell('A1', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE C.V.');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A2', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE DETALLES');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cell('B4', function($cell) {
                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                });

                $sheet->cell('C4', function($cell) {
                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                });

                $sheet->row(5, [
                    'No.',
                    'Fraccionamiento',
                    'Etapa',
                    'Manzana',
                    'Lote',
                    'Cliente',
                    'Fecha de solicitud',
                    'Descripción del detalle',
                    'Contratista',
                    'Status'
                ]);


                $sheet->cells('A5:J5', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');
                    // Set font size
                    $cells->setFontSize(12);
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $cont=6;

                foreach($resumen as $index => $lote) {
                    $cont++;
                    $status='';
                    if($lote->fecha_concluido == NULL)
                        $status = 'Pendiente';
                    elseif($lote->fecha_concluido != NULL && $lote->revisado == 0)
                        $status = 'Sin revisar';
                    elseif($lote->fecha_concluido != NULL && $lote->revisado == 1)
                        $status = 'Rechazado';
                    elseif($lote->fecha_concluido != NULL && $lote->revisado == 2)
                        $status = 'Concluido';

                    $sheet->row($index+6, [
                        $index+1,
                        $lote->proyecto,
                        $lote->num_etapa,
                        $lote->manzana,
                        $lote->num_lote,
                        $lote->cliente,
                        $lote->created_at,
                        $lote->detalles.'/ '.$lote->detalle,
                        $lote->nombre,
                        $status,
                    ]);
                }

                $num='A5:J' . $cont;
                $sheet->setBorder($num, 'thin');
            });

            $excel->sheet('Detalles', function($sheet) use ($detalles, $contratistas){//Hoja para mostrar el conteo de todo el catalogo de detalles y contratistas
                $sheet->row(1, [
                    'Detalle',
                    '',
                    '',
                    'Contratista',
                    ''
                ]);

                $sheet->cells('A1:E1', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');
                    // Set font size
                    $cells->setFontSize(12);
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $cont=2;
                $cont1=2;

                foreach($detalles as $index => $lote) {
                    if($lote->cont!=0){
                        $sheet->row($cont, [
                            $lote->detalles,
                            $lote->cont,
                        ]);
                        $cont++;
                    }
                }

                $num='A1:B' . $cont;
                $sheet->setBorder($num, 'thin');

                foreach($contratistas as $index => $lote) {
                    if($lote->cont!=0){
                        $letra1 = 'D'.$cont1;
                        $letra2 = 'E'.$cont1;
                        $nombre = $lote->nombre;
                        $sheet->setCellValue($letra1, $nombre);
                        $sheet->setCellValue($letra2, $lote->cont);
                        $cont1++;
                    }
                }

                $num='D1:E' . $cont1;
                $sheet->setBorder($num, 'thin');
            });
        })->download('xls');
    }
    //Funcion para subir el archivo de escrituras para el expediente del cotnrato
    public function formSubmitEscrituras(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        //Query para obtener el nombre del archivo en caso de contar ya con uno
        $escrituras = Expediente::select('doc_escrituras', 'id')
            ->where('id', '=', $id)
            ->get();
            //en caso de tener un archivo ya registrado
        if ($escrituras->isEmpty() == 0){
            //Se elimina el registro anterior
            $pathAnterior = public_path() . '/files/escrituras/' . $escrituras[0]->pdf;
            File::delete($pathAnterior);
        }
        //Nombre del archivo
        $fileName = time() . '.' . $request->archivo->getClientOriginalExtension();
        //Se almacena en el servidor
        $moved =  $request->archivo->move(public_path('/files/escrituras'), $fileName);
        if ($moved) {
            //Se registra el nombre del archivo en el campo de la BD
            $escrituras = Expediente::findOrFail($request->id);
            $escrituras->doc_escrituras = $fileName;
            $escrituras->doc_date = $fecha;
            $escrituras->save(); //Insert
        }
        return back();
    }
    //Funcion para descargar el archivo de escrituracion
    public function downloadFile($fileName)
    {
        $pathtoFile = public_path() . '/files/escrituras/' . $fileName;
        return response()->file($pathtoFile);
    }
    //Funcion para generar el reporte de entra de viviendas
    public function reporteEntregas(Request $request){
        //Llamada a la funcion privada que retorna todos los registros de la tabla entregas
        $entregas = $this->getEntregas($request);
        $entregas = $entregas->where('entregas.status','=',1);//Filtro por casas ya entregadas
        if($request->fecha_1 != '' && $request->fecha_2 != '')//Filtro por fecha de entrega
                $entregas = $entregas->whereBetween('entregas.fecha_entrega_real', [$request->fecha_1, $request->fecha_2]);
        $entregas = $entregas->orderBy('entregas.fecha_program','desc')->get();
        //Llamada a la funcion privada que retorna todos los registros de la tabla entregas
        $sinEntregar = $this->getEntregas($request);
        $sinEntregar = $sinEntregar->where('entregas.status','=',0);//Filtro para casas pendientes por entregar
        if($request->fecha_1 != '' && $request->fecha_2 != '')//Filtro para fecha programada para entregar
            $sinEntregar = $sinEntregar->whereBetween('entregas.fecha_program', [$request->fecha_1, $request->fecha_2]);
        $sinEntregar = $sinEntregar->orderBy('entregas.fecha_program','desc')->get();
        //Llamada a la funcion privada que retorna las ventas escrituradas sin entregar
        $firmadas = $this->getFirmadas($request);

        return[
            'entregas' => $entregas,
            'sinEntregar' => $sinEntregar,
            'firmadas' => $firmadas,
            'contEntregas' => $entregas->count(),
            'contSinEntregar' => $sinEntregar->count(),
            'contFirmadas' => $firmadas->count(),
        ];

    }
    //Funcion privada que retorna la query con todos los registros de la tabla entregas
    private function getEntregas(Request $request){
        $entrega = Entrega::join('expedientes','entregas.id','=','expedientes.id')
                    ->join('contratos','expedientes.id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('personal','creditos.prospecto_id','=','personal.id')
                    ->select('contratos.id as folio',
                        'personal.nombre','personal.apellidos',
                        'fraccionamientos.nombre as proyecto',
                        'etapas.num_etapa as etapa',
                        'lotes.manzana',
                        'lotes.num_lote',
                        'modelos.nombre as modelo',
                        'expedientes.fecha_firma_esc',
                        'entregas.fecha_program',
                        'entregas.fecha_entrega_real',
                        'entregas.status as entregado',
                        'entregas.revision_previa',
                        'entregas.puntualidad',
                        'entregas.cero_detalles',
                        'entregas.cont_reprogram',
                        'contratos.status'
                    )
                    ->where('contratos.status','=',3);
            if($request->proyecto != '')
                $entrega = $entrega->where('lotes.fraccionamiento_id','=',$request->proyecto);
            if($request->etapa != '')
                $entrega = $entrega->where('lotes.etapa_id','=',$request->etapa);

        return $entrega;
    }
    //Funcion privada que retorna la query con todas las ventas escrituradas sin entregar
    private function getFirmadas(Request $request){
        $firmadas = Expediente::leftJoin('entregas','expedientes.id','=','entregas.id')
                    ->join('contratos','expedientes.id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('personal','creditos.prospecto_id','=','personal.id')
                    ->select('contratos.id as folio',
                        'personal.nombre','personal.apellidos',
                        'fraccionamientos.nombre as proyecto',
                        'etapas.num_etapa as etapa',
                        'lotes.manzana',
                        'lotes.num_lote',
                        'modelos.nombre as modelo',
                        'expedientes.fecha_firma_esc',
                        'entregas.fecha_program',
                        'contratos.status'
                    )
                    ->where('contratos.status','=',3)
                    ->where('contratos.entregado','=',0)
                    ->where('expedientes.fecha_firma_esc','!=',NULL);

                    if($request->proyecto != '')
                        $firmadas = $firmadas->where('lotes.fraccionamiento_id','=',$request->proyecto);
                    if($request->etapa != '')
                        $firmadas = $firmadas->where('lotes.etapa_id','=',$request->etapa);
                    if($request->fecha_1 != '' && $request->fecha_2 != '')
                        $firmadas = $firmadas->whereBetween('expedientes.fecha_firma_esc', [$request->fecha_1, $request->fecha_2]);
                    $firmadas = $firmadas->orderBy('expedientes.fecha_firma_esc','desc')->get();

        return $firmadas;
    }

    public function reporteEmpresas(Request $request){
        $proyecto = $request->proyecto;
        $num = $request->num;
        $fecha = $request->b_fecha1;
        $fecha2 = $request->b_fecha2;
        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->select('fraccionamientos.nombre as proyecto')
                ->where('contratos.status','=',3);
                if($proyecto != '')
                        $ventas = $ventas->where('lotes.fraccionamiento_id','=',$proyecto);
                if($fecha != '' && $fecha2 != '')
                        $ventas = $ventas->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                if($request->b_empresa != '')
                        $ventas = $ventas->where('clientes.empresa','like','%'.$request->b_empresa.'%');
        $ventas = $ventas->distinct()->get();

        $ventasEmp = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->select('clientes.empresa')
                ->where('contratos.status','=',3);
                if($proyecto != '')
                        $ventasEmp = $ventasEmp->where('lotes.fraccionamiento_id','=',$proyecto);
                if($fecha != '' && $fecha2 != '')
                        $ventasEmp = $ventasEmp->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                if($request->b_empresa != '')
                        $ventasEmp = $ventasEmp->where('clientes.empresa','like','%'.$request->b_empresa.'%');

                        $ventasEmp = $ventasEmp->distinct()->get();

        $empresas = Empresa::select('nombre')->whereIn('nombre',$ventasEmp)->distinct()->get();

        $fraccionamientos = Fraccionamiento::select('nombre as proyecto','id')->whereIn('nombre',$ventas)->orderBy('id','asc')->distinct()->get();

        if(sizeof($fraccionamientos))
            foreach($fraccionamientos as $proyecto){
                $proyecto->etapas = Etapa::select('num_etapa','id')->where('fraccionamiento_id','=',$proyecto->id)->where('num_etapa','!=','Sin Asignar')->orderBy('id','asc')->get();
                $proyecto->numEtapas = $proyecto->etapas->count();
            }

        if(sizeof($empresas)){}
            foreach($empresas as $key => $empresa){
                $empresa->total = 0;
                foreach($fraccionamientos as $proyecto)
                    foreach($proyecto->etapas as $etapa){
                        $empresa[$etapa->num_etapa.'-'.$proyecto->proyecto] = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.empresa','lotes.fraccionamiento_id')
                                                ->where('contratos.status','=',3)
                                                ->where('lotes.etapa_id','=',$etapa->id)
                                                ->where('clientes.empresa','=',$empresa->nombre);
                                                if($fecha != '' && $fecha2 != '')
                                                    $empresa[$etapa->num_etapa.'-'.$proyecto->proyecto] = $empresa[$etapa->num_etapa.'-'.$proyecto->proyecto]->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                                $empresa[$etapa->num_etapa.'-'.$proyecto->proyecto] = $empresa[$etapa->num_etapa.'-'.$proyecto->proyecto]->count();
                        $empresa->total += $empresa[$etapa->num_etapa.'-'.$proyecto->proyecto];
                    }
                if($request->b_limite != '')
                    if($empresa->total < $request->b_limite)
                        unset($empresas[$key]);
            }

        return ['empresas' => $empresas,
            'proyecto' => $fraccionamientos
        ];//Empresa::all();
    }

    public function reporteEmpresasExcel(Request $request){
        $reporte = $this->reporteEmpresas($request);

        $empresas = $reporte['empresas'];
        $fraccionamientos = $reporte['proyecto'];

        //Creación y retorno de los resultados en excel.
        return Excel::create('Reporte de empresas', function($excel) use ($empresas,$fraccionamientos){
            $excel->sheet('Empresas', function($sheet) use ($fraccionamientos,$empresas){

                $cabecera1 = ['',''];
                $cabecera2 = ['Empresa','Total'];
                $num = 0;
                foreach($fraccionamientos as $index => $proyecto)
                    foreach($proyecto->etapas as $index => $etapa){
                        $num++;
                        array_push($cabecera1, $proyecto->proyecto);
                        array_push($cabecera2, $etapa->num_etapa);
                    }

                $ancho = 'B';
                switch($num){
                    case 1:{
                        $ancho = 'C';
                        break;
                    }
                    case 2:{
                        $ancho = 'D';
                        break;
                    }
                    case 3:{
                        $ancho = 'E';
                        break;
                    }
                    case 4:{
                        $ancho = 'F';
                        break;
                    }
                    case 5:{
                        $ancho = 'G';
                        break;
                    }
                    case 6:{
                        $ancho = 'H';
                        break;
                    }
                    case 7:{
                        $ancho = 'I';
                        break;
                    }
                    case 8:{
                        $ancho = 'J';
                        break;
                    }
                    case 9:{
                        $ancho = 'K';
                        break;
                    }
                    case 10:{
                        $ancho = 'L';
                        break;
                    }
                    case 11:{
                        $ancho = 'M';
                        break;
                    }
                    case 12:{
                        $ancho = 'N';
                        break;
                    }
                    case 13:{
                        $ancho = 'O';
                        break;
                    }
                    case 14:{
                        $ancho = 'P';
                        break;
                    }
                    case 15:{
                        $ancho = 'Q';
                        break;
                    }
                    case 16:{
                        $ancho = 'R';
                        break;
                    }
                    case 17:{
                        $ancho = 'S';
                        break;
                    }
                    case 18:{
                        $ancho = 'T';
                        break;
                    }
                    case 19:{
                        $ancho = 'U';
                        break;
                    }
                    case 20:{
                        $ancho = 'V';
                        break;
                    }
                    case 21:{
                        $ancho = 'W';
                        break;
                    }
                    case 22:{
                        $ancho = 'X';
                        break;
                    }
                    case 22:{
                        $ancho = 'Y';
                        break;
                    }
                    case 23:{
                        $ancho = 'Z';
                        break;
                    }
                    case 24:{
                        $ancho = 'AA';
                        break;
                    }
                }

                $sheet->row(1,$cabecera1);
                $sheet->row(2,$cabecera2);

                //$sheet->mergeCells('A1:'.$ancho.'1');

                $sheet->cells('A1:'.$ancho.'2', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                    $cont = 3;

                    foreach($empresas as $index => $empresa) {
                        $cont++;
                        $datos = [$empresa->nombre,$empresa->total];
                        foreach($fraccionamientos as $index => $proyecto)
                            foreach($proyecto->etapas as $index => $etapa)
                                array_push($datos, $empresa[$etapa->num_etapa.'-'.$proyecto->proyecto]);

                        $sheet->row($cont, $datos);

                    }

                $num='A1:'.$ancho . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        )->download('xls');
    }

    public function ventasAbastos(Request $request){
        //return $empresas = Empresa::select('nombre')->where('cp','=','78390')->where('colonia','like','%Abastos%')->get();

        $ventasEmp = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->select('personal.nombre','personal.apellidos', 'creditos.fraccionamiento',
                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','creditos.modelo',
                    'contratos.fecha_status','clientes.empresa',
                    'contratos.direccion_empresa',
                    'contratos.colonia_empresa'
                    )
                ->where('contratos.status','=',3)
                //->where('cp_empresa','=','78390')
                ->where('colonia_empresa','like','%Abastos%')
                ->get();

        // return [ 'contratos' => $ventasEmp,
        //         'total' => $ventasEmp->count()
        //     ];

        //Creación y retorno de los resultados en excel.
        return Excel::create('Reporte de empresas', function($excel) use ($ventasEmp){
            $excel->sheet('Abastos', function($sheet) use ($ventasEmp){

                $cabecera1 = ['Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Modelo',
                    'Fecha de compra', 'Empresa', 'Direccion'
                ];

                $ancho = 'J';

                $sheet->row(1,$cabecera1);

                //$sheet->mergeCells('A1:'.$ancho.'1');

                $sheet->cells('A1:'.$ancho.'1', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $cont = 2;

                foreach($ventasEmp as $index => $venta) {
                    $sheet->row($cont, [
                        $venta->nombre.' '.$venta->apellidos,
                        $venta->fraccionamiento,
                        $venta->etapa,
                        $venta->manzana,
                        $venta->num_lote,
                        $venta->modelo,
                        $venta->fecha_status,
                        $venta->empresa,
                        $venta->direccion_empresa.' Col.'.$venta->colonia_empresa,
                    ]);
                    $cont++;
                }

                $num='A1:'.$ancho . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        )->download('xls');

    }

    public function repVentasInventarios(Request $request){
        $inventarios = array(
                'proceso' => $this->getLotesDisponibles($request->emp_constructora,90,0),
                'terminadas' => $this->getLotesDisponibles($request->emp_constructora,95,0),
                'muestra' => $this->getLotesDisponibles($request->emp_constructora,0,1,1),
        );

        $noCobradas = array(
            'proceso' => $this->getVendidasSinCobrar(90,$request->emp_constructora),
            'terminadas' => $this->getVendidasSinCobrar(95,$request->emp_constructora),
        );

        $dispVentas = array(
            'proceso' => $this->getLotesDisponibles($request->emp_constructora,90,0,1),
            'terminadas' => $this->getLotesDisponibles($request->emp_constructora,95,0,1),
        );

        return[
            'inventarios' => $inventarios,
            'noCobradas' => $noCobradas,
            'dispVentas' => $dispVentas,
        ];

    }


    private function getLotesDisponibles($empresa,$avance,$muestra,$habilitado=0){
        $lotes = Lote::join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as e','lotes.etapa_id','=','e.id')
                ->join('modelos as m','lotes.modelo_id','=','m.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->select('f.nombre as proyecto','e.num_etapa as etapa','m.nombre as modelo',
                    'lotes.manzana','lotes.num_lote','lotes.sublote','lotes.casa_muestra', 'licencias.avance',
                    'lotes.id'
                )
                ->where('lotes.contrato','=',0)
                ->where('lotes.habilitado','=',$habilitado)
                ->where('lotes.casa_muestra','=',$muestra);

            if($muestra == 0){
                if($avance == 95)
                    $lotes = $lotes->where('licencias.avance','>',97);
                else
                    $lotes = $lotes->whereBetween('licencias.avance', [1, 97]);
            }

            if($empresa != '')//Filtro para empresa constructora
                $lotes = $lotes->where('lotes.emp_constructora','=', $empresa);

            $lotes = $lotes->paginate(20);

            return $lotes;

    }

    private function getVendidasSinCobrar($avance,$empresa){

        $lotes = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
            ->join('etapas as e','lotes.etapa_id','=','e.id')
            ->join('modelos as m','lotes.modelo_id','=','m.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->select('f.nombre as proyecto','e.num_etapa as etapa','m.nombre as modelo',
                'lotes.manzana','lotes.num_lote','lotes.sublote','lotes.casa_muestra', 'licencias.avance',
                'lotes.id'
            )
            ->whereIn('contratos.status',[1,3])
            ->where('inst_seleccionadas.elegido', '=', '1')
            ->whereNotIn('lotes.id',$this->getContadoIndividualizados($empresa))
            ->whereNotIn('lotes.id',$this->getCreditoIndividualizados($empresa));

            if($empresa != '')//Filtro para empresa constructora
                $lotes = $lotes->where('lotes.emp_constructora','=', $empresa);

            if($avance == 95)
                $lotes = $lotes->where('licencias.avance','>',97);
            else
                $lotes = $lotes->whereBetween('licencias.avance', [1, 97]);

            return $lotes->paginate(20);

    }

    private function getContadoIndividualizados($empresa){
        // CONSULTAS PARA LOS CONTRATOS COBRADOS (Créditos Directos individualizados)
        $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                ->join('creditos','contratos.id', '=', 'creditos.id')
                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->select('lotes.id')
                ->where('contratos.status','=',3)
                ->where('expedientes.liquidado','=',1)
                ->where('inst_seleccionadas.elegido', '=', '1')
                ->where('lotes.casa_muestra','=',0)
                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo');
        if($empresa != '')//Filtro para empresa constructora
        $indivContado = $indivContado->where('lotes.emp_constructora','=', $empresa);
        $indivContado = $indivContado->distinct('contratos.id')->get();

        return $indivContado;
    }

    private function getCreditoIndividualizados($empresa){
         // CONSULTAS PARA LOS CONTRATOS INDIVIDUALIZADOs (Ventas por financiamiento bancario)
         $indivCredito = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                ->join('creditos','contratos.id', '=', 'creditos.id')
                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->select('lotes.id')
                ->where('contratos.status','=',3)
                ->where('expedientes.fecha_firma_esc','!=',NULL)
                ->where('inst_seleccionadas.elegido', '=', '1')
                ->where('lotes.casa_muestra','=',0)
                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo');
        if($empresa != '')//Filtro para empresa constructora
        $indivCredito = $indivCredito->where('lotes.emp_constructora','=', $empresa);
        $indivCredito = $indivCredito->distinct('contratos.id')->get();

        return $indivCredito;
    }


}
