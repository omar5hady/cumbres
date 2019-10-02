<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposito;
use App\Contrato;
use App\Pago_contrato;
use Carbon\Carbon;
use DB;
use NumerosEnLetras;
use App\Gasto_admin;
use App\Dep_credito;
use Excel;

class DepositoController extends Controller
{
    public function indexPagares(Request $request){
        if(!$request->ajax())return redirect('/');
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $vencido = $request->b_vencidos;
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio =  $request->criterio;

        if($vencido == 1){ //VENCIDOS
            if($buscar == ''){
                $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('creditos','creditos.id','=','contratos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                    ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                    ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                            'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                            'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                            'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                            'personal.telefono','personal.email','creditos.num_dep_economicos',
                            'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                            'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                            'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                            'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                            'contratos.ext_empresa','contratos.colonia_empresa',
                            DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                            DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                    ->where('pagos_contratos.pagado','!=',2)
                    ->where('pagos_contratos.pagado','!=',3)
                    ->where('contratos.status','!=',0)
                    ->where('contratos.status','!=',2) 
                    ->where('pagos_contratos.fecha_pago','<',$hoy)
                    ->orderBy('pagos_contratos.pagado', 'asc')
                    ->orderBy('pagos_contratos.fecha_pago', 'asc')
                    ->orderBy('pagos_contratos.pagado', 'asc')
                    ->paginate(10);
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.pagado','!=',3)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where('personal.nombre','like', '%'. $buscar . '%')
                            ->orWhere('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.pagado','!=',3)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where('personal.apellidos','like', '%'. $buscar . '%')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.pagado','!=',3)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->whereBetween($criterio, [$buscar,$buscar2])
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                        if($buscar2=='' && $buscar3==''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.pagado','!=',3)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where($criterio,'=',$buscar)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 == ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.pagado','!=',3)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 != ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.pagado','!=',3)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->where('creditos.manzana','=',$buscar3)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        break;
                    }
                    default:{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where('pagos_contratos.pagado','!=',2)
                            ->where('pagos_contratos.pagado','!=',3)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->where('pagos_contratos.fecha_pago','<',$hoy)
                            ->where($criterio,'=',$buscar)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                }
            }
            
        } 
        elseif($vencido == 2){ //CANCELADOS
            if($buscar == ''){
                $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('creditos','creditos.id','=','contratos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                    ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                    ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                            'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                            'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                            'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                            'personal.telefono','personal.email','creditos.num_dep_economicos',
                            'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                            'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                            'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                            'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                            'contratos.ext_empresa','contratos.colonia_empresa',
                            DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                            DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                    //
                
                    ->where('contratos.status','=',0)
                    ->orWhere('contratos.status','=',2) 
                
                    ->orderBy('pagos_contratos.pagado', 'asc')
                    ->orderBy('pagos_contratos.fecha_pago', 'asc')
                    ->orderBy('pagos_contratos.pagado', 'asc')
                    ->paginate(10);
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            //
                        
                            ->where('contratos.status','=',0)
                            ->where('personal.nombre','like', '%'. $buscar . '%')

                            ->orWhere('contratos.status','=',2)
                            ->where('personal.nombre','like', '%'. $buscar . '%')

                            ->orWhere('contratos.status','=',2)
                            ->where('personal.apellidos','like', '%'. $buscar . '%')

                            ->orWhere('contratos.status','=',0)
                            ->where('personal.apellidos','like', '%'. $buscar . '%')
                           

                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            
                            ->where('contratos.status','=',0)
                            ->whereBetween($criterio, [$buscar,$buscar2])

                            ->orWhere('contratos.status','=',2)
                            ->whereBetween($criterio, [$buscar,$buscar2])
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                        if($buscar2=='' && $buscar3==''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            
                            ->where('contratos.status','=',0)
                            ->where($criterio,'=',$buscar)
                            ->orWhere('contratos.status','=',2)
                            ->where($criterio,'=',$buscar)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 == ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            
                            ->where('contratos.status','=',0)
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->orWhere('contratos.status','=',2)
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 != ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            
                            ->where('contratos.status','=',0)
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->where('creditos.manzana','=',$buscar3)
                            ->orWhere('contratos.status','=',2)
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->where('creditos.manzana','=',$buscar3)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        break;
                    }
                    default:{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            
                            ->where('contratos.status','=',0)
                            ->where($criterio,'=',$buscar)
                            ->orWhere('contratos.status','=',2)
                            ->where($criterio,'=',$buscar)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                }
            }
            
        }
        else{ //TODOS
            if($buscar==''){
                $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('creditos','creditos.id','=','contratos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                    ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                    ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                            'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                            'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                            'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                            'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                            'personal.telefono','personal.email','creditos.num_dep_economicos',
                            'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                            'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                            'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                            'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                            'contratos.ext_empresa','contratos.colonia_empresa',
                            DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                            DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2) 
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                    ->paginate(10);
            }
            else{
                switch($criterio){
                    case 'personal.nombre':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where('personal.nombre','like', '%'. $buscar . '%')
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->orwhere('personal.apellidos','like', '%'. $buscar . '%')
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'pagos_contratos.fecha_pago':{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->whereBetween($criterio, [$buscar,$buscar2])
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2) 
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                    case 'creditos.fraccionamiento':{
                        if($buscar2=='' && $buscar3==''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where($criterio,'=',$buscar)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2) 
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 == ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2) 
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        if($buscar!='' && $buscar2 !='' && $buscar3 != ''){
                            $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where($criterio,'=',$buscar)
                            ->where('creditos.etapa','=',$buscar2)
                            ->where('creditos.manzana','=',$buscar3)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2) 
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        }
                        break;
                    }
                    default:{
                        $pagares = Pago_contrato::join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('personal','clientes.id','=','personal.id')
                            ->leftjoin('expedientes','contratos.id','=','expedientes.id')
                            ->leftjoin('personal as g','expedientes.gestor_id','=','g.id')
                            ->select('contratos.id as folio','pagos_contratos.id as pago' , 'pagos_contratos.num_pago', 'pagos_contratos.monto_pago', 
                                    'pagos_contratos.fecha_pago', 'pagos_contratos.restante', 'creditos.fraccionamiento',
                                    'creditos.etapa', 'creditos.manzana', 'creditos.num_lote','pagos_contratos.pagado',
                                    'personal.nombre','personal.apellidos','personal.f_nacimiento','personal.rfc',
                                    'personal.homoclave','personal.direccion','personal.colonia','personal.cp',
                                    'personal.telefono','personal.email','creditos.num_dep_economicos',
                                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                    'clientes.nacionalidad','clientes.sexo','personal.celular','contratos.direccion_empresa',
                                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                    'contratos.ext_empresa','contratos.colonia_empresa',
                                    DB::raw('DATEDIFF(current_date,pagos_contratos.fecha_pago) as diferencia'),
                                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) as gestor"))
                            ->where($criterio,'=',$buscar)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2) 
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->orderBy('pagos_contratos.fecha_pago', 'asc')
                            ->orderBy('pagos_contratos.pagado', 'asc')
                            ->paginate(10);
                        break;
                    }
                }
            }
        }
        
        
        return[
            'pagination' => [
            'total'         => $pagares->total(),
            'current_page'  => $pagares->currentPage(),
            'per_page'      => $pagares->perPage(),
            'last_page'     => $pagares->lastPage(),
            'from'          => $pagares->firstItem(),
            'to'            => $pagares->lastItem(),
        ],
            'pagares' => $pagares, $hoy
        ];
    }

    public function indexDepositos(Request $request){
        if(!$request->ajax())return redirect('/');
        $depositos = Deposito::select('id', 'pago_id', 'cant_depo','interes_mor','interes_ord',
                                'obs_mor','obs_ord','num_recibo','banco','concepto','fecha_pago')
                            ->where('pago_id','=',$request->buscar)
                            ->get();
        
        $pagares = Pago_contrato::select('restante')
        ->where('id','=',$request->buscar)->get();
            return ['depositos' => $depositos, 'restante' => $pagares[0]->restante];
    }

    public function store(Request $request){
        if(!$request->ajax())return redirect('/');
        try{
            DB::beginTransaction();
            $deposito = new Deposito();
            $deposito->pago_id = $request->pago_id;
            $deposito->cant_depo = $request->cant_depo;
            $deposito->interes_mor = $request->interes_mor;
            $deposito->interes_ord = $request->interes_ord;
            $deposito->obs_mor = $request->obs_mor;
            $deposito->obs_ord = $request->obs_ord;
            $deposito->num_recibo = $request->num_recibo;
            $deposito->banco = $request->banco;
            $deposito->concepto = $request->concepto;
            $deposito->fecha_pago = $request->fecha_pago;

            $pago = $request->cant_depo - $request->interes_mor - $request->interes_ord;

            $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
            $pago_contrato->restante =  $pago_contrato->restante - $pago;
            if($pago_contrato->restante <= 0)
                $pago_contrato->pagado = 2;
            else{
                $pago_contrato->pagado = 1;
            }

            if($request->interes_ord != 0){
                $gasto = new Gasto_admin();
                $gasto->contrato_id = $pago_contrato->contrato_id;
                $gasto->concepto = 'Interes Ordinario';
                $gasto->costo = $request->interes_ord;
                $gasto->fecha = $request->fecha_pago;
                $gasto->observacion = '';
                $gasto->save();
            }

            $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
            $contrato->saldo = $contrato->saldo - $pago;
            $contrato->save(); 

            $pago_contrato->save();

            $deposito->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function update(Request $request){
        if(!$request->ajax())return redirect('/');
        try{
            DB::beginTransaction();
            $deposito = Deposito::findOrFail($request->id);
            $pago_contrato = Pago_contrato::findOrFail($request->pago_id);

            if($deposito->cant_depo != $request->cant_depo){
                $diferencia = $deposito->cant_depo - $request->cant_depo;
            }

            $pagoAnt = $deposito->cant_depo - $deposito->interes_mor - $deposito->interes_ord;
            $pago = $request->cant_depo - $request->interes_mor - $request->interes_ord;
            
            if($request->interes_ord != $deposito->interes_ord){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Ordinario')
                            ->where('costo','=',$deposito->interes_ord)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();

                $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                $gasto->costo = $request->interes_ord;
                $gasto->save();
            }

            $deposito->cant_depo = $request->cant_depo;
            $deposito->interes_mor = $request->interes_mor;
            $interesAnt = $deposito->interes_ord;
            $deposito->interes_ord = $request->interes_ord;
            $deposito->obs_mor = $request->obs_mor;
            $deposito->obs_ord = $request->obs_ord;
            $deposito->num_recibo = $request->num_recibo;
            $deposito->banco = $request->banco;
            $deposito->concepto = $request->concepto;
            $deposito->fecha_pago = $request->fecha_pago;

           
            $pago_contrato->restante = $pago_contrato->restante + $diferencia;
            if($pago_contrato->restante == 0)
                $pago_contrato->pagado = 2;
            else{
                $pago_contrato->pagado = 1;
            }

            $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
            $contrato->saldo = $contrato->saldo + $pagoAnt - $pago;
            $contrato->save(); 

            $pago_contrato->save();

            $deposito->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function delete(Request $request){
        if(!$request->ajax())return redirect('/');

        try{
            DB::beginTransaction();
            $deposito = Deposito::findOrFail($request->id);

            $pagoAnt = $deposito->cant_depo - $deposito->interes_mor - $deposito->interes_ord;

            $pago_contrato = Pago_contrato::findOrFail($request->pago_id);
            $pago = $deposito->cant_depo - $deposito->interes_mor - $deposito->interes_ord;
            $pago_contrato->restante = $pago_contrato->restante + $pago;

            $contrato = Contrato::findOrFail($pago_contrato->contrato_id);
            $contrato->saldo = $contrato->saldo + $pagoAnt;

            if($deposito->interes_ord != 0){
                $b_gasto = Gasto_admin::select('id')
                            ->where('concepto','=','Interes Ordinario')
                            ->where('costo','=',$deposito->interes_ord)
                            ->where('contrato_id','=',$pago_contrato->contrato_id)
                            ->get();

                $gasto = Gasto_admin::findOrFail($b_gasto[0]->id);
                $gasto->delete();
            }

            $contrato->save(); 

            $pago_contrato->save();

            $deposito->delete();
            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
        }     
    
    }

    public function reciboPDF($id){
        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                            ->join('contratos','contratos.id','=','pagos_contratos.contrato_id')
                            ->join('creditos','creditos.id','=','contratos.id')
                            ->join('personal','personal.id','=','creditos.prospecto_id')
                            ->select('depositos.id', 'depositos.pago_id', 'depositos.cant_depo','depositos.interes_mor','depositos.interes_ord',
                                     'depositos.obs_mor','depositos.obs_ord','depositos.num_recibo','depositos.banco','depositos.concepto','depositos.fecha_pago'
                                     ,'creditos.manzana', 'creditos.num_lote','personal.nombre','personal.apellidos','creditos.fraccionamiento')
                                    ->where('depositos.id','=',$id)
                                    ->get();
        $depositos[0]->cantdepLetra = NumerosEnLetras::convertir($depositos[0]->cant_depo,'Pesos',true,'Centavos');
        $fechaDeposito = new Carbon($depositos[0]->fecha_pago);
        $depositos[0]->fecha_pago = $fechaDeposito->formatLocalized('%d días de %B de %Y');
        $pdf = \PDF::loadview('pdf.reciboDePagos',['depositos' => $depositos]);
        return $pdf->stream('recibo_de_pago.pdf');
    }

    public function indexEstadoCuenta(Request $request){
        //if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $criterio = $request->criterio;
        $b_status = $request->b_status;


        if($b_status == ""){
            if($buscar == '' && $criterio!='c.nombre'){
                $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'licencias.avance',
                                        'creditos.manzana','creditos.num_lote',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                    ->leftJoin('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                    
                    ->select(
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id AND 
                                (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id 
                                GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id 
                                GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id AND 
                                (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                GROUP BY pagos_contratos.contrato_id) as pagares"),

                            DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                WHERE gastos_admin.contrato_id = contratos.id 
                                GROUP BY gastos_admin.contrato_id) as gastos")
                            )
                    ->where('i.elegido', '=', 1)
                    ->orderBy('contratos.saldo','desc')
                    ->orderBy('contratos.id','asc')
                    ->count();
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        if($buscar2 == ''){
                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }
                        else{
                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->where('c.apellidos','like','%'. $buscar2 . '%')
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->where('c.apellidos','like','%'. $buscar2 . '%')
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }
                        break;
                    }

                    case 'contratos.id':{
                        $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.id','=', $buscar )
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                        $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.id','=', $buscar )
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();

                                break;
                    }

                    case 'lotes.fraccionamiento_id':{
                        if($buscar != '' && $buscar2 == '' && $b_manzana == '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }
                        elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                               
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }
                        
                        elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                               
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                //->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                //->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                //->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                //->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        break;
                    }
                }
            }

        }else{
            if($buscar == '' && $criterio!='c.nombre'){
                $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();

            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        if($buscar2 == ''){
                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }
                        else{
                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->where('c.apellidos','like','%'. $buscar2 . '%')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->where('c.apellidos','like','%'. $buscar2 . '%')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }
                        break;
                    }

                    case 'contratos.id':{
                        $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.id','=', $buscar )
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                        $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.id','=', $buscar )
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();

                                break;
                    }

                    case 'lotes.fraccionamiento_id':{
                        if($buscar != '' && $buscar2 == '' && $b_manzana == '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }
                        elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }
                        
                        elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                //->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                //->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                //->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->paginate(15);

                            $contador = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select(
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),
            
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),
            
                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")
                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                //->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->count();
                        }

                        break;
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
            ],'contratos' => $contratos,'contador' => $contador];
    }

    public function excelEstadoCuenta(Request $request){

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $criterio = $request->criterio;
        $b_status = $request->b_status;

        if($b_status == ""){
            if($buscar == '' && $criterio!='c.nombre'){
                $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'licencias.avance',
                                        'creditos.manzana','creditos.num_lote',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();

            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        if($buscar2 == ''){
                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        else{
                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->where('c.apellidos','like','%'. $buscar2 . '%')
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        break;
                    }

                    case 'contratos.id':{
                        $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.id','=', $buscar )
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();

                                break;
                    }

                    case 'lotes.fraccionamiento_id':{
                        if($buscar != '' && $buscar2 == '' && $b_manzana == '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        
                        elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                //->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                //->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        break;
                    }
                }
            }

        }else{
            if($buscar == '' && $criterio!='c.nombre'){
                $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();

            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        if($buscar2 == ''){
                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        else{
                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('c.nombre','like','%'. $buscar . '%')
                                ->where('c.apellidos','like','%'. $buscar2 . '%')
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        break;
                    }

                    case 'contratos.id':{
                        $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.id','=', $buscar )
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();

                                break;
                    }

                    case 'lotes.fraccionamiento_id':{
                        if($buscar != '' && $buscar2 == '' && $b_manzana == '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'c.f_nacimiento','c.rfc',
                                        'c.homoclave','c.direccion','c.colonia','c.cp',
                                        'c.telefono','c.email','creditos.num_dep_economicos',
                                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                                        'clientes.nacionalidad','clientes.sexo','c.celular','contratos.direccion_empresa',
                                        'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                                        'contratos.ext_empresa','contratos.colonia_empresa',

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana != '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        
                        elseif($buscar != '' && $buscar2 == '' && $b_manzana != '' && $b_lote == ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                //->where('lotes.etapa_id', '=', $buscar2)
                                ->where('lotes.manzana', '=', $b_manzana)
                                //->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        elseif($buscar != '' && $buscar2 != '' && $b_manzana == '' && $b_lote != ''){

                            $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                ->leftJoin('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                            
                                
                                ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'licencias.avance',
                                        'creditos.precio_venta',
                                        'expedientes.valor_escrituras', 
                                        'expedientes.descuento', 
                                        'contratos.enganche_total',
                                        'contratos.saldo',
                                        'i.monto_credito as credito_solic',
                                        'i.cobrado',
                                        'i.segundo_credito',
                                        'lotes.etapa_id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                                        
                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id 
                                            GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                            WHERE pagos_contratos.contrato_id = contratos.id AND 
                                            (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                                            GROUP BY pagos_contratos.contrato_id) as pagares"),

                                        DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                                            WHERE gastos_admin.contrato_id = contratos.id 
                                            GROUP BY gastos_admin.contrato_id) as gastos")

                                        )
                                ->where('i.elegido', '=', 1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $buscar2)
                                //->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('contratos.status','=',$b_status)
                                ->orderBy('contratos.saldo','desc')
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }

                        break;
                    }
                }
            }
        }

        return Excel::create('Relacion lotes disponibles', function($excel) use ($contratos){
            $excel->sheet('lotes', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto', 'Etapa', 'Manzana', '# Lote',
                    'Avance', 'Precio de Venta', 'Valor a escriturar','Pagares pendientes','Total enganche','Depositos',
                    'Enganche pendiente','Crédito','Pendiente de crédito','Gastos administrativos', 'Descuento', 'Saldo'
                ]);

                $sheet->cells('A1:R1', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $cont=1;            
                $sheet->setColumnFormat(array(
                    'H' => '$#,##0.00',
                    'I' => '$#,##0.00',
                    'J' => '$#,##0.00',
                    'K' => '$#,##0.00',
                    'L' => '$#,##0.00',
                    'M' => '$#,##0.00',
                    'N' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'P' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                ));
                
                foreach($contratos as $index => $contrato) {
                    $cont++;
                    $depositos = $contrato->totalPagares - $contrato->totalRestante;
                    $pendienteCredito = $contrato->credito_solic - $contrato->cobrado;
                    $contrato->avance = $contrato->avance.'%';
                    $sheet->row($index+2, [
                        $contrato->folio, 
                        $contrato->nombre_cliente,
                        $contrato->fraccionamiento, 
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->avance, 
                        $contrato->precio_venta, 
                        $contrato->valor_escrituras,
                        $contrato->pagares,
                        $contrato->enganche_total,
                        $depositos,
                        $contrato->pendiente_enganche,
                        $contrato->credito_solic,
                        $pendienteCredito,
                        $contrato->gastos,
                        $contrato->descuento,
                        $contrato->saldo,
                       
                    ]);	
                }


                $num='A1:R' . $cont;
                $sheet->setBorder($num, 'thin');
                
            });
        }
        
        )->download('xls');
    }

    public function estadoPDF ($id){
        $contratos = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
        ->leftJoin('creditos','contratos.id','=','creditos.id')
        ->leftJoin('lotes','creditos.lote_id','=','lotes.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
        
        ->select('contratos.id as folio','creditos.fraccionamiento', 'creditos.etapa',
                'creditos.manzana','creditos.num_lote','creditos.modelo',
                'creditos.precio_venta',
                'expedientes.valor_escrituras', 
                'expedientes.descuento', 
                'expedientes.fecha_liquidacion',
                'lotes.credito_puente',
                'contratos.enganche_total',
                'contratos.fecha',
                'contratos.saldo',
                'i.monto_credito as credito_solic',
                'i.cobrado',
                'i.segundo_credito',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                'c.rfc','c.homoclave',

                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                    WHERE pagos_contratos.contrato_id = contratos.id AND 
                    (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                    GROUP BY pagos_contratos.contrato_id) as pendiente_enganche"),

                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                    WHERE pagos_contratos.contrato_id = contratos.id 
                    GROUP BY pagos_contratos.contrato_id) as totalRestante"),
                
                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                    WHERE pagos_contratos.contrato_id = contratos.id 
                    GROUP BY pagos_contratos.contrato_id) as totalPagares"),

                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                    WHERE pagos_contratos.contrato_id = contratos.id AND 
                    (pagos_contratos.pagado = 0 or pagos_contratos.pagado = 1)
                    GROUP BY pagos_contratos.contrato_id) as pagares"),

                DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                    WHERE gastos_admin.contrato_id = contratos.id 
                    GROUP BY gastos_admin.contrato_id) as gastos")

                )
        ->where('i.elegido', '=', 1)
        ->where('contratos.id',$id)
        ->get();

        if($contratos[0]->descuento == NULL)
            $contratos[0]->descuento = 0;

        $contratos[0]->totalCargo = $contratos[0]->precio_venta + $contratos[0]->gastos;

        $depositos = Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
                             ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                             ->select('depositos.cant_depo','depositos.num_recibo','depositos.fecha_pago','depositos.banco')
                             ->where('contratos.id','=',$id)
                             ->get();

        for ($i = 0; $i < count($depositos); $i++) {
        $depositos[$i]->cant_depo = number_format((float)$depositos[$i]->cant_depo, 2, '.', ',');
        }

        $totalDepositos =  Deposito::join('pagos_contratos','depositos.pago_id','=','pagos_contratos.id')
        ->join('contratos','pagos_contratos.contrato_id','=','contratos.id')
        ->select(DB::raw("SUM(depositos.cant_depo) as sumDeposito"))
        ->where('contratos.id','=',$id)
        ->get();

        $contratos[0]->sumDeposito = $totalDepositos[0]->sumDeposito;

        $contratos[0]->gastos = number_format((float)$contratos[0]->gastos, 2, '.', ',');
        $contratos[0]->precio_venta = number_format((float)$contratos[0]->precio_venta, 2, '.', ',');
        $contratos[0]->valor_escrituras = number_format((float)$contratos[0]->valor_escrituras, 2, '.', ',');

        $gastos_admin = Gasto_admin::select('concepto','costo','fecha')
        ->where('contrato_id','=',$id)
        ->get();

        for($i = 0; $i < count($gastos_admin); $i++){
            $gastos_admin[$i]->costo = number_format((float)$gastos_admin[$i]->costo, 2, '.', ',');
        }

        $depositos_credito = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                                        ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->select('dep_creditos.cant_depo','dep_creditos.banco','dep_creditos.fecha_deposito','inst_seleccionadas.institucion')
                                        ->where('creditos.id','=',$id)
                                        ->get();

        for($i = 0; $i < count($depositos_credito); $i++){
            $depositos_credito[$i]->cant_depo = number_format((float)$depositos_credito[$i]->cant_depo, 2, '.', ',');
        }

        $total_depositos_credito = Dep_credito::join('inst_seleccionadas','dep_creditos.inst_sel_id','=','inst_seleccionadas.id')
                                        ->join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                                        ->select(DB::raw("SUM(dep_creditos.cant_depo) as sumDepositoCredito"))
                                        ->where('creditos.id','=',$id)
                                        ->get();

        $contratos[0]->sumDepositoCredito = $total_depositos_credito[0]->sumDepositoCredito;
        $contratos[0]->totalAbono = $contratos[0]->sumDeposito + $contratos[0]->sumDepositoCredito + $contratos[0]->descuento;
        $contratos[0]->sumDepositoCredito = number_format((float)$contratos[0]->sumDepositoCredito, 2, '.', ',');
        $contratos[0]->sumDeposito = number_format((float)$contratos[0]->sumDeposito, 2, '.', ',');
        $contratos[0]->totalAbono = number_format((float)$contratos[0]->totalAbono, 2, '.', ',');
        $contratos[0]->totalCargo = number_format((float)$contratos[0]->totalCargo, 2, '.', ',');
        $contratos[0]->saldo = number_format((float)$contratos[0]->saldo, 2, '.', ',');
        $contratos[0]->descuento = number_format((float)$contratos[0]->descuento, 2, '.', ',');
        
        $pdf = \PDF::loadview('pdf.contratos.estadoDeCuenta', ['contratos' => $contratos, 'depositos' => $depositos, 'gastos_admin' => $gastos_admin, 'depositos_credito' => $depositos_credito]);
        return $pdf->stream('EstadoDeCuenta.pdf');
    }
}
