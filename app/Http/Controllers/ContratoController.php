<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Credito;
use App\Contrato;
use App\Pago_contrato;
use App\Apartado;
use DB;
use Auth;

use App\Precio_etapa;
use App\Precio_modelo;
use App\Sobreprecio_modelo;

use App\Solic_equipamiento;

use Carbon\Carbon;
use App\inst_seleccionada;
use App\Cliente;
use App\Personal;
use App\Vendedor;
use App\Licencia;
use App\Expediente;
use App\Gasto_admin;
use App\Deposito;
use App\Lote;
use App\Modelo;
use NumerosEnLetras;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationReceived;
use App\User;
use App\Notifications\NotifyAdmin;
use Excel;

class ContratoController extends Controller
{
    public function indexContrato(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $b_modelo = $request->b_modelo;
        $b_status = $request->b_status;
        $f_ini = $request->f_ini;
        $f_fin = $request->f_fin;
        $publicidad = $request->publicidad;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                ->select(
                    'expedientes.liquidado',
                    'creditos.id',
                    'creditos.prospecto_id',
                    'creditos.num_dep_economicos',
                    'creditos.tipo_economia',
                    'creditos.nombre_primera_ref',
                    'creditos.telefono_primera_ref',
                    'creditos.celular_primera_ref',
                    'creditos.nombre_segunda_ref',
                    'creditos.telefono_segunda_ref',
                    'creditos.celular_segunda_ref',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.modelo',
                    'creditos.precio_base',
                    'creditos.superficie',
                    'creditos.terreno_excedente',
                    'creditos.precio_terreno_excedente',
                    'creditos.promocion',
                    'creditos.descripcion_promocion',
                    'creditos.descuento_promocion',
                    'creditos.paquete',
                    'creditos.descripcion_paquete',
                    'creditos.precio_venta',
                    'creditos.plazo',
                    'creditos.credito_solic',
                    'creditos.costo_paquete',
                    'inst_seleccionadas.tipo_credito',
                    'inst_seleccionadas.id as inst_credito',
                    'creditos.precio_obra_extra',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.lote_id',
                    'contratos.publicidad_id as publicidadId','medios_publicitarios.nombre as publicidad',
                        'clientes.nombre_recomendado',

                    'inst_seleccionadas.institucion',
                    'personal.nombre',
                    'personal.apellidos',
                    'personal.telefono',
                    'personal.celular',
                    'personal.email',
                    'personal.direccion',
                    'personal.cp',
                    'personal.colonia',
                    'personal.f_nacimiento',
                    'personal.rfc',
                    'personal.homoclave',
                    'creditos.fraccionamiento',
                    'clientes.id as prospecto_id',
                    'clientes.edo_civil',
                    'clientes.nss',
                    'clientes.curp',
                    'clientes.empresa',
                    'clientes.coacreditado',
                    'clientes.estado',
                    'clientes.ciudad',
                    'clientes.puesto',
                    'clientes.nacionalidad',
                    'clientes.sexo',
                    'clientes.sexo_coa',
                    'clientes.email_institucional_coa',
                    'clientes.empresa_coa',
                    'clientes.edo_civil_coa',
                    'clientes.nss_coa',
                    'clientes.curp_coa',
                    'clientes.nombre_coa',
                    'clientes.apellidos_coa',
                    'clientes.f_nacimiento_coa',
                    'clientes.nacionalidad_coa',
                    'clientes.rfc_coa',
                    'clientes.homoclave_coa',
                    'clientes.direccion_coa',
                    'clientes.colonia_coa',
                    'clientes.ciudad_coa',
                    'clientes.estado_coa',
                    'clientes.cp_coa',
                    'clientes.telefono_coa',
                    'clientes.ext_coa',
                    'clientes.celular_coa',
                    'clientes.email_coa',
                    'clientes.parentesco_coa',
                    'clientes.lugar_nacimiento_coa',
                    'v.nombre as vendedor_nombre',
                    'v.apellidos as vendedor_apellidos',

                    'contratos.id as contratoId',
                    'contratos.infonavit',
                    'contratos.fovisste',
                    'contratos.comision_apertura',
                    'clientes.lugar_nacimiento',
                    'contratos.investigacion',
                    'contratos.avaluo',
                    'contratos.prima_unica',
                    'contratos.escrituras',
                    'contratos.credito_neto',
                    'contratos.status',
                    'contratos.fecha_status',
                    'contratos.avaluo_cliente',
                    'contratos.fecha',
                    'contratos.direccion_empresa',
                    'contratos.cp_empresa',
                    'contratos.colonia_empresa',
                    'contratos.estado_empresa',
                    'contratos.ciudad_empresa',
                    'contratos.telefono_empresa',
                    'contratos.ext_empresa',
                    'contratos.direccion_empresa_coa',
                    'contratos.cp_empresa_coa',
                    'contratos.colonia_empresa_coa',
                    'contratos.estado_empresa_coa',
                    'contratos.ciudad_empresa_coa',
                    'contratos.telefono_empresa_coa',
                    'contratos.ext_empresa_coa',
                    'contratos.total_pagar',
                    'contratos.monto_total_credito',
                    'contratos.enganche_total',
                    'contratos.avance_lote',
                    'contratos.observacion'
                );

        if(Auth::user()->rol_id != 2){
            if($b_status == ''){
                if ($buscar == '' && $criterio != 'creditos.vendedor_id') {
                    $contratos = $query
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->orderBy('id', 'desc')->paginate(20);

                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select('contratos.id as contratoId')
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->orderBy('id', 'desc')->count();
                } else {
                    switch ($criterio) {
                        case 'personal.nombre': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'v.nombre': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'inst_seleccionadas.tipo_credito': {
                                $contratos = $query
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'creditos.id': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'creditos.vendedor_id': {
                            if($f_ini =='' || $f_fin =='')
                                if($b_etapa == "" && $buscar3 == "" && $buscar != ""){
                                    $contratos = $query
                
                                        ->where($criterio, '=',$buscar)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->orderBy('id', 'desc')->paginate(20);
            
                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                        ->select('contratos.id as contratoId')
                                        ->where($criterio, '=', $buscar)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->orderBy('id', 'desc')->count();
                                }else{
                                    if($b_etapa == "" && $buscar3 != "" && $buscar != ""){
                                        $contratos = $query
                
                                            ->where($criterio, '=',$buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->orderBy('id', 'desc')->paginate(20);
                
                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                            ->select('contratos.id as contratoId')
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->orderBy('id', 'desc')->count();
                                    }else{
                                        if($b_etapa != "" && $buscar3 != "" && $buscar != ""){
                                            $contratos = $query
                    
                                                ->where($criterio, '=',$buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('lotes.etapa_id','=',$b_etapa)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->orderBy('id', 'desc')->paginate(20);
                        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('lotes.etapa_id','=',$b_etapa)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->orderBy('id', 'desc')->count();
                                        }
                                    }
                                }
                            else{
                                if($b_etapa == "" && $buscar3 == "" && $buscar != ""){
                                    $contratos = $query
                                        ->where($criterio, '=',$buscar)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                        ->orderBy('id', 'desc')->paginate(20);
            
                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                        ->select('contratos.id as contratoId')
                                        ->where($criterio, '=', $buscar)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                        ->orderBy('id', 'desc')->count();
                                }else{
                                    if($b_etapa == "" && $buscar3 != "" && $buscar != ""){
                                        $contratos = $query
                
                                            ->where($criterio, '=',$buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->paginate(20);
                
                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                            ->select('contratos.id as contratoId')
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->count();
                                    }else{
                                        if($b_etapa != "" && $buscar3 != "" && $buscar != ""){
                                            $contratos = $query
                    
                                            ->where($criterio, '=',$buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->paginate(20);
                    
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                            ->select('contratos.id as contratoId')
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->count();
                                        }
                                    }
                                }
                            }

                            break;
                            }
                        case 'contratos.fecha': {
                                $contratos = $query

                                    ->whereBetween($criterio, [$buscar, $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->whereBetween($criterio, [$buscar, $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'contratos.fecha_status': {
                                $contratos = $query

                                    ->whereBetween($criterio, [$buscar,  $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->whereBetween($criterio, [$buscar,  $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        
                        case 'creditos.fraccionamiento': {
                            if($publicidad == ''){
                                if($b_modelo == ''){
                                    if($f_ini == '' || $f_fin == ''){
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_modelo == '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else{
                                    if($f_ini == '' || $f_fin == ''){
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_modelo == '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '' && $b_lote == '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '' && $b_manzana == '' && $b_lote == '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '' && $b_etapa == '' && $b_lote == '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '' && $b_manzana == '' && $b_etapa == '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else{
                                if($b_modelo == ''){
                                    if($f_ini == '' || $f_fin == ''){
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_modelo == '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else{
                                    if($f_ini == '' || $f_fin == ''){
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_modelo == '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
        
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                                break;
                            }
                    
                        
                        }
                }
            }
            else{
                if ($buscar == '' && $criterio != 'creditos.vendedor_id') {
                    $contratos = $query
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('contratos.status','=',$b_status)
                        ->orderBy('id', 'desc')->paginate(20);

                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select('contratos.id as contratoId')
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('contratos.status','=',$b_status)
                        ->orderBy('id', 'desc')->count();
                } else {
                    switch ($criterio) {
                        case 'personal.nombre': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'v.nombre': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'inst_seleccionadas.tipo_credito': {
                                $contratos = $query
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'creditos.id': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'creditos.vendedor_id': {
                            if($f_ini == '' || $f_fin == ''){
                                if($b_etapa == "" && $buscar3 == "" && $buscar != ""){
                                    $contratos = $query
            
                                        ->where($criterio, '=',$buscar)
                                        ->where('contratos.status','=',$b_status)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->orderBy('id', 'desc')->paginate(20);
            
                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                        ->select('contratos.id as contratoId')
                                        ->where($criterio, '=', $buscar)
                                        ->where('contratos.status','=',$b_status)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->orderBy('id', 'desc')->count();
                                }else{
                                    if($b_etapa == "" && $buscar3 != "" && $buscar != ""){
                                        $contratos = $query
                
                                            ->where($criterio, '=',$buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->orderBy('id', 'desc')->paginate(20);
                
                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                            ->select('contratos.id as contratoId')
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->orderBy('id', 'desc')->count();
                                    }else{
                                        if($b_etapa != "" && $buscar3 != "" && $buscar != ""){
                                            $contratos = $query
                    
                                                ->where($criterio, '=',$buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('lotes.etapa_id','=',$b_etapa)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->orderBy('id', 'desc')->paginate(20);
                    
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('lotes.etapa_id','=',$b_etapa)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->orderBy('id', 'desc')->count();
                                        }
                                    }
                                }
                            }
                            else{
                                if($b_etapa == "" && $buscar3 == "" && $buscar != ""){
                                    $contratos = $query
            
                                        ->where($criterio, '=',$buscar)
                                        ->where('contratos.status','=',$b_status)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                        ->orderBy('id', 'desc')->paginate(20);
            
                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                        ->select('contratos.id as contratoId')
                                        ->where($criterio, '=', $buscar)
                                        ->where('contratos.status','=',$b_status)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                        ->orderBy('id', 'desc')->count();
                                }else{
                                    if($b_etapa == "" && $buscar3 != "" && $buscar != ""){
                                        $contratos = $query
                
                                            ->where($criterio, '=',$buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->paginate(20);
                
                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                            ->select('contratos.id as contratoId')
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->count();
                                    }else{
                                        if($b_etapa != "" && $buscar3 != "" && $buscar != ""){
                                            $contratos = $query
                    
                                                ->where($criterio, '=',$buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('lotes.etapa_id','=',$b_etapa)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
                    
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('lotes.etapa_id','=',$b_etapa)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        }
                                    }
                                }
                            }
                            
                            break;
                            }
                    
                        case 'contratos.fecha': {
                                $contratos = $query

                                    ->whereBetween($criterio, [$buscar, $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->whereBetween($criterio, [$buscar, $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }

                        case 'contratos.fecha_status': {
                                $contratos = $query

                                    ->whereBetween($criterio, [$buscar,  $buscar3])
                                    ->where('contratos.status','=',$b_status)
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->select('contratos.id as contratoId')
                                    ->whereBetween($criterio, [$buscar,  $buscar3])
                                    ->where('contratos.status','=',$b_status)
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        
                        case 'creditos.fraccionamiento': {
                            if($publicidad == ''){
                                if($b_modelo == ''){
                                    if($f_ini == '' || $f_fin == ''){
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->paginate(20);
            
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->paginate(20);
            
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->paginate(20);
            
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->paginate(20);
            
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
            
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
            
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->paginate(20);
            
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
            
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else{
                                    if($f_ini == '' || $f_fin == ''){
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->paginate(20);
            
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->paginate(20);
            
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->paginate(20);
            
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->paginate(20);
            
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
            
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
            
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->paginate(20);
            
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
            
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else{
                                if($b_modelo == ''){
                                    if($f_ini == '' || $f_fin == ''){
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->paginate(20);
            
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->paginate(20);
            
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->paginate(20);
            
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->paginate(20);
            
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
            
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
            
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->paginate(20);
            
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
            
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else{
                                    if($f_ini == '' || $f_fin == ''){
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->paginate(20);
            
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->paginate(20);
            
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->paginate(20);
            
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->paginate(20);
            
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
            
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '' && $b_manzana != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
            
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('contratos.publicidad_id', '=',  $publicidad)
                                                    ->where('lotes.modelo_id', '=',  $b_modelo)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                                    ->where('lotes.manzana', '=', $b_manzana)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_etapa != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->paginate(20);
            
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('contratos.publicidad_id', '=',  $publicidad)
                                                        ->where('lotes.modelo_id', '=',  $b_modelo)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_manzana != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
            
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('contratos.publicidad_id', '=',  $publicidad)
                                                            ->where('lotes.modelo_id', '=',  $b_modelo)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.manzana', '=', $b_manzana)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        if ($b_lote != '') {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento', '=',  $buscar)
                                                                ->where('lotes.num_lote', '=', $b_lote)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        } else {
                                                            $contratos = $query
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->paginate(20);
            
                                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                                ->select('contratos.id as contratoId')
                                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                                ->where('contratos.status','=',$b_status)
                                                                ->where('contratos.publicidad_id', '=',  $publicidad)
                                                                ->where('lotes.modelo_id', '=',  $b_modelo)
                                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                                ->orderBy('id', 'desc')->count();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        }
                    }
                }

            }
        }
        ///////// AUTH 2
        else{
            if($b_status == ''){
                if ($buscar == '' && $criterio != 'creditos.vendedor_id') {
                    $contratos = $query
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('creditos.vendedor_id', '=', Auth::user()->id)
                        ->orderBy('id', 'desc')->paginate(20);

                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                        ->select('contratos.id as contratoId')
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('creditos.vendedor_id', '=', Auth::user()->id)
                        ->orderBy('id', 'desc')->count();
                } else {
                    switch ($criterio) {
                        case 'personal.nombre': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }

                        case 'inst_seleccionadas.tipo_credito': {
                                $contratos = $query
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'creditos.id': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }

                        case 'contratos.fecha': {
                                $contratos = $query

                                    ->whereBetween($criterio, [$buscar, $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->whereBetween($criterio, [$buscar, $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'contratos.fecha_status': {
                                $contratos = $query

                                    ->whereBetween($criterio, [$buscar,  $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->whereBetween($criterio, [$buscar,  $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }       
                    }
                }
            }
            else{
                if ($buscar == '' && $criterio != 'creditos.vendedor_id') {
                    $contratos = $query
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('creditos.vendedor_id', '=', Auth::user()->id)
                        ->where('contratos.status','=',$b_status)
                        ->orderBy('id', 'desc')->paginate(20);

                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                        ->select('contratos.id as contratoId')
                        ->where('inst_seleccionadas.elegido', '=', '1')
                        ->where('creditos.vendedor_id', '=', Auth::user()->id)
                        ->where('contratos.status','=',$b_status)
                        ->orderBy('id', 'desc')->count();
                } else {
                    switch ($criterio) {
                        case 'personal.nombre': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'v.nombre': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'inst_seleccionadas.tipo_credito': {
                                $contratos = $query
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'creditos.id': {
                                $contratos = $query

                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        case 'creditos.vendedor_id': {
                                if($f_ini == '' || $f_fin == ''){
                                    if($b_etapa == "" && $buscar3 == "" && $buscar != ""){
                                        $contratos = $query
                
                                            ->where($criterio, '=',$buscar)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                            ->orderBy('id', 'desc')->paginate(20);
                
                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                            ->select('contratos.id as contratoId')
                                            ->where($criterio, '=', $buscar)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                            ->orderBy('id', 'desc')->count();
                                    }else{
                                        if($b_etapa == "" && $buscar3 != "" && $buscar != ""){
                                            $contratos = $query
                                                ->where($criterio, '=',$buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                ->orderBy('id', 'desc')->paginate(20);
                    
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                ->select('contratos.id as contratoId')
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                ->orderBy('id', 'desc')->count();
                                        }else{
                                            if($b_etapa != "" && $buscar3 != "" && $buscar != ""){
                                                $contratos = $query
                                                    ->where($criterio, '=',$buscar)
                                                    ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                    ->where('lotes.etapa_id','=',$b_etapa)
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                    ->orderBy('id', 'desc')->paginate(20);
                        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where($criterio, '=', $buscar)
                                                    ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                    ->where('lotes.etapa_id','=',$b_etapa)
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                    ->orderBy('id', 'desc')->count();
                                            }
                                        }
                                    }
                                }
                                else{
                                    if($b_etapa == "" && $buscar3 == "" && $buscar != ""){
                                        $contratos = $query
                                            ->where($criterio, '=',$buscar)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->paginate(20);
                
                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                            ->select('contratos.id as contratoId')
                                            ->where($criterio, '=', $buscar)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->count();
                                    }else{
                                        if($b_etapa == "" && $buscar3 != "" && $buscar != ""){
                                            $contratos = $query
                                                ->where($criterio, '=',$buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
                    
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                ->select('contratos.id as contratoId')
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        }else{
                                            if($b_etapa != "" && $buscar3 != "" && $buscar != ""){
                                                $contratos = $query
                                                    ->where($criterio, '=',$buscar)
                                                    ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                    ->where('lotes.etapa_id','=',$b_etapa)
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
                        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where($criterio, '=', $buscar)
                                                    ->where('lotes.fraccionamiento_id','=',$buscar3)
                                                    ->where('lotes.etapa_id','=',$b_etapa)
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            }
                                        }
                                    }
                                }
                            
                            break;
                            }
                    
                        case 'contratos.fecha': {
                                $contratos = $query

                                    ->whereBetween($criterio, [$buscar, $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->whereBetween($criterio, [$buscar, $buscar3])
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->where('contratos.status','=',$b_status)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }

                        case 'contratos.fecha_status': {
                                $contratos = $query

                                    ->whereBetween($criterio, [$buscar,  $buscar3])
                                    ->where('contratos.status','=',$b_status)
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->paginate(20);

                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                    ->select('contratos.id as contratoId')
                                    ->whereBetween($criterio, [$buscar,  $buscar3])
                                    ->where('contratos.status','=',$b_status)
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                    ->orderBy('id', 'desc')->count();
                                break;
                            }
                        
                        case 'creditos.fraccionamiento': {
                                if($f_ini == '' || $f_fin == ''){
                                    if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                        $contratos = $query
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', '=', $b_lote)
                                            ->orderBy('id', 'desc')->paginate(20);
        
                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                            ->select('contratos.id as contratoId')
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', '=', $b_lote)
                                            ->orderBy('id', 'desc')->count();
                                    } else {
                                        if ($b_etapa != '' && $b_manzana != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=',  $b_etapa )
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=',  $b_etapa )
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_manzana != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.manzana', '=', $b_manzana)
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.manzana', '=', $b_manzana)
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_lote != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.num_lote', '=', $b_lote)
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento', '=',  $buscar)
                                                            ->where('lotes.num_lote', '=', $b_lote)
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->orderBy('id', 'desc')->count();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else{
                                    if ($b_etapa != '' && $b_manzana != '' && $b_lote != '' && $b_status!= '' && $buscar != '') {
                                        $contratos = $query
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', '=', $b_lote)
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->paginate(20);
        
                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                            ->select('contratos.id as contratoId')
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', '=', $b_lote)
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->count();
                                    } else {
                                        if ($b_etapa != '' && $b_manzana != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->paginate(20);
        
                                            $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                ->select('contratos.id as contratoId')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->count();
                                        } else {
                                            if ($b_etapa != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=',  $b_etapa )
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->paginate(20);
        
                                                $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                    ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                    ->select('contratos.id as contratoId')
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.etapa_id', '=',  $b_etapa )
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->count();
                                            } else {
                                                if ($b_manzana != '') {
                                                    $contratos = $query
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.manzana', '=', $b_manzana)
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->paginate(20);
        
                                                    $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                        ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                        ->select('contratos.id as contratoId')
                                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                                        ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                        ->where('contratos.status','=',$b_status)
                                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                        ->where('lotes.manzana', '=', $b_manzana)
                                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                        ->orderBy('id', 'desc')->count();
                                                } else {
                                                    if ($b_lote != '') {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->where('lotes.num_lote', '=', $b_lote)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento', '=',  $buscar)
                                                            ->where('lotes.num_lote', '=', $b_lote)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    } else {
                                                        $contratos = $query
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->paginate(20);
        
                                                        $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                                                            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                                                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                                            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                                            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                                                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                                                            ->select('contratos.id as contratoId')
                                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                                            ->where('creditos.vendedor_id', '=', Auth::user()->id)
                                                            ->where('contratos.status','=',$b_status)
                                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                            ->orderBy('id', 'desc')->count();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                break;
                            }
                    }
                }

            }
        }

        return [
            'pagination' => [
                'total'         => $contratos->total(),
                'current_page'  => $contratos->currentPage(),
                'per_page'      => $contratos->perPage(),
                'last_page'     => $contratos->lastPage(),
                'from'          => $contratos->firstItem(),
                'to'            => $contratos->lastItem(),
            ], 'contratos' => $contratos, 'contadorContrato' => $contadorContratos
        ];
    }

    public function indexCreditosAprobados(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        $query = Credito::join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.precio_base',
                'creditos.precio_venta',
                'creditos.plazo',
                'creditos.credito_solic',
                'creditos.status',
                'inst_seleccionadas.tipo_credito',
                'inst_seleccionadas.id as inst_credito',
                'inst_seleccionadas.institucion',
                'personal.nombre',
                'personal.apellidos',
                'creditos.fraccionamiento as proyecto',
                'clientes.id as prospecto_id',
                'v.nombre as vendedor_nombre',
                'v.apellidos as vendedor_apellidos'
            );

        if ($buscar == '') {
            $creditos = $query
                ->where('creditos.status', '=', '2')
                ->where('inst_seleccionadas.elegido', '=', '1')
                ->where('creditos.contrato', '=', '0')
                ->orderBy('id', 'desc')->paginate(8);
        } else {
            switch ($criterio) {
                case 'personal.nombre': {
                        $creditos = $query
                            ->where($criterio, 'like', '%' . $buscar . '%')
                            ->where('creditos.status', '=', '2')
                            ->where('inst_seleccionadas.elegido', '=', '1')
                            ->where('creditos.contrato', '=', '0')
                            ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                            ->where('creditos.status', '=', '2')
                            ->where('inst_seleccionadas.elegido', '=', '1')
                            ->where('creditos.contrato', '=', '0')
                            ->orderBy('id', 'desc')->paginate(8);
                        break;
                    }
                case 'v.nombre': {
                        $creditos = $query

                            ->where($criterio, 'like', '%' . $buscar . '%')
                            ->where('creditos.status', '=', '2')
                            ->where('inst_seleccionadas.elegido', '=', '1')
                            ->where('creditos.contrato', '=', '0')
                            ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                            ->where('creditos.status', '=', '2')
                            ->where('inst_seleccionadas.elegido', '=', '1')
                            ->where('creditos.contrato', '=', '0')
                            ->orderBy('id', 'desc')->paginate(8);
                        break;
                    }
                case 'inst_seleccionadas.tipo_credito': {
                        $creditos = $query
                            ->where($criterio, 'like', '%' . $buscar . '%')
                            ->where('creditos.status', '=', '2')
                            ->where('inst_seleccionadas.elegido', '=', '1')
                            ->where('creditos.contrato', '=', '0')
                            ->orderBy('id', 'desc')->paginate(8);
                        break;
                    }
                case 'creditos.id': {
                        $creditos = $query
                            ->where($criterio, 'like', '%' . $buscar . '%')
                            ->where('creditos.status', '=', '2')
                            ->where('inst_seleccionadas.elegido', '=', '1')
                            ->where('creditos.contrato', '=', '0')
                            ->orderBy('id', 'desc')->paginate(8);
                        break;
                    }
                case 'creditos.fraccionamiento': {
                        if ($b_etapa != '' && $b_manzana != '' && $b_lote != '') {
                            $creditos = $query
                                ->where('creditos.status', '=', '2')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('creditos.contrato', '=', '0')
                                ->orderBy('id', 'desc')->paginate(8);
                        } else {
                            if ($b_etapa != '' && $b_manzana != '') {
                                $creditos = $query
                                    ->where('creditos.status', '=', '2')
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                    ->where('lotes.etapa_id', '=', $b_etapa )
                                    ->where('lotes.manzana', '=', $b_manzana)
                                    ->where('creditos.contrato', '=', '0')
                                    ->orderBy('id', 'desc')->paginate(8);
                            } else {
                                if ($b_etapa != '') {
                                    $creditos = $query
                                        ->where('creditos.status', '=', '2')
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                        ->where('lotes.etapa_id', '=',  $b_etapa )
                                        ->where('creditos.contrato', '=', '0')
                                        ->orderBy('id', 'desc')->paginate(8);
                                } else {
                                    if ($b_manzana != '') {
                                        $creditos = $query
                                            ->where('creditos.status', '=', '2')
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('creditos.contrato', '=', '0')
                                            ->orderBy('id', 'desc')->paginate(8);
                                    } else {
                                        if ($b_lote != '') {
                                            $creditos = $query
                                                ->where('creditos.status', '=', '2')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.num_lote', '=', $b_lote)
                                                ->where('creditos.contrato', '=', '0')
                                                ->orderBy('id', 'desc')->paginate(8);
                                        } else {
                                            $creditos = $query
                                                ->where('creditos.status', '=', '2')
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('creditos.contrato', '=', '0')
                                                ->orderBy('id', 'desc')->paginate(8);
                                        }
                                    }
                                }
                            }
                        }
                        break;
                    }
            }
        }

        return [
            'pagination' => [
                'total'         => $creditos->total(),
                'current_page'  => $creditos->currentPage(),
                'per_page'      => $creditos->perPage(),
                'last_page'     => $creditos->lastPage(),
                'from'          => $creditos->firstItem(),
                'to'            => $creditos->lastItem(),
            ], 'creditos' => $creditos
        ];
    }

    public function getDatosCredito(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $folio = $request->folio;
        $creditos = Credito::join('datos_extra', 'creditos.id', '=', 'datos_extra.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.num_dep_economicos',
                'creditos.tipo_economia',
                'creditos.nombre_primera_ref',
                'creditos.telefono_primera_ref',
                'creditos.celular_primera_ref',
                'creditos.nombre_segunda_ref',
                'creditos.telefono_segunda_ref',
                'creditos.celular_segunda_ref',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.precio_base',
                'creditos.precio_obra_extra',
                'creditos.superficie',
                'creditos.terreno_excedente',
                'creditos.precio_terreno_excedente',
                'creditos.promocion',
                'creditos.descripcion_promocion',
                'creditos.descuento_promocion',
                'creditos.paquete',
                'creditos.descripcion_paquete',
                'creditos.precio_venta',
                'creditos.plazo',
                'creditos.credito_solic',
                'creditos.lote_id',
                'creditos.fraccionamiento as proyecto',
                'creditos.costo_paquete',
                'creditos.status'
            )
            ->where('creditos.id', '=', $folio)->get();

        foreach ($creditos as $index => $credito) {
            $prospecto = [];
            $prospecto = Cliente::join('personal', 'clientes.id', '=', 'personal.id')
                ->select(
                    'personal.nombre',
                    'personal.apellidos',
                    'clientes.sexo',
                    'clientes.nombre_recomendado',
                    'clientes.publicidad_id',
                    'personal.telefono',
                    'personal.celular',
                    'personal.email',
                    'personal.direccion',
                    'personal.cp',
                    'personal.colonia',
                    'clientes.ciudad',
                    'clientes.estado',
                    'personal.f_nacimiento',
                    'clientes.nacionalidad',
                    'clientes.curp',
                    'personal.rfc',
                    'personal.homoclave',
                    'clientes.nss',
                    'clientes.empresa',
                    'clientes.puesto',
                    'clientes.email_institucional',
                    'clientes.tipo_casa',
                    'clientes.edo_civil',
                    'clientes.coacreditado',
                    'clientes.nombre_coa',
                    'clientes.apellidos_coa',
                    'clientes.f_nacimiento_coa',
                    'clientes.rfc_coa',
                    'clientes.homoclave_coa',
                    'clientes.direccion_coa',
                    'clientes.cp_coa',
                    'clientes.colonia_coa',
                    'clientes.estado_coa',
                    'clientes.ciudad_coa',
                    'clientes.celular_coa',
                    'clientes.telefono_coa',
                    'clientes.email_coa',
                    'clientes.email_institucional_coa',
                    'clientes.parentesco_coa',
                    'clientes.empresa_coa',
                    'clientes.curp_coa',
                    'clientes.nss_coa',
                    'clientes.nacionalidad_coa',
                    'clientes.lugar_nacimiento'
                    
                )
                ->where('clientes.id', '=', $credito->prospecto_id)->get();

            $institucion = [];
            $institucion = inst_seleccionada::select('tipo_credito', 'institucion', 'elegido')
                ->where('credito_id', '=', $credito->id)
                ->where('elegido', '=', 1)->get();
            if (sizeof($prospecto) > 0) {
                $credito->nombre = $prospecto[0]->nombre;
                $credito->apellidos = $prospecto[0]->apellidos;
                $credito->sexo = $prospecto[0]->sexo;
                $credito->telefono = $prospecto[0]->telefono;
                $credito->celular = $prospecto[0]->celular;
                $credito->email = $prospecto[0]->email;
                $credito->direccion = $prospecto[0]->direccion;
                $credito->cp = $prospecto[0]->cp;
                $credito->colonia = $prospecto[0]->colonia;
                $credito->ciudad = $prospecto[0]->ciudad;
                $credito->estado = $prospecto[0]->estado;
                $credito->f_nacimiento = $prospecto[0]->f_nacimiento;
                $credito->nacionalidad = $prospecto[0]->nacionalidad;
                $credito->curp = $prospecto[0]->curp;
                $credito->rfc = $prospecto[0]->rfc;
                $credito->homoclave = $prospecto[0]->homoclave;
                $credito->nss = $prospecto[0]->nss;
                $credito->empresa = $prospecto[0]->empresa;
                $credito->puesto = $prospecto[0]->puesto;
                $credito->email_institucional = $prospecto[0]->email_institucional;
                $credito->tipo_casa = $prospecto[0]->tipo_casa;
                $credito->edo_civil = $prospecto[0]->edo_civil;
                $credito->coacreditado = $prospecto[0]->coacreditado;
                $credito->nombre_coa = $prospecto[0]->nombre_coa;
                $credito->apellidos_coa = $prospecto[0]->apellidos_coa;
                $credito->f_nacimiento_coa = $prospecto[0]->f_nacimiento_coa;
                $credito->rfc_coa = $prospecto[0]->rfc_coa;
                $credito->curp_coa = $prospecto[0]->curp_coa;
                $credito->nss_coa = $prospecto[0]->nss_coa;
                $credito->homoclave_coa = $prospecto[0]->homoclave_coa;
                $credito->direccion_coa = $prospecto[0]->direccion_coa;
                $credito->cp_coa = $prospecto[0]->cp_coa;
                $credito->nacionalidad_coa = $prospecto[0]->nacionalidad_coa;
                $credito->colonia_coa = $prospecto[0]->colonia_coa;
                $credito->estado_coa = $prospecto[0]->estado_coa;
                $credito->ciudad_coa = $prospecto[0]->ciudad_coa;
                $credito->celular_coa = $prospecto[0]->celular_coa;
                $credito->telefono_coa = $prospecto[0]->telefono_coa;
                $credito->email_coa = $prospecto[0]->email_coa;
                $credito->email_institucional_coa = $prospecto[0]->email_institucional_coa;
                $credito->empresa_coa = $prospecto[0]->empresa_coa;
                $credito->nombre_recomendado = $prospecto[0]->nombre_recomendado;
                $credito->tipo_credito = $institucion[0]->tipo_credito;
                $credito->institucion = $institucion[0]->institucion;
                $credito->elegido = $institucion[0]->elegido;
                $credito->publicidadId = $prospecto[0]->publicidad_id;
                $credito->lugar_nacimiento = $prospecto[0]->lugar_nacimiento;
            }
        }


        return ['creditos' => $creditos];
    }

    public function updateDatosCredito(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        //datos del cliente que se guardan en la tabla personal
        $personal = Personal::findOrFail($request->prospecto_id);
        $personal->apellidos = $request->apellidos;
        $personal->nombre = $request->nombre;
        $personal->f_nacimiento = $request->f_nacimiento;
        $personal->rfc = $request->rfc;
        $personal->homoclave = $request->homoclave;
        $personal->direccion = $request->direccion;
        $personal->cp = $request->cp;
        $personal->colonia = $request->colonia;
        $personal->telefono = $request->telefono;
        $personal->celular = $request->celular;
        $personal->email = $request->email;

        $cliente = Cliente::findOrFail($request->prospecto_id);
        $cliente->sexo = $request->sexo;
        $cliente->email_institucional = $request->email_institucional;
        $cliente->edo_civil = $request->edo_civil;
        $cliente->nss = $request->nss;
        $cliente->curp = $request->curp;
        $cliente->empresa = $request->empresa;
        $cliente->coacreditado = $request->coacreditado;
        $cliente->ciudad = $request->ciudad;
        $cliente->estado = $request->estado;
        $cliente->nacionalidad = $request->nacionalidad;
        $cliente->puesto = $request->puesto;
        $cliente->sexo_coa = $request->sexo_coa;
        $cliente->direccion_coa = $request->direccion_coa;
        $cliente->email_institucional_coa = $request->email_institucional_coa;
        $cliente->edo_civil_coa = $request->edo_civil_coa;
        $cliente->nss_coa = $request->nss_coa;
        $cliente->curp_coa = $request->curp_coa;
        $cliente->nombre_coa = $request->nombre_coa;
        $cliente->apellidos_coa = $request->apellidos_coa;
        $cliente->f_nacimiento_coa = $request->f_nacimiento_coa;
        $cliente->colonia_coa = $request->colonia_coa;
        $cliente->cp_coa = $request->cp_coa;
        $cliente->rfc_coa = $request->rfc_coa;
        $cliente->homoclave_coa = $request->homoclave_coa;
        $cliente->ciudad_coa = $request->ciudad_coa;
        $cliente->estado_coa = $request->estado_coa;
        $cliente->empresa_coa = $request->empresa_coa;
        $cliente->nacionalidad_coa = $request->nacionalidad_coa;
        $cliente->telefono_coa = $request->telefono_coa;
        $cliente->celular_coa = $request->celular_coa;
        $cliente->email_coa = $request->email_coa;
        $cliente->parentesco_coa = $request->parentesco_coa;
        $cliente->lugar_nacimiento = $request->lugar_nacimiento;
        
        $cliente->publicidad_id = $request->publicidad_id;
        $cliente->nombre_recomendado = $request->nombre_recomendado;

        $credito = Credito::findOrFail($request->id);
        $credito->num_dep_economicos =  $request->num_dep_economicos;
        $credito->credito_solic = $request->credito_solic;
        $credito->contrato = 1;

        $inst_sel = inst_seleccionada::select('id')
                    ->where('credito_id','=',$request->id)
                    ->where('elegido','=',1)->get();
        
        $credito_sol = inst_seleccionada::findOrFail($inst_sel[0]->id);
        $credito_sol->monto_credito = $request->credito_solic;
        $credito_sol->save();

        $lote = Lote::findOrFail($request->lote_id);
        $lote->contrato = 1;

        $lote->save();
        $credito->save();
        $personal->save();
        $cliente->save();
    }

    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $id = $request->id;
        $lote = Licencia::select('avance')
            ->where('id', '=', $request->lote_id)->get();

        

        try {
            DB::beginTransaction();
            $contrato = new Contrato();
            $contrato->id = $request->id;
            $contrato->infonavit = $request->infonavit;
            $contrato->fovisste = $request->fovisste;
            $contrato->comision_apertura = $request->comision_apertura;
            $contrato->investigacion = $request->investigacion;
            $contrato->avaluo = $request->avaluo;
            $contrato->prima_unica = $request->prima_unica;
            $contrato->escrituras = $request->escrituras;
            $contrato->credito_neto = $request->credito_neto;
            $contrato->avaluo_cliente = $request->avaluo_cliente;
            $contrato->fecha = $request->fecha;
            $contrato->direccion_empresa = $request->direccion_empresa;
            $contrato->cp_empresa = $request->cp_empresa;
            $contrato->colonia_empresa = $request->colonia_empresa;
            $contrato->estado_empresa = $request->estado_empresa;
            $contrato->ciudad_empresa = $request->ciudad_empresa;
            $contrato->telefono_empresa = $request->telefono_empresa;
            $contrato->ext_empresa = $request->ext_empresa;
            $contrato->direccion_empresa_coa = $request->direccion_empresa_coa;
            $contrato->cp_empresa_coa = $request->cp_empresa_coa;
            $contrato->colonia_empresa_coa = $request->colonia_empresa_coa;
            $contrato->estado_empresa_coa = $request->estado_empresa_coa;
            $contrato->ciudad_empresa_coa = $request->ciudad_empresa_coa;
            $contrato->telefono_empresa_coa = $request->telefono_empresa_coa;
            $contrato->ext_empresa_coa = $request->ext_empresa_coa;
            $contrato->total_pagar = $request->total_pagar;
            $contrato->monto_total_credito = $request->monto_total_credito;
            $contrato->enganche_total = $request->enganche_total;
            $contrato->avance_lote = $lote[0]->avance;
            $contrato->observacion = $request->observacion;

            $credito = Credito::findOrFail($request->id);

            $cliente = Cliente::findOrFail($credito->prospecto_id);
            $contrato->publicidad_id = $cliente->publicidad_id;
            
            $vendedor = Vendedor::findOrFail($credito->vendedor_id);
            $contrato->saldo = $request->precio_venta;
            
            $credito->paquete =  $request->paquete;
            $credito->descripcion_paquete = $request->descripcion_paquete;
            $credito->costo_paquete = $request->costo_paquete;
            $credito->precio_venta = $request->precio_venta;
            $credito->save();

            if($vendedor->tipo == 1){
                $contrato->porcentaje_exp = 100;
            }

            $contrato->save();

            $pagos = $request->data; //Array de detalles
            //Recorro todos los elementos

            foreach ($pagos as $ep => $det) {
                $pagos = new Pago_contrato();
                $pagos->contrato_id = $id;
                $pagos->num_pago = $ep;
                $pagos->monto_pago = $det['monto_pago'];
                $pagos->restante = $det['monto_pago'];
                $pagos->fecha_pago = $det['fecha_pago'];
                $pagos->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function listarPagos(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $pagos = Pago_contrato::select('id', 'num_pago', 'monto_pago', 'fecha_pago','restante')
            ->where('contrato_id', '=', $request->contrato_id)
            ->orderBy('fecha_pago', 'ASC')
            ->get();

        return ['pagos' => $pagos];
    }

    public function contratoCompraVentaPdf(Request $request, $id)
    {
        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('medios_publicitarios', 'clientes.publicidad_id', '=', 'medios_publicitarios.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.num_dep_economicos',
                'creditos.tipo_economia',
                'creditos.nombre_primera_ref',
                'creditos.telefono_primera_ref',
                'creditos.celular_primera_ref',
                'creditos.nombre_segunda_ref',
                'creditos.telefono_segunda_ref',
                'creditos.celular_segunda_ref',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.precio_base',
                'creditos.superficie',
                'creditos.terreno_excedente',
                'creditos.precio_terreno_excedente',
                'creditos.promocion',
                'creditos.descripcion_promocion',
                'creditos.descuento_promocion',
                'creditos.paquete',
                'creditos.descripcion_paquete',
                'creditos.precio_venta',
                'creditos.plazo',
                'creditos.credito_solic',
                'creditos.costo_paquete',
                'inst_seleccionadas.tipo_credito',
                'inst_seleccionadas.id as inst_credito',
                'creditos.precio_obra_extra',
                'creditos.fraccionamiento as proyecto',

                'lotes.calle',
                'lotes.numero',
                'lotes.interior',
                'lotes.terreno',
                'lotes.construccion',
                'lotes.sobreprecio',
                'lotes.fecha_termino_ventas',
                'medios_publicitarios.nombre as medio_publicidad',
                'lotes.ajuste',

                'inst_seleccionadas.institucion',
                'personal.nombre',
                'personal.apellidos',
                'personal.telefono',
                'personal.celular',
                'personal.email',
                'clientes.email_institucional',
                'personal.direccion',
                'personal.cp',
                'personal.colonia',
                'personal.f_nacimiento',
                'personal.rfc',
                'personal.homoclave',
                'creditos.fraccionamiento',
                'clientes.id as prospecto_id',
                'clientes.edo_civil',
                'clientes.nss',
                'clientes.curp',
                'clientes.empresa',
                'clientes.coacreditado',
                'clientes.estado',
                'clientes.ciudad',
                'clientes.puesto',
                'clientes.nacionalidad',
                'clientes.sexo',
                'clientes.sexo_coa',
                'clientes.email_institucional_coa',
                'clientes.empresa_coa',
                'clientes.edo_civil_coa',
                'clientes.nss_coa',
                'clientes.curp_coa',
                'clientes.nombre_coa',
                'clientes.apellidos_coa',
                'clientes.f_nacimiento_coa',
                'clientes.nacionalidad_coa',
                'clientes.rfc_coa',
                'clientes.homoclave_coa',
                'clientes.direccion_coa',
                'clientes.colonia_coa',
                'clientes.ciudad_coa',
                'clientes.estado_coa',
                'clientes.cp_coa',
                'clientes.telefono_coa',
                'clientes.ext_coa',
                'clientes.celular_coa',
                'clientes.email_coa',
                'clientes.parentesco_coa',
                'clientes.lugar_nacimiento_coa',
                'clientes.nombre_recomendado',
                'v.nombre as vendedor_nombre',
                'v.apellidos as vendedor_apellidos',

                'contratos.infonavit',
                'contratos.fovisste',
                'contratos.comision_apertura',
                'clientes.lugar_nacimiento',
                'contratos.investigacion',
                'contratos.avaluo',
                'contratos.prima_unica',
                'contratos.escrituras',
                'contratos.credito_neto',
                'contratos.status',
                'contratos.avaluo_cliente',
                'contratos.fecha',
                'contratos.direccion_empresa',
                'contratos.cp_empresa',
                'contratos.colonia_empresa',
                'contratos.estado_empresa',
                'contratos.ciudad_empresa',
                'contratos.telefono_empresa',
                'contratos.ext_empresa',
                'contratos.direccion_empresa_coa',
                'contratos.cp_empresa_coa',
                'contratos.colonia_empresa_coa',
                'contratos.estado_empresa_coa',
                'contratos.ciudad_empresa_coa',
                'contratos.telefono_empresa_coa',
                'contratos.ext_empresa_coa',
                'contratos.total_pagar',
                'contratos.monto_total_credito',
                'contratos.enganche_total',
                'contratos.avance_lote',
                'contratos.observacion'
            )
            ->where('inst_seleccionadas.elegido', '=', '1')
            ->where('contratos.id', '=', $id)
            ->orderBy('id', 'desc')->get();

            if($contratos[0]->institucion == 'Crea Más' || $contratos[0]->institucion == 'Gamu'){
                $contratos[0]->institucion = 'INFONAVIT';
            }

        setlocale(LC_TIME, 'es_MX.utf8');
        $tiempo = new Carbon($contratos[0]->fecha);
        $contratos[0]->fecha = $tiempo->formatLocalized('%d de %B de %Y');

        $fecha_termino_ventas = new Carbon($contratos[0]->fecha_termino_ventas);
        $contratos[0]->fecha_termino_ventas = $fecha_termino_ventas->formatLocalized('%B de %Y');

        $fecha_nac = new Carbon($contratos[0]->f_nacimiento);
        $contratos[0]->f_nacimiento = $fecha_nac->formatLocalized('%d-%m-%Y');

        $fecha_nac_coa = new Carbon($contratos[0]->f_nacimiento_coa);
        $contratos[0]->f_nacimiento_coa = $fecha_nac_coa->formatLocalized('%d-%m-%Y');

        $contratos[0]->precio_base = $contratos[0]->precio_base - $contratos[0]->descuento_promocion;

        $contratos[0]->precio_base = number_format((float)$contratos[0]->precio_base, 2, '.', ',');
        $contratos[0]->credito_solic = number_format((float)$contratos[0]->credito_solic, 2, '.', ',');
        $contratos[0]->precio_terreno_excedente = number_format((float)$contratos[0]->precio_terreno_excedente, 2, '.', ',');
        $contratos[0]->comision_apertura = number_format((float)$contratos[0]->comision_apertura, 2, '.', ',');
        $contratos[0]->precio_obra_extra = number_format((float)$contratos[0]->precio_obra_extra, 2, '.', ',');
        $contratos[0]->investigacion = number_format((float)$contratos[0]->investigacion, 2, '.', ',');
        $contratos[0]->sobreprecio = number_format((float)$contratos[0]->sobreprecio, 2, '.', ',');
        $contratos[0]->avaluo = number_format((float)$contratos[0]->avaluo, 2, '.', ',');
        $contratos[0]->costo_paquete = number_format((float)$contratos[0]->costo_paquete, 2, '.', ',');
        $contratos[0]->prima_unica = number_format((float)$contratos[0]->prima_unica, 2, '.', ',');
        $contratos[0]->descuento_promocion = number_format((float)$contratos[0]->descuento_promocion, 2, '.', ',');
        $contratos[0]->escrituras = number_format((float)$contratos[0]->escrituras, 2, '.', ',');
        $contratos[0]->precio_venta = number_format((float)$contratos[0]->precio_venta, 2, '.', ',');
        $contratos[0]->credito_neto = number_format((float)$contratos[0]->credito_neto, 2, '.', ',');
        $contratos[0]->monto_total_credito = number_format((float)$contratos[0]->monto_total_credito, 2, '.', ',');
        $contratos[0]->total_pagar = number_format((float)$contratos[0]->total_pagar, 2, '.', ',');
        $contratos[0]->avaluo_cliente = number_format((float)$contratos[0]->avaluo_cliente, 2, '.', ',');
        $contratos[0]->enganche_total = number_format((float)$contratos[0]->enganche_total, 2, '.', ',');
        $contratos[0]->infonavit = number_format((float)$contratos[0]->infonavit, 2, '.', ',');
        $contratos[0]->fovisste = number_format((float)$contratos[0]->fovisste, 2, '.', ',');

        if($contratos[0]->terreno_excedente <= 0){
            $contratos[0]->terreno_excedente = 0;
        }

        $pagos = Pago_contrato::select('monto_pago', 'num_pago', 'fecha_pago')->where('contrato_id', '=', $id)->orderBy('fecha_pago', 'asc')->get();
        for ($i = 0; $i < count($pagos); $i++) {
            $pagos[$i]->monto_pago = number_format((float)$pagos[$i]->monto_pago, 2, '.', ',');
            $fecha_pago = new Carbon($pagos[$i]->fecha_pago);
            $pagos[$i]->fecha_pago = $fecha_pago->formatLocalized('%d-%m-%Y');
        }



        $pdf = \PDF::loadview('pdf.contratos.contratoCompraVenta', ['contratos' => $contratos, 'pagos' => $pagos]);
        return $pdf->stream('ContratoCompraVenta.pdf');
        //  return ['contratos' => $contratos];
    }

    public function pagareContratopdf(Request $request, $id)
    {

        $cliente = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->select(
                'personal.nombre',
                'personal.apellidos',
                'personal.telefono',
                'personal.celular',
                'personal.email',
                'personal.direccion',
                'personal.cp',
                'personal.colonia',
                'clientes.estado',
                'clientes.ciudad',
                'contratos.fecha'
            )
            ->where('contratos.id', '=', $id)->get();

        $pagos = Pago_contrato::select('monto_pago', 'num_pago', 'fecha_pago')->where('contrato_id', '=', $id)->orderBy('fecha_pago', 'asc')->get();

        setlocale(LC_TIME, 'es_MX.utf8');



        for ($i = 0; $i < count($pagos); $i++) {
            $pagos[$i]->monto_pago1 = number_format((float)$pagos[$i]->monto_pago, 2, '.', ',');
            $tiempo = new Carbon($pagos[$i]->fecha_pago);
            $pagos[$i]->fecha_pago = $tiempo->formatLocalized('%d de %B de %Y');
            $pagos[$i]->montoPagoLetra = NumerosEnLetras::convertir($pagos[$i]->monto_pago, 'Pesos', true, 'Centavos');
        }


        $tiempo = new Carbon($cliente[0]->fecha);
        $cliente[0]->fecha = $tiempo->formatLocalized('%d de %B de %Y');

        $pdf = \PDF::loadview('pdf.contratos.pagaresContratos', ['pagos' => $pagos, 'cliente' => $cliente]);
        return $pdf->stream('pagare.pdf');
        // return ['cabecera' => $cabecera];
    }

    public function contratoConReservaDeDominio(Request $request, $id)
    {

        $contratosDom = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.superficie',
                'inst_seleccionadas.id as inst_credito',
                'inst_seleccionadas.tipo_credito',
                'creditos.fraccionamiento as proyecto',
                'lotes.construccion',
                'lotes.regimen_condom',
                'creditos.precio_venta',

                'personal.nombre',
                'personal.apellidos',
                'personal.telefono',
                'personal.celular',
                'personal.direccion',
                'personal.cp',
                'personal.colonia',
                'creditos.fraccionamiento',
                'clientes.id as prospecto_id',
                'clientes.edo_civil',
                'clientes.nss',
                'clientes.curp',
                'clientes.empresa',
                'clientes.coacreditado',
                'clientes.nombre_coa',
                'clientes.apellidos_coa',
                'clientes.f_nacimiento_coa',
                'clientes.nacionalidad_coa',
                'clientes.estado',
                'clientes.ciudad',

                'contratos.enganche_total',
                'contratos.fecha',
                'contratos.avaluo_cliente',
                'contratos.total_pagar'
            )
            ->where('inst_seleccionadas.elegido', '=', '1')
            ->where('contratos.id', '=', $id)
            ->where('inst_seleccionadas.tipo_credito', '=', 'Crédito Directo')
            ->get();

        setlocale(LC_TIME, 'es_MX.utf8');
        $contratosDom[0]->engacheTotalLetra = NumerosEnLetras::convertir($contratosDom[0]->enganche_total, 'Pesos', true, 'Centavos');
        $contratosDom[0]->enganche_total = number_format((float)$contratosDom[0]->enganche_total, 2, '.', ',');

        $fechaContrato = new Carbon($contratosDom[0]->fecha);
        $contratosDom[0]->fecha = $fechaContrato->formatLocalized('%d días de %B de %Y');

        $pagos = Pago_contrato::select('monto_pago', 'num_pago', 'fecha_pago')
        ->where('tipo_pagare', '=', 0)->where('contrato_id', '=', $id)->orderBy('fecha_pago', 'asc')->get();


        $totalDePagos = count($pagos);
        $pagos[0]->totalDePagos = NumerosEnLetras::convertir($totalDePagos, false, false, false);

            $posMayor=0;
        for ($i = 0; $i < count($pagos); $i++) {
            if($contratosDom[0]->avaluo_cliente < $pagos[$i]->monto_pago)
            {
                $posMayor = $i;
            }
        }

        if($posMayor != 0)
            $pagos[$posMayor]->monto_pago =  $pagos[$posMayor]->monto_pago - $contratosDom[0]->avaluo_cliente;
        else{
            $pagos[0]->monto_pago =  $pagos[0]->monto_pago - $contratosDom[0]->avaluo_cliente;
            if($pagos[0]->monto_pago<0)
                $pagos[0]->monto_pago = 0;
        }
            


        for ($i = 0; $i < count($pagos); $i++) {
            $tiempo = new Carbon($pagos[$i]->fecha_pago);
            $pagos[$i]->fecha_pago = $tiempo->formatLocalized('%d de %B de %Y');
            $pagos[$i]->montoPagoLetra = NumerosEnLetras::convertir($pagos[$i]->monto_pago, 'Pesos', true, 'Centavos');
            $pagos[$i]->monto_pago = number_format((float)$pagos[$i]->monto_pago, 2, '.', ',');

            switch ($i) {
                case (0): {
                        $pagos[$i]->numeros = 'primero';
                        break;
                    }
                case (1): {
                        $pagos[$i]->numeros = 'segundo';
                        break;
                    }
                case (2): {
                        $pagos[$i]->numeros = 'tercero';
                        break;
                    }
                case (3): {
                        $pagos[$i]->numeros = 'cuarto';
                        break;
                    }
                case (4): {
                        $pagos[$i]->numeros = 'quinto';
                        break;
                    }
                case (5): {
                        $pagos[$i]->numeros = 'sexto';
                        break;
                    }
                case (6): {
                        $pagos[$i]->numeros = 'septimo';
                        break;
                    }
                case (7): {
                        $pagos[$i]->numeros = 'octavo';
                        break;
                    }
                case (8): {
                        $pagos[$i]->numeros = 'noveno';
                        break;
                    }
                case (9): {
                        $pagos[$i]->numeros = 'decimo';
                        break;
                    }
                case (10): {
                        $pagos[$i]->numeros = 'onceavo';
                        break;
                    }
            }
        }


        $pdf = \PDF::loadview('pdf.contratos.contratoConReservaDeDominio', ['contratosDom' => $contratosDom, 'pagos' => $pagos]);
        return $pdf->stream('contrato_reserva_de_dominio.pdf');
    }

    public function contratoDePromesaCredito(Request $request, $id)
    {

        $contratoPromesa = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.superficie',
                'inst_seleccionadas.id as inst_credito',
                'inst_seleccionadas.tipo_credito',
                'creditos.precio_venta',
                'inst_seleccionadas.institucion',
                'creditos.fraccionamiento as proyecto',
                'lotes.construccion',
                'lotes.regimen_condom',

                'personal.nombre',
                'personal.apellidos',
                'personal.telefono',
                'personal.celular',
                'personal.direccion',
                'personal.cp',
                'personal.colonia',
                'creditos.fraccionamiento',
                'clientes.id as prospecto_id',
                'clientes.edo_civil',
                'clientes.nss',
                'clientes.curp',
                'clientes.empresa',
                'clientes.coacreditado',
                'clientes.nombre_coa',
                'clientes.apellidos_coa',
                'clientes.f_nacimiento_coa',
                'clientes.nacionalidad_coa',
                'clientes.estado',
                'clientes.ciudad',

                'contratos.monto_total_credito',
                'contratos.enganche_total',
                'contratos.fecha',
                'contratos.infonavit',
                'contratos.fovisste',
                'contratos.avaluo_cliente',
                'contratos.credito_neto',
                'contratos.total_pagar'
            )
            ->where('inst_seleccionadas.elegido', '=', '1')
            ->where('contratos.id', '=', $id)
            ->where('inst_seleccionadas.tipo_credito', '!=', 'Crédito Directo')
            ->get();

            // if($contratoPromesa[0]->avaluo_cliente>0){
            //     $contratoPromesa[0]->credito_neto = $contratoPromesa[0]->credito_neto - $contratoPromesa[0]->avaluo_cliente;
            // }

        if($contratoPromesa[0]->enganche_total == 0){
            $contratoPromesa[0]->credito_neto = $contratoPromesa[0]->credito_neto - $contratoPromesa[0]->avaluo_cliente;
        }

        setlocale(LC_TIME, 'es_MX.utf8');
        if($contratoPromesa[0]->avaluo_cliente>=0){
            $contratoPromesa[0]->engancheTotalLetra = NumerosEnLetras::convertir(($contratoPromesa[0]->enganche_total - $contratoPromesa[0]->avaluo_cliente), 'Pesos', true, 'Centavos');
        }
        else{
            $contratoPromesa[0]->engancheTotalLetra = NumerosEnLetras::convertir($contratoPromesa[0]->avaluo_cliente, 'Pesos', true, 'Centavos');
        }
        
        $contratoPromesa[0]->precioVentaLetra = NumerosEnLetras::convertir($contratoPromesa[0]->precio_venta, 'Pesos', true, 'Centavos');
        //$contratoPromesa[0]->precio_venta = number_format((float)$contratoPromesa[0]->precio_venta, 2, '.', ',');

        // if($contratoPromesa[0]->total_pagar <0)
        //     $contratoPromesa[0]->credito_neto=$contratoPromesa[0]->credito_neto - $contratoPromesa[0]->total_pagar;

        
        $contratoPromesa[0]->montoTotalCreditoLetra = NumerosEnLetras::convertir($contratoPromesa[0]->credito_neto, 'Pesos', true, 'Centavos');
        //$contratoPromesa[0]->credito_neto = number_format((float)$contratoPromesa[0]->credito_neto, 2, '.', ',');

        $contratoPromesa[0]->infonavitLetra = NumerosEnLetras::convertir($contratoPromesa[0]->infonavit, 'Pesos', true, 'Centavos');
        $contratoPromesa[0]->infonavit = number_format((float)$contratoPromesa[0]->infonavit, 2, '.', ',');

        $contratoPromesa[0]->fovissteLetra = NumerosEnLetras::convertir($contratoPromesa[0]->fovisste, 'Pesos', true, 'Centavos');
        $contratoPromesa[0]->fovisste = number_format((float)$contratoPromesa[0]->fovisste, 2, '.', ',');

        $fechaContrato = new Carbon($contratoPromesa[0]->fecha);
        $contratoPromesa[0]->fecha = $fechaContrato->formatLocalized('%d días de %B de %Y');

        $pagos = Pago_contrato::select('monto_pago', 'num_pago', 'fecha_pago')
        ->where('tipo_pagare', '=', 0)->where('contrato_id', '=', $id)->orderBy('fecha_pago', 'asc')->get();


        if($contratoPromesa[0]->institucion == 'Gamu' || $contratoPromesa[0]->institucion == 'Crea Más'){
            $contratoPromesa[0]->institucion = 'INFONAVIT';
        }
        
        $totalDePagos = count($pagos);
        $pagos[0]->totalDePagos = NumerosEnLetras::convertir($totalDePagos, false, false, false);

        $posMayor=0;
        for ($i = 0; $i < count($pagos); $i++) {
            if($contratoPromesa[0]->avaluo_cliente < $pagos[$i]->monto_pago)
            {
                $posMayor = $i;
            }
        }

        if($posMayor != 0)
            $pagos[$posMayor]->monto_pago =  $pagos[$posMayor]->monto_pago - $contratoPromesa[0]->avaluo_cliente;
        else{
            $pagos[0]->monto_pago =  $pagos[0]->monto_pago - $contratoPromesa[0]->avaluo_cliente;
            if($pagos[0]->monto_pago<0)
                $pagos[0]->monto_pago = 0;
        }

        for ($i = 0; $i < count($pagos); $i++) {
            $tiempo = new Carbon($pagos[$i]->fecha_pago);
            $pagos[$i]->fecha_pago = $tiempo->formatLocalized('%d de %B de %Y');
            $pagos[$i]->montoPagoLetra = NumerosEnLetras::convertir($pagos[$i]->monto_pago, 'Pesos', true, 'Centavos');
            $pagos[$i]->monto_pago = number_format((float)$pagos[$i]->monto_pago, 2, '.', ',');

            switch ($i) {
                case (0): {
                        $pagos[$i]->numeros = 'primero';
                        break;
                    }
                case (1): {
                        $pagos[$i]->numeros = 'segundo';
                        break;
                    }
                case (2): {
                        $pagos[$i]->numeros = 'tercero';
                        break;
                    }
                case (3): {
                        $pagos[$i]->numeros = 'cuarto';
                        break;
                    }
                case (4): {
                        $pagos[$i]->numeros = 'quinto';
                        break;
                    }
                case (5): {
                        $pagos[$i]->numeros = 'sexto';
                        break;
                    }
                case (6): {
                        $pagos[$i]->numeros = 'septimo';
                        break;
                    }
                case (7): {
                        $pagos[$i]->numeros = 'octavo';
                        break;
                    }
                case (8): {
                        $pagos[$i]->numeros = 'noveno';
                        break;
                    }
                case (9): {
                        $pagos[$i]->numeros = 'decimo';
                        break;
                    }
                case (10): {
                        $pagos[$i]->numeros = 'onceavo';
                        break;
                    }
            }
        }


        $pdf = \PDF::loadview('pdf.contratos.contratoDePromesaCredito', ['contratoPromesa' => $contratoPromesa, 'pagos' => $pagos]);
        return $pdf->stream('contrato_promesa_credito.pdf');
    }

    public function statusContrato(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        // try{
        //     DB::beginTransaction();
            $id_lote = $request->lote_id;
            $equipo='';

            $equipamientosCost = Solic_equipamiento::join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                ->select(DB::raw("SUM(solic_equipamientos.costo) as sumCosto"))
                ->where('lotes.id','=',$id_lote)
                ->where('solic_equipamientos.fin_instalacion','!=',NULL)
                ->where('solic_equipamientos.contrato_id','=',$request->id)->get();
            
            $equipamientosInst = Solic_equipamiento::join('lotes','solic_equipamientos.lote_id','=','lotes.id')
                ->join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                ->select('equipamientos.equipamiento','solic_equipamientos.id')
                ->where('lotes.id','=',$id_lote)
                ->where('solic_equipamientos.fin_instalacion','!=',NULL)
                ->where('solic_equipamientos.contrato_id','=',$request->id)->get();

            $equipamientosAntc = Solic_equipamiento::select('id')
                ->where('solic_equipamientos.lote_id','=',$id_lote)
                ->where('solic_equipamientos.fin_instalacion','=',NULL)
                ->where('solic_equipamientos.fecha_anticipo','!=',NULL)
                ->where('solic_equipamientos.contrato_id','=',$request->id)->get();
            
            $equipamientosCancel = Solic_equipamiento::select('id')
                ->where('solic_equipamientos.lote_id','=',$id_lote)
                ->where('solic_equipamientos.fin_instalacion','=',NULL)
                ->where('solic_equipamientos.fecha_anticipo','=',NULL)
                ->where('solic_equipamientos.contrato_id','=',$request->id)->get();

            if(sizeof($equipamientosAntc) != 0){
                foreach ($equipamientosAntc as $antc){
                    $antc_equip = Solic_equipamiento::findOrFail($antc->id);
                    $antc_equip->control = 1;
                    $antc_equip->save();
                }
            }

            if(sizeof($equipamientosInst) != 0){
                foreach ($equipamientosInst as $inst){
                    $equipo = $inst->equipamiento.', '.$equipo;
                    $cancel_equip = Solic_equipamiento::findOrFail($inst->id);
                    $cancel_equip->control = 3;
                    $cancel_equip->save();
                }
            }

            if(sizeof($equipamientosCancel) != 0){
                foreach ($equipamientosCancel as $canc){
                    $cancel_equip = Solic_equipamiento::findOrFail($canc->id);
                    $cancel_equip->control = 2;
                    $cancel_equip->status = 5;
                    $cancel_equip->save();
                }
            }

            if(sizeof($equipamientosCost)){
                if($equipamientosCost[0]->sumCosto != 0)
                    $ajuste = $equipamientosCost[0]->sumCosto;
                else
                    $ajuste = 0;
            }
           

            if ($request->fecha_status == '') {
                $fecha = Carbon::now();
                $status = Contrato::findOrFail($request->id);
                $status->status = $request->status;
                $status->fecha_status = $fecha;
                $status->motivo_cancel = $request->motivo_cancel;
                $status->save();

                if ($request->status == 1) {
                    $contrato = Lote::findOrFail($id_lote);
                    $contrato->contrato = 1;
                    $contrato->save();
                }

                if ($request->status == 0 || $request->status == 2) {
                    $contrato = Lote::findOrFail($id_lote);
                    $contrato->contrato = 0;
                    $contrato->apartado = 0;
                    $contrato->ajuste += $ajuste;

                    if($ajuste != 0)
                        $contrato->comentarios ='Lote con equipamiento: '.$equipo.'. '.$contrato->comentarios;

                    $apartado = Apartado::select('id')->where('lote_id','=',$id_lote)->get();
                    foreach($apartado as $ap){
                        $borrarApartado = Apartado::findOrFail($ap->id);
                        $borrarApartado->delete();
                    }

                    $precio_etapa = Precio_etapa::select('id','precio_excedente')
                    ->where('fraccionamiento_id','=',$contrato->fraccionamiento_id)
                    ->where('etapa_id','=',$contrato->etapa_id)->get();

                    $precio_modelo = Precio_modelo::select('precio_modelo')->where('precio_etapa_id','=',$precio_etapa[0]->id)
                                    ->where('modelo_id','=',$contrato->modelo_id)->get();

                    $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                    ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
                    ->where('sobreprecios_modelos.lote_id','=',$id_lote)->get();

                    $modelo = Modelo::select('terreno')->where('id','=',$contrato->modelo_id)->get();
                    $terrenoExcedente = round(($contrato->terreno - $modelo[0]->terreno),2);
                    if((double)$terrenoExcedente > 0)
                        $contrato->excedente_terreno = round(($terrenoExcedente * $precio_etapa[0]->precio_excedente), 2);
                    else {
                        $contrato->excedente_terreno = 0;
                    }

                    $contrato->precio_base = round(($precio_modelo[0]->precio_modelo), 2);
                    $precio_venta = round(($sobreprecios[0]->sobreprecios + $contrato->precio_base + $contrato->excedente_terreno + $contrato->obra_extra),2);
                    $terreno_tam_excedente = round(($contrato->terreno - $modelo[0]->terreno),2);
                    $contrato->save();
                }
            } else {
                $status = Contrato::findOrFail($request->id);
                $status->status = $request->status;
                $status->fecha_status = $request->fecha_status;
                $status->motivo_cancel = $request->motivo_cancel;
                $status->save();
                
                if ($request->status == 0 || $request->status == 2) {
                    $contrato = Lote::findOrFail($id_lote);
                    $contrato->contrato = 0;
                    $contrato->apartado = 0;
                    $contrato->ajuste += $ajuste;
                    if($ajuste != 0)
                        $contrato->comentarios ='Lote con equipamiento: '.$equipo.'. '.$contrato->comentarios;

                    $apartado = Apartado::select('id')->where('lote_id','=',$id_lote)->get();
                    foreach($apartado as $ap){
                        $borrarApartado = Apartado::findOrFail($ap->id);
                        $borrarApartado->delete();
                    }

                    $precio_etapa = Precio_etapa::select('id','precio_excedente')
                    ->where('fraccionamiento_id','=',$contrato->fraccionamiento_id)
                    ->where('etapa_id','=',$contrato->etapa_id)->get();

                    $precio_modelo = Precio_modelo::select('precio_modelo')->where('precio_etapa_id','=',$precio_etapa[0]->id)
                                    ->where('modelo_id','=',$contrato->modelo_id)->get();

                    $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
                    ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
                    ->where('sobreprecios_modelos.lote_id','=',$id_lote)->get();

                    $modelo = Modelo::select('terreno')->where('id','=',$contrato->modelo_id)->get();
                    $terrenoExcedente = round(($contrato->terreno - $modelo[0]->terreno),2);
                    if((double)$terrenoExcedente > 0)
                        $contrato->excedente_terreno = round(($terrenoExcedente * $precio_etapa[0]->precio_excedente), 2);
                    else {
                        $contrato->excedente_terreno = 0;
                    }

                    $contrato->precio_base = round(($precio_modelo[0]->precio_modelo), 2);
                    $precio_venta = round(($sobreprecios[0]->sobreprecios + $contrato->precio_base + $contrato->excedente_terreno + $contrato->obra_extra),2);
                    $terreno_tam_excedente = round(($contrato->terreno - $modelo[0]->terreno),2);
                    $contrato->save();

                    $credito = Credito::select('prospecto_id')
                        ->where('id', '=', $request->id)
                        ->get();
                    $cliente = Cliente::findOrFail($credito[0]->prospecto_id);
                    $cliente->clasificacion = 6;
                    $cliente->save();
                }
                if ($request->status == 3) {
                    $credito = Credito::select('prospecto_id', 'descripcion_paquete', 'num_lote', 'fraccionamiento', 'etapa')
                        ->where('id', '=', $request->id)
                        ->get();
                    $paquete = Lote::findOrFail($id_lote);
                    $paquete->paquete = $credito[0]->descripcion_paquete;
                    $paquete->contrato = 1;
                    $paquete->save();
                    $cliente = Cliente::findOrFail($credito[0]->prospecto_id);
                    $cliente->clasificacion = 5;
                    $vendedorid = $cliente->vendedor_id;
                    $cliente->save();

                    $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', $vendedorid)->get();
                    $fecha = Carbon::now();
                    $msj = "Se ha vendido el lote " . $credito[0]->num_lote . " del proyecto " . $credito[0]->fraccionamiento . " etapa " . $credito[0]->etapa;
                    $arregloAceptado = [
                        'notificacion' => [
                            'usuario' => $imagenUsuario[0]->usuario,
                            'foto' => $imagenUsuario[0]->foto_user,
                            'fecha' => $fecha,
                            'msj' => $msj,
                            'titulo' => 'Venta :)'
                        ]
                    ];

                    $personal = Personal::join('users', 'personal.id', '=', 'users.id')->select('personal.email', 'personal.id')->where('users.id', '=', $vendedorid)->get();

                    foreach ($personal as $personas) {
                        //$correo = $personas->email;
                        //Mail::to($correo)->send(new NotificationReceived($msj));
                        User::findOrFail($personas->id)->notify(new NotifyAdmin($arregloAceptado));
                    }
                }
            }
        // } catch (Exception $e){
        //     DB::rollBack();
        // }
    }

    public function agregarPago(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $pago = new Pago_contrato();
        $pago->contrato_id = $request->contrato_id;
        $pago->num_pago = $request->num_pago;
        $pago->monto_pago = $request->monto_pago;
        $pago->fecha_pago = $request->fecha_pago;
        $pago->restante = $request->monto_pago;
        $pago->save();
    }

    public function actualizarPago(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $pago = Pago_contrato::findOrFail($request->id);
        $pago->monto_pago = $request->monto;
        $pago->fecha_pago = $request->fecha;
        $pago->save();
    }

    public function eliminarPago(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $pago = Pago_contrato::findOrFail($request->id);
        $pago->delete();
    }

    public function actualizarContrato(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        //datos del cliente que se guardan en la tabla personal
        try {
            DB::beginTransaction();
            $personal = Personal::findOrFail($request->prospecto_id);
                $personal->apellidos = $request->apellidos;
                $personal->nombre = $request->nombre;
                $personal->f_nacimiento = $request->f_nacimiento;
                $personal->rfc = $request->rfc;
                $personal->homoclave = $request->homoclave;
                $personal->direccion = $request->direccion;
                $personal->cp = $request->cp;
                $personal->colonia = $request->colonia;
                $personal->telefono = $request->telefono;
                $personal->celular = $request->celular;
                $personal->email = $request->email;

            $cliente = Cliente::findOrFail($request->prospecto_id);
                $cliente->sexo = $request->sexo;
                $cliente->email_institucional = $request->email_institucional;
                $cliente->edo_civil = $request->edo_civil;
                $cliente->nss = $request->nss;
                $cliente->curp = $request->curp;
                $cliente->empresa = $request->empresa;
                $cliente->coacreditado = $request->coacreditado;
                $cliente->ciudad = $request->ciudad;
                $cliente->estado = $request->estado;
                $cliente->nacionalidad = $request->nacionalidad;
                $cliente->puesto = $request->puesto;
                $cliente->sexo_coa = $request->sexo_coa;
                $cliente->direccion_coa = $request->direccion_coa;
                $cliente->email_institucional_coa = $request->email_institucional_coa;
                $cliente->edo_civil_coa = $request->edo_civil_coa;
                $cliente->nss_coa = $request->nss_coa;
                $cliente->curp_coa = $request->curp_coa;
                $cliente->nombre_coa = $request->nombre_coa;
                $cliente->apellidos_coa = $request->apellidos_coa;
                $cliente->f_nacimiento_coa = $request->f_nacimiento_coa;
                $cliente->colonia_coa = $request->colonia_coa;
                $cliente->cp_coa = $request->cp_coa;
                $cliente->rfc_coa = $request->rfc_coa;
                $cliente->homoclave_coa = $request->homoclave_coa;
                $cliente->ciudad_coa = $request->ciudad_coa;
                $cliente->estado_coa = $request->estado_coa;
                $cliente->empresa_coa = $request->empresa_coa;
                $cliente->nacionalidad_coa = $request->nacionalidad_coa;
                $cliente->telefono_coa = $request->telefono_coa;
                $cliente->celular_coa = $request->celular_coa;
                $cliente->email_coa = $request->email_coa;
                $cliente->parentesco_coa = $request->parentesco_coa;
                $cliente->lugar_nacimiento = $request->lugar_nacimiento;

                $cliente->publicidad_id = $request->publicidad_id;
                $cliente->nombre_recomendado = $request->nombre_recomendado;

            $credito = Credito::findOrFail($request->contrato_id);
                $credito->num_dep_economicos =  $request->num_dep_economicos;
                $credito->nombre_primera_ref = $request->nombre_primera_ref;
                $credito->telefono_primera_ref = $request->telefono_primera_ref;
                $credito->celular_primera_ref = $request->celular_primera_ref;
                $credito->nombre_segunda_ref = $request->nombre_segunda_ref;
                $credito->telefono_segunda_ref = $request->telefono_segunda_ref;
                $credito->celular_segunda_ref = $request->celular_segunda_ref;
                $credito->paquete =  $request->paquete;
                $credito->descripcion_paquete = $request->descripcion_paquete;
                $credito->costo_paquete = $request->costo_paquete;
                $credito->precio_venta = $request->precio_venta;
                $credito->credito_solic = $request->credito_solic;
                $credito->contrato = 1;

            $inst_sel = inst_seleccionada::select('id')
                ->where('credito_id','=',$request->contrato_id)
                ->where('elegido','=',1)->get();

            $credito_sol = inst_seleccionada::findOrFail($inst_sel[0]->id);
                $credito_sol->monto_credito = $request->credito_solic;
                $credito_sol->tipo_credito = $request->tipo_credito;
                $credito_sol->institucion = $request->institucion;
                $credito_sol->save();

            $lote = Lote::findOrFail($request->lote_id);
            $lote->contrato = 1;

            $contrato = Contrato::findOrFail($request->contrato_id);
                $contrato->infonavit = $request->infonavit;
                $contrato->fovisste = $request->fovisste;
                $contrato->comision_apertura = $request->comision_apertura;
                $contrato->investigacion = $request->investigacion;
                $contrato->avaluo = $request->avaluo;
                $contrato->avaluo_cliente = $request->avaluo_cliente;
                $contrato->prima_unica = $request->prima_unica;
                $contrato->escrituras = $request->escrituras;
                $contrato->credito_neto = $request->credito_neto;
                $contrato->monto_total_credito = $request->monto_total_credito;
                $contrato->total_pagar = $request->total_pagar;
                $contrato->enganche_total = $request->enganche_total;
                $contrato->fecha = $request->fecha;
                $contrato->direccion_empresa = $request->direccion_empresa;
                $contrato->cp_empresa = $request->cp_empresa;
                $contrato->colonia_empresa = $request->colonia_empresa;
                $contrato->estado_empresa = $request->estado_empresa;
                $contrato->ciudad_empresa = $request->ciudad_empresa;
                $contrato->telefono_empresa = $request->telefono_empresa;
                $contrato->ext_empresa = $request->ext_empresa;
                $contrato->direccion_empresa_coa = $request->direccion_empresa_coa;
                $contrato->cp_empresa_coa = $request->cp_empresa_coa;
                $contrato->colonia_empresa_coa = $request->colonia_empresa_coa;
                $contrato->estado_empresa_coa = $request->estado_empresa_coa;
                $contrato->ciudad_empresa_coa = $request->ciudad_empresa_coa;
                $contrato->telefono_empresa_coa = $request->telefono_empresa_coa;
                $contrato->ext_empresa_coa = $request->ext_empresa_coa;
                $contrato->observacion = $request->observacion;
                $contrato->publicidad_id = $request->publicidad_id;

            $sumaIntereses = Expediente::select(DB::raw("SUM(interes_ord) as suma"))->where('id','=',$request->contrato_id)->get();
                if($sumaIntereses[0]->suma == NULL){
                    $sumaIntereses[0]->suma = 0;
                }

            $sumaGastos = Gasto_admin::select(DB::raw("SUM(costo) as suma"))->where('contrato_id','=',$request->contrato_id)->get();
                if($sumaGastos[0]->suma == NULL){
                    $sumaGastos[0]->suma = 0;
                }

            $sumaDeposito = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                ->select(DB::raw("SUM(depositos.cant_depo) as suma"))->where('contratos.id','=',$request->contrato_id)->get();
                if($sumaDeposito[0]->suma == NULL){
                    $sumaDeposito[0]->suma = 0;
                }

                $sumaTotal =  $sumaIntereses[0]->suma + $sumaGastos[0]->suma - $sumaDeposito[0]->suma;

            $contrato->saldo = $credito->precio_venta + $sumaTotal;

            $lote->save();
            $credito->save();
            $personal->save();
            $cliente->save();
            $contrato->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function reasignarCliente(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try {
            $loteNuevo_id = $request->sel_lote;

            $lote_ant = Lote::findOrFail($request->lote_id);
            $varContrato = $lote_ant->contrato;
            $lote_ant->contrato = 0;
            $lote_ant->paquete = '';
            $lote_ant->save();
            DB::beginTransaction();

            $lote_new = Lote::findOrFail($loteNuevo_id);

            /////////////////////////////////////////////////////////////////
            $precio_etapa = Precio_etapa::select('id','precio_excedente')
                            ->where('fraccionamiento_id','=',$lote_new->fraccionamiento_id)
                            ->where('etapa_id','=',$lote_new->etapa_id)->get();
            
            $precio_modelo = Precio_modelo::select('precio_modelo')->where('precio_etapa_id','=',$precio_etapa[0]->id)
                            ->where('modelo_id','=',$lote_new->modelo_id)->get();
            
            $sobreprecios = Sobreprecio_modelo::join('sobreprecios_etapas','sobreprecios_modelos.sobreprecio_etapa_id','=','sobreprecios_etapas.id')
            ->select(DB::raw("SUM(sobreprecios_etapas.sobreprecio) as sobreprecios"))
            ->where('sobreprecios_modelos.lote_id','=',$loteNuevo_id)->get();
            
            $modelo = Modelo::select('terreno')->where('id','=',$lote_new->modelo_id)->get();
            $terrenoExcedente = round(($lote_new->terreno - $modelo[0]->terreno),2);
                if((double)$terrenoExcedente > 0)
                    $lote_new->excedente_terreno = round(($terrenoExcedente * $precio_etapa[0]->precio_excedente), 2);
                else {
                    $lote_new->excedente_terreno = 0;
                }
            $lote_new->precio_base = $precio_modelo[0]->precio_modelo;
            $lote_new->precio_base = round(($lote_new->precio_base), 2);
            $precio_venta = round(($sobreprecios[0]->sobreprecios + $lote_new->precio_base  + $lote_new->ajuste + $lote_new->excedente_terreno + $lote_new->obra_extra),2);
            $terreno_tam_excedente = round(($lote_new->terreno - $modelo[0]->terreno),2);
            $lote_new->contrato = $varContrato;

            ////////////////////////////////////////////////////////////////////////////////////////
            

            $credito = Credito::findOrFail($request->id);
            $credito->fraccionamiento = $request->fraccionamiento;
            $credito->etapa = $request->etapa;
            $credito->manzana = $request->manzana;
            $credito->num_lote = $request->num_lote;
            $credito->modelo = $request->modelo;
            $credito->precio_base = $lote_new->precio_base + $lote_new->ajuste;
            $credito->superficie = $request->superficie;
            $credito->terreno_excedente = $terreno_tam_excedente;
            $credito->precio_terreno_excedente = $lote_new->excedente_terreno;
            $credito->precio_obra_extra = $request->precio_obra_extra;
            $credito->promocion = $request->promocion;
            $credito->descripcion_promocion = $request->descripcion_promocion;
            $credito->descuento_promocion = $request->descuento_promocion;
            $credito->paquete = '';
            $credito->descripcion_paquete = '';
            $credito->costo_paquete = 0;
            $credito->precio_venta = round($precio_venta - $request->descuento_promocion,2);
            $credito->lote_id = $loteNuevo_id;
            $credito->save();
            $lote_new->save();
            DB::commit();


            } catch (Exception $e) { 
                DB::rollBack();
        }
    }

    public function excelContratos (Request $request){
        $buscar = $request->buscar;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $b_status = $request->b_status;
        $f_ini = $request->f_ini;
        $f_fin = $request->f_fin;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            ->select(
                'creditos.id',
                'creditos.prospecto_id',
                'creditos.num_dep_economicos','medios_publicitarios.nombre as publicidad',
                'creditos.tipo_economia',
                'creditos.nombre_primera_ref',
                'creditos.telefono_primera_ref',
                'creditos.celular_primera_ref',
                'creditos.nombre_segunda_ref',
                'creditos.telefono_segunda_ref',
                'creditos.celular_segunda_ref',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.modelo',
                'creditos.precio_base',
                'creditos.superficie',
                'creditos.terreno_excedente',
                'creditos.precio_terreno_excedente',
                'creditos.promocion',
                'creditos.descripcion_promocion',
                'creditos.descuento_promocion',
                'creditos.paquete',
                'creditos.descripcion_paquete',
                'creditos.precio_venta',
                'creditos.plazo',
                'creditos.credito_solic',
                'creditos.costo_paquete',
                'inst_seleccionadas.tipo_credito',
                'inst_seleccionadas.id as inst_credito',
                'creditos.precio_obra_extra',
                'creditos.fraccionamiento as proyecto',
                'creditos.lote_id',

                'inst_seleccionadas.institucion',
                'personal.nombre',
                'personal.apellidos',
                'personal.telefono',
                'personal.celular',
                'personal.email',
                'personal.direccion',
                'personal.cp',
                'personal.colonia',
                'personal.f_nacimiento',
                'personal.rfc',
                'personal.homoclave',
                'creditos.fraccionamiento',
                'clientes.id as prospecto_id',
                'clientes.edo_civil',
                'clientes.nss',
                'clientes.curp',
                'clientes.empresa',
                'clientes.coacreditado',
                'clientes.estado',
                'clientes.ciudad',
                'clientes.puesto',
                'clientes.nacionalidad',
                'clientes.sexo',
                'clientes.sexo_coa',
                'clientes.email_institucional_coa',
                'clientes.empresa_coa',
                'clientes.edo_civil_coa',
                'clientes.nss_coa',
                'clientes.curp_coa',
                'clientes.nombre_coa',
                'clientes.apellidos_coa',
                'clientes.f_nacimiento_coa',
                'clientes.nacionalidad_coa',
                'clientes.rfc_coa',
                'clientes.homoclave_coa',
                'clientes.direccion_coa',
                'clientes.colonia_coa',
                'clientes.ciudad_coa',
                'clientes.estado_coa',
                'clientes.cp_coa',
                'clientes.telefono_coa',
                'clientes.ext_coa',
                'clientes.celular_coa',
                'clientes.email_coa',
                'clientes.parentesco_coa',
                'clientes.lugar_nacimiento_coa',
                'v.nombre as vendedor_nombre',
                'v.apellidos as vendedor_apellidos',

                'contratos.id as contratoId',
                'contratos.infonavit',
                'contratos.fovisste',
                'contratos.comision_apertura',
                'clientes.lugar_nacimiento',
                'contratos.investigacion',
                'contratos.avaluo',
                'contratos.prima_unica',
                'contratos.escrituras',
                'contratos.credito_neto',
                'contratos.status',
                'contratos.fecha_status',
                'contratos.avaluo_cliente',
                'contratos.fecha',
                'contratos.direccion_empresa',
                'contratos.cp_empresa',
                'contratos.colonia_empresa',
                'contratos.estado_empresa',
                'contratos.ciudad_empresa',
                'contratos.telefono_empresa',
                'contratos.ext_empresa',
                'contratos.direccion_empresa_coa',
                'contratos.cp_empresa_coa',
                'contratos.colonia_empresa_coa',
                'contratos.estado_empresa_coa',
                'contratos.ciudad_empresa_coa',
                'contratos.telefono_empresa_coa',
                'contratos.ext_empresa_coa',
                'contratos.total_pagar',
                'contratos.monto_total_credito',
                'contratos.enganche_total',
                'contratos.avance_lote',
                'contratos.observacion'
            );

        if($b_status == ''){
            if ($buscar == '') {
                $contratos = $query
                    ->where('inst_seleccionadas.elegido', '=', '1')
                    ->orderBy('id', 'desc')->get();

            } else {
                switch ($criterio) {
                    case 'personal.nombre': {
                            $contratos = $query

                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->orderBy('id', 'desc')->get();

                          
                            break;
                        }
                    case 'v.nombre': {
                            $contratos = $query

                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->orderBy('id', 'desc')->get();

                            break;
                        }
                    case 'inst_seleccionadas.tipo_credito': {
                            $contratos = $query
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->orderBy('id', 'desc')->get();
                            break;
                        }
                    case 'creditos.id': {
                            $contratos = $query

                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->orderBy('id', 'desc')->get();

                            break;
                        }
                    case 'creditos.vendedor_id': {
                        if($f_fin == '' || $f_ini == ''){
                            if($b_etapa == "" && $buscar3 == "" && $buscar != ""){
                                $contratos = $query
        
                                    ->where($criterio, '=',$buscar)
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->get();
        
                            }else{
                                if($b_etapa == "" && $buscar3 != "" && $buscar != ""){
                                    $contratos =$query
            
                                        ->where($criterio, '=',$buscar)
                                        ->where('lotes.fraccionamiento_id','=',$buscar3)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->orderBy('id', 'desc')->get();
            
                                }else{
                                    if($b_etapa != "" && $buscar3 != "" && $buscar != ""){
                                        $contratos = $query
                    
                                            ->where($criterio, '=',$buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->orderBy('id', 'desc')->get();
                
                                       
                                    }
                                }
                            }
                        }
                        else{
                            if($b_etapa == "" && $buscar3 == "" && $buscar != ""){
                                $contratos = $query
        
                                    ->where($criterio, '=',$buscar)
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                    ->orderBy('id', 'desc')->get();
        
                            }else{
                                if($b_etapa == "" && $buscar3 != "" && $buscar != ""){
                                    $contratos = $query
            
                                        ->where($criterio, '=',$buscar)
                                        ->where('lotes.fraccionamiento_id','=',$buscar3)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                        ->orderBy('id', 'desc')->get();
            
                                }else{
                                    if($b_etapa != "" && $buscar3 != "" && $buscar != ""){
                                        $contratos = $query
                
                                            ->where($criterio, '=',$buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->get();
                
                                       
                                    }
                                }
                            }
                        }
                        break;
                        }
                    case 'contratos.fecha': {
                            $contratos = $query

                                ->whereBetween($criterio, [$buscar, $buscar3])
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->orderBy('id', 'desc')->get();

                           
                            break;
                        }

                    case 'contratos.fecha_status': {
                            $contratos = $query

                                ->whereBetween($criterio, [$buscar,  $buscar3])
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->orderBy('id', 'desc')->get();
 
                            break;
                        }
                    
                    case 'creditos.fraccionamiento': {
                        if($f_ini == '' || $f_fin == ''){
                            if ($b_etapa != '' && $b_manzana != '' && $b_lote != '') {
                                $contratos = $query
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                    ->where('lotes.manzana', '=', $b_manzana)
                                    ->where('lotes.num_lote', '=', $b_lote)
                                    ->orderBy('id', 'desc')->get();

                            
                            } else {
                                if ($b_etapa != '' && $b_manzana != '') {
                                    $contratos = $query
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('lotes.manzana', '=', $b_manzana)
                                        ->orderBy('id', 'desc')->get();

                                  
                                } else {
                                    if ($b_etapa != '') {
                                        $contratos = $query
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.etapa_id', '=',  $b_etapa )
                                            ->orderBy('id', 'desc')->get();

                                      
                                    } else {
                                        if ($b_manzana != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->orderBy('id', 'desc')->get();

                                           
                                        } else {
                                            if ($b_lote != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.num_lote', '=', $b_lote)
                                                    ->orderBy('id', 'desc')->get();

                                        
                                            } else {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->orderBy('id', 'desc')->get();

                                              
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else{
                            if ($b_etapa != '' && $b_manzana != '' && $b_lote != '') {
                                $contratos = $query
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                    ->where('lotes.manzana', '=', $b_manzana)
                                    ->where('lotes.num_lote', '=', $b_lote)
                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                    ->orderBy('id', 'desc')->get();

                            
                            } else {
                                if ($b_etapa != '' && $b_manzana != '') {
                                    $contratos = $query
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('lotes.manzana', '=', $b_manzana)
                                        ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                        ->orderBy('id', 'desc')->get();

                                  
                                } else {
                                    if ($b_etapa != '') {
                                        $contratos = $query
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.etapa_id', '=',  $b_etapa )
                                            ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                            ->orderBy('id', 'desc')->get();

                                      
                                    } else {
                                        if ($b_manzana != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                ->orderBy('id', 'desc')->get();

                                           
                                        } else {
                                            if ($b_lote != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.num_lote', '=', $b_lote)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->get();

                                        
                                            } else {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->whereBetween('contratos.fecha', [$f_ini, $f_fin])
                                                    ->orderBy('id', 'desc')->get();

                                              
                                            }
                                        }
                                    }
                                }
                            }
                        }
                            
                            break;
                        }
                }
            }
        }
        else{
            if ($buscar == '' && $criterio != 'creditos.vendedor_id') {
                $contratos = $query
                    ->where('inst_seleccionadas.elegido', '=', '1')
                    ->where('contratos.status','=',$b_status)
                    ->orderBy('id', 'desc')->get();

            } else {
                switch ($criterio) {
                    case 'personal.nombre': {
                            $contratos = $query

                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('contratos.status','=',$b_status)
                                ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('id', 'desc')->get();
                           
                            break;
                        }
                    case 'v.nombre': {
                            $contratos = $query

                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('contratos.status','=',$b_status)
                                ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('id', 'desc')->get();

                            break;
                        }
                    case 'inst_seleccionadas.tipo_credito': {
                            $contratos = $query
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('id', 'desc')->get();

                            
                            break;
                        }
                    case 'creditos.id': {
                            $contratos = $query

                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('id', 'desc')->get();

                            
                            break;
                        }
                    case 'creditos.vendedor_id': {
                            if($b_etapa == "" && $buscar3 == "" && $buscar != ""){
                                $contratos = $query
        
                                    ->where($criterio, '=',$buscar)
                                    ->where('contratos.status','=',$b_status)
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->orderBy('id', 'desc')->get();
        
                            }else{
                                if($b_etapa == "" && $buscar3 != "" && $buscar != ""){
                                    $contratos = $query
            
                                        ->where($criterio, '=',$buscar)
                                        ->where('lotes.fraccionamiento_id','=',$buscar3)
                                        ->where('contratos.status','=',$b_status)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->orderBy('id', 'desc')->get();
            
                                }else{
                                    if($b_etapa != "" && $buscar3 != "" && $buscar != ""){
                                        $contratos = $query
                
                                            ->where($criterio, '=',$buscar)
                                            ->where('lotes.fraccionamiento_id','=',$buscar3)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('contratos.status','=',$b_status)
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->orderBy('id', 'desc')->get();
                
                                    }
                                }
                            }
    
                            break;
                            }
                    case 'contratos.fecha': {
                            $contratos = $query

                                ->whereBetween($criterio, [$buscar, $buscar3])
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('id', 'desc')->get();

                           
                            break;
                        }

                    case 'contratos.fecha_status': {
                            $contratos = $query

                                ->whereBetween($criterio, [$buscar,  $buscar3])
                                ->where('contratos.status','=',$b_status)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->orderBy('id', 'desc')->get();
                        }
                    
                    case 'creditos.fraccionamiento': {
                            if ($b_etapa != '' && $b_manzana != '' && $b_lote != '') {
                                $contratos = $query
                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                    ->where('contratos.status','=',$b_status)
                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                    ->where('lotes.etapa_id', '=', $b_etapa)
                                    ->where('lotes.manzana', '=', $b_manzana)
                                    ->where('lotes.num_lote', '=', $b_lote)
                                    ->orderBy('id', 'desc')->get();

                               
                            } else {
                                if ($b_etapa != '' && $b_manzana != '') {
                                    $contratos = $query
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('contratos.status','=',$b_status)
                                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                        ->where('lotes.etapa_id','=', $b_etapa)
                                        ->where('lotes.manzana', '=', $b_manzana)
                                        ->orderBy('id', 'desc')->get();

                                   
                                } else {
                                    if ($b_etapa != '') {
                                        $contratos = $query
                                            ->where('inst_seleccionadas.elegido', '=', '1')
                                            ->where('contratos.status','=',$b_status)
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.etapa_id', '=',  $b_etapa )
                                            ->orderBy('id', 'desc')->get();

                                        
                                    } else {
                                        if ($b_manzana != '') {
                                            $contratos = $query
                                                ->where('inst_seleccionadas.elegido', '=', '1')
                                                ->where('contratos.status','=',$b_status)
                                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                ->where('lotes.manzana', '=', $b_manzana)
                                                ->orderBy('id', 'desc')->get();

                                           
                                        } else {
                                            if ($b_lote != '') {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->where('lotes.num_lote', '=', $b_lote)
                                                    ->orderBy('id', 'desc')->get();

                                              
                                            } else {
                                                $contratos = $query
                                                    ->where('inst_seleccionadas.elegido', '=', '1')
                                                    ->where('contratos.status','=',$b_status)
                                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                                    ->orderBy('id', 'desc')->get();

                                         
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        }
                }
            }

        }


        return Excel::create('contratos', function($excel) use ($contratos){
            $excel->sheet('contratos', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Contrato', 'Cliente', 'Vendedor', 'Proyecto', 'Etapa', 'Manzana',
                    '# Lote','Modelo', 'Tipo de crédito', 'Institución','Fecha del contrato', 'Precio de Venta', 'Status', 'Publicidad'
                ]);


                $sheet->cells('A1:N1', function ($cells) {
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
                    'L' => '$#,##0.00',
                ));

                foreach($contratos as $index => $contrato) {
                    $cont++;

                    switch($contrato->status){
                        case 0: {
                            $status = 'Cancelado';
                            break;
                        }
                        case 1:{
                            $status = 'Pendiente';
                            break;
                        }
                        case 2:{
                            $status = 'No firmado';
                            break;
                        }
                        case 3:{
                            $status = 'Firmado';
                            break;
                        }

                    }

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($contrato->fecha);
                    $contrato->fecha = $fecha1->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $contrato->contratoId, 
                        $contrato->nombre. ' ' . $contrato->apellidos,
                        $contrato->vendedor_nombre. ' ' .$contrato->vendedor_apellidos,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->modelo,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->fecha,
                        $contrato->precio_venta,
                        $status,
                        $contrato->publicidad,

                    ]);	
                }
                $num='A1:N' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    public function validarLoteEnContrato(Request $request){
        if(!$request->ajax())return redirect('/');
        $idLote = $request->lote_id;
        $lote = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->select('creditos.id')
                        ->where('contratos.status','=',1)
                        ->where('creditos.lote_id','=',$idLote)
                        ->orwhere('contratos.status','=',3)
                        ->where('creditos.lote_id','=',$idLote)
                        ->count();

        return ['lote' => $lote];
    }

}
