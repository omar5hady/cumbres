<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Credito;
use DB;
use Auth;
use Carbon\Carbon;
use App\Inst_seleccionada;
use App\Cliente;

class ContratoController extends Controller
{
    public function indexCreditosAprobados(Request $request){
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        if($buscar==''){
            $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                ->join('personal','creditos.prospecto_id','=','personal.id')
                ->join('clientes','creditos.prospecto_id','=','clientes.id')
                ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                ->join('personal as v','clientes.vendedor_id','v.id')
                    ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                        'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                        'creditos.status','inst_seleccionadas.tipo_credito','inst_seleccionadas.id as inst_credito',
                        'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                        'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                ->where('creditos.status','=','2')
                ->where('inst_seleccionadas.elegido','=','1')
                ->orderBy('id','desc')->paginate(8);
        }
        else{
            switch($criterio){
                case 'personal.nombre':
                {
                    $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                            ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                'creditos.status','inst_seleccionadas.tipo_credito',
                                'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                            
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','=','2')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                        ->where('creditos.status','=','2')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->paginate(8);
                    break;
                }
                case 'v.nombre':
                {
                    $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                            ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                'creditos.status','inst_seleccionadas.tipo_credito',
                                'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                    
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','=','2')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orWhere('v.apellidos', 'like', '%'. $buscar . '%')
                        ->where('creditos.status','=','2')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->paginate(8);
                    break;
                }
                case 'inst_seleccionadas.tipo_credito':
                {
                    $creditos = Credito::join('datos_extra','creditos.id','=','datos_extra.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                            ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                'creditos.status','inst_seleccionadas.tipo_credito',
                                'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','=','2')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->paginate(8);
                    break;
                }
                case 'creditos.id':
                {
                    $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','creditos.prospecto_id','=','personal.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                        ->join('personal as v','clientes.vendedor_id','v.id')
                            ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                'creditos.status','inst_seleccionadas.tipo_credito',
                                'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                'clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->where('creditos.status','=','2')
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->orderBy('id','desc')->paginate(8);
                    break;
                }
                case 'clientes.proyecto_interes_id':
                {
                    if($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                            ->join('personal','creditos.prospecto_id','=','personal.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                            ->join('personal as v','clientes.vendedor_id','v.id')
                                ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                    'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                    'creditos.status','inst_seleccionadas.tipo_credito',
                                    'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                    'clientes.proyecto_interes_id','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                            ->where('creditos.status','=','2')
                            ->where('inst_seleccionadas.elegido','=','1')
                            ->where('clientes.proyecto_interes_id', '=',  $buscar)
                            ->where('creditos.etapa','=',$b_etapa)
                            ->where('creditos.manzana', '=', $b_manzana)
                            ->where('creditos.num_lote','=',$b_lote)
                            ->orderBy('id','desc')->paginate(8);
                    
                    }
                    else{
                        if($b_etapa != '' && $b_manzana != ''){
                            $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                ->join('personal','creditos.prospecto_id','=','personal.id')
                                ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                                ->join('personal as v','clientes.vendedor_id','v.id')
                                    ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                        'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                        'creditos.status','inst_seleccionadas.tipo_credito',
                                        'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                        'clientes.proyecto_interes_id','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                ->where('creditos.status','=','2')
                                ->where('inst_seleccionadas.elegido','=','1')
                                ->where('clientes.proyecto_interes_id', '=',  $buscar)
                                ->where('creditos.etapa','like','%'.$b_etapa.'%')
                                ->where('creditos.manzana', '=', $b_manzana)
                                ->orderBy('id','desc')->paginate(8);
                        }
                        else{
                            if($b_etapa != ''){
                                $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                    ->join('personal','creditos.prospecto_id','=','personal.id')
                                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                    ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                                    ->join('personal as v','clientes.vendedor_id','v.id')
                                        ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                            'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                            'creditos.status','inst_seleccionadas.tipo_credito',
                                            'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                            'clientes.proyecto_interes_id','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                    ->where('creditos.status','=','2')
                                    ->where('inst_seleccionadas.elegido','=','1')
                                    ->where('clientes.proyecto_interes_id', '=',  $buscar)
                                    ->where('creditos.etapa','like','%'.$b_etapa.'%')
                                    ->orderBy('id','desc')->paginate(8);
                            }
                            else{
                                if($b_manzana != ''){
                                    $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                        ->join('personal','creditos.prospecto_id','=','personal.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                                        ->join('personal as v','clientes.vendedor_id','v.id')
                                            ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                                'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                                'creditos.status','inst_seleccionadas.tipo_credito',
                                                'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                                'clientes.proyecto_interes_id','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                        ->where('creditos.status','=','2')
                                        ->where('inst_seleccionadas.elegido','=','1')
                                        ->where('clientes.proyecto_interes_id', '=',  $buscar)
                                        ->where('creditos.manzana', '=', $b_manzana)
                                        ->orderBy('id','desc')->paginate(8);
                                }
                                else{
                                    if($b_lote != ''){
                                        $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                            ->join('personal','creditos.prospecto_id','=','personal.id')
                                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                                            ->join('personal as v','clientes.vendedor_id','v.id')
                                                ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                                    'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                                    'creditos.status','inst_seleccionadas.tipo_credito',
                                                    'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                                    'clientes.proyecto_interes_id','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                            ->where('creditos.status','=','2')
                                            ->where('inst_seleccionadas.elegido','=','1')
                                            ->where('clientes.proyecto_interes_id', '=',  $buscar)
                                            ->where('creditos.num_lote','=',$b_lote)
                                            ->orderBy('id','desc')->paginate(8);
                                    }
                                    else{
                                        $creditos = Credito::join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                                            ->join('personal','creditos.prospecto_id','=','personal.id')
                                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                            ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                                            ->join('personal as v','clientes.vendedor_id','v.id')
                                                ->select('creditos.id','creditos.prospecto_id','creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','creditos.precio_base',
                                                    'creditos.precio_venta','creditos.plazo','creditos.credito_solic',
                                                    'creditos.status','inst_seleccionadas.tipo_credito',
                                                    'inst_seleccionadas.institucion','personal.nombre','personal.apellidos','fraccionamientos.nombre as proyecto',
                                                    'clientes.proyecto_interes_id','clientes.id as prospecto_id','v.nombre as vendedor_nombre','v.apellidos as vendedor_apellidos')
                                            ->where('creditos.status','=','2')
                                            ->where('inst_seleccionadas.elegido','=','1')
                                            ->where('clientes.proyecto_interes_id', '=',  $buscar)
                                            ->orderBy('id','desc')->paginate(8);
                                    }
                                }
                            }
                        }
                    }
                    break;
                }
            }

        }
    
        return[
            'pagination' => [
                'total'         => $creditos->total(),
                'current_page'  => $creditos->currentPage(),
                'per_page'      => $creditos->perPage(),
                'last_page'     => $creditos->lastPage(),
                'from'          => $creditos->firstItem(),
                'to'            => $creditos->lastItem(),
            ],'creditos' => $creditos];
    }

    public function getDatosCredito(Request $request){
        $folio = $request->folio;
        $creditos = Credito::select('id','prospecto_id','num_dep_economicos','tipo_economia',
                'nombre_primera_ref','telefono_primera_ref','celular_primera_ref',
                'nombre_segunda_ref','telefono_segunda_ref','celular_segunda_ref',
                'etapa','manzana','num_lote','modelo','precio_base','precio_obra_extra',
                'superficie','terreno_excedente','precio_terreno_excedente',
                'promocion','descripcion_promocion','descuento_promocion','paquete',
                'descripcion_paquete','precio_venta','plazo','credito_solic',
                'costo_paquete','status')
                ->where('id','=',$folio)->get();
        
                foreach($creditos as $index => $credito) {
                    $prospecto=[];
                    $prospecto = Cliente::join('personal','clientes.id','=','personal.id')
                        ->join('fraccionamientos','clientes.proyecto_interes_id','=','fraccionamientos.id')
                        ->select('personal.nombre','personal.apellidos','clientes.sexo','personal.telefono','personal.celular',
                                 'personal.email','personal.direccion','personal.cp','personal.colonia','clientes.ciudad','clientes.estado',
                                 'personal.f_nacimiento','clientes.nacionalidad','clientes.curp','personal.rfc','personal.homoclave',
                                 'clientes.nss','clientes.empresa','clientes.puesto','clientes.email_institucional','clientes.tipo_casa',
                                 'clientes.edo_civil','clientes.coacreditado','clientes.nombre_coa','clientes.apellidos_coa',
                                 'clientes.f_nacimiento_coa','clientes.rfc_coa','clientes.homoclave_coa','clientes.direccion_coa',
                                 'clientes.cp_coa','clientes.colonia_coa','clientes.estado_coa','clientes.ciudad_coa','clientes.celular_coa',
                                 'clientes.telefono_coa','clientes.email_coa','clientes.email_institucional_coa',
                                 'clientes.empresa_coa','fraccionamientos.nombre as proyecto','clientes.curp_coa','clientes.nss_coa',
                                 'clientes.nacionalidad_coa')
                        ->where('clientes.id','=',$credito->prospecto_id)->get();
                    
                    $institucion=[];
                    $institucion=Inst_seleccionada::select('tipo_credito','institucion','elegido')
                        ->where('credito_id','=',$credito->id)
                        ->where('elegido','=',1)->get();
                    if(sizeof($prospecto) > 0){
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
                        $credito->proyecto = $prospecto[0]->proyecto;
                        
                        $credito->tipo_credito = $institucion[0]->tipo_credito;
                        $credito->institucion = $institucion[0]->institucion;
                        $credito->elegido = $institucion[0]->elegido;
                    }
                    
                }

        
        return['creditos' => $creditos];
    }
}
