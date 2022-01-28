<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mant_vehiculo;
use App\Mant_retencion;
use App\Obs_mant_vehiculo;
use App\Vehiculo;
use App\Personal;
use App\User;
use Carbon\Carbon;
use Auth;

use App\Http\Controllers\NotificacionesAvisosController;

class VehiculosController extends Controller
{
    public function getMarcas(Request $request){
        $marcas = [
            'Audi',
            'Buick',
            'BMW',
            'Cacillac',
            'Chrysler',
            'Chevrolet',
            'Dodge',
            'Ford',
            'GMC',
            'Honda',
            'Hyundai',
            'Jeep',
            'Kia',
            'Mazda',
            'Nissan',
            'Polaris',
            'Seat',
            'Suzuki',
            'Toyota',
            'Volkswagen'
        ];

        return ['marcas'=>$marcas];
    }

    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $vehiculos = Vehiculo::join('personal','vehiculos.responsable_id','=','personal.id')
                ->select('vehiculos.*','personal.nombre','personal.apellidos');
        if($request->b_vehiculo != '')
            $vehiculos = $vehiculos->where('vehiculos.vehiculo','like','%'.$request->b_vehiculo.'%');
        if($request->b_empresa != '')
            $vehiculos = $vehiculos->where('vehiculos.empresa','=',$request->b_empresa);
        if($request->b_marca != '')
            $vehiculos = $vehiculos->where('vehiculos.marca','=',$request->b_marca);
        if($request->b_comodato != '')
            $vehiculos = $vehiculos->where('vehiculos.comodato','=',$request->b_comodato);

        if(Auth::user()->usuario != 'marce.gaytan' && Auth::user()->usuario != 'karen.viramontes' 
            && Auth::user()->usuario != 'uriel.al' && Auth::user()->usuario != 'shady'
        ) $vehiculos = $vehiculos->where('vehiculos.responsable_id','=',Auth::user()->id);
            

        $vehiculos = $vehiculos->orderBy('vehiculos.empresa','asc')
                ->orderBy('vehiculos.marca','asc')
                ->orderBy('vehiculos.vehiculo','asc')
                ->orderBy('vehiculos.modelo','asc')
                ->paginate(10);

        return $vehiculos;
    }

    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $vehiculo = new Vehiculo();
        $vehiculo->vehiculo =  $request->vehiculo;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->marca =  $request->marca;
        $vehiculo->clave =  $request->clave;
        $vehiculo->placas =  $request->placas;
        $vehiculo->numero_serie =  $request->numero_serie;
        $vehiculo->numero_motor =  $request->numero_motor;
        $vehiculo->responsable_id = $request->responsable_id;
        $vehiculo->comodato = $request->comodato;
        $vehiculo->empresa = $request->empresa;

        $vehiculo->save();
    }

    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $vehiculo = Vehiculo::findOrFail($request->id);
        $vehiculo->vehiculo =  $request->vehiculo;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->marca =  $request->marca;
        $vehiculo->clave =  $request->clave;
        $vehiculo->placas =  $request->placas;
        $vehiculo->numero_serie =  $request->numero_serie;
        $vehiculo->numero_motor =  $request->numero_motor;
        $vehiculo->responsable_id = $request->responsable_id;
        $vehiculo->comodato = $request->comodato;
        $vehiculo->empresa = $request->empresa;

        $vehiculo->save();
    }

    public function getComoDato(Request $request){
        if(!$request->ajax())return redirect('/');
        $vehiculos = Vehiculo::select('id','vehiculo','marca', 'modelo')
                    ->where('comodato','=',1);
        if(Auth::user()->rol_id != 1)
            $vehiculos = $vehiculos->where('responsable_id','=',Auth::user()->id);
        $vehiculos = $vehiculos->get();
        return $vehiculos;
    }

    public function storeSolicitud(Request $request){
        if(!$request->ajax())return redirect('/');
        $solicitante = Vehiculo::join('personal','vehiculos.responsable_id','=','personal.id')
                        ->select('personal.nombre','personal.apellidos')
                        ->where('vehiculos.id','=',$request->vehiculo)
                        ->first();

        try{
            DB::beginTransaction();

            $solicitud = new Mant_vehiculo();
            $solicitud->solicitante = $solicitante->nombre.' '.$solicitante->apellidos;
            $solicitud->vehiculo = $request->vehiculo;
            $solicitud->reparacion = $request->reparacion;
            $solicitud->descripcion = $request->descripcion;
            $solicitud->taller = $request->taller;
            $solicitud->forma_pago = $request->forma_pago;
            $solicitud->importe_total = $request->importe_total;
            $solicitud->monto_comp = $request->monto_comp;
            $solicitud->monto_gcc = $request->monto_gcc;

            $solicitud->save();

            $retenciones = $request->retenciones;

            foreach ($retenciones as $key => $ret) {
                if($ret['importe'] > 0){
                    $retencion = new Mant_retencion();
                    $retencion->mantenimiento_id = $solicitud->id;
                    $retencion->fecha_retencion = $ret['fecha_retencion'];
                    $retencion->importe = $ret['importe'];
                    $retencion->save();
                }
            }

            $msj = 'El colaborador '.$solicitud->solicitante.' ha generado una nueva solicitud para mantenimiento de su vehiculo';
            $aviso = new NotificacionesAvisosController();
            $usuarios = User::select('id')
                                ->where('id','!=',Auth::user()->id)
                                ->where('admin_mant_vehiculos','=',1)
                                ->get();
            foreach ($usuarios as $index => $user) {
                $aviso->store($user->id,$msj);
            }
        DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }         
    }

    public function getSolicitudes(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha1 = $request->b_fecha1;
        $fecha2 = $request->b_fecha2;
        $status = $request->b_status;
        $buscar = $request->buscar;

        $solicitudes = Mant_vehiculo::join('vehiculos','mant_vehiculos.vehiculo','=','vehiculos.id')
                                ->select('mant_vehiculos.*','vehiculos.vehiculo as auto',
                                            'vehiculos.marca','vehiculos.modelo', 'vehiculos.responsable_id');

                if($fecha1 != '' && $fecha2)
                    $solicitudes = $solicitudes->whereBetween('mant_vehiculos.created_at',[$fecha1, $fecha2.' 23:59:59']);
                if($status != '')
                    $solicitudes = $solicitudes->where('mant_vehiculos.status','=',$status);
                if($buscar != '')
                    $solicitudes = $solicitudes->where(DB::raw("CONCAT(vehiculos.marca,' ',vehiculos.vehiculo,' ',vehiculos.modelo)"), 'like', '%'. $buscar . '%');

                if(Auth::user()->admin_mant_vehiculos != 1){
                    $solicitudes = $solicitudes->where('vehiculos.responsable_id','=',Auth::user()->id);
                }
                
            $solicitudes = $solicitudes->orderBy('mant_vehiculos.id','desc')->paginate(10);

        if(sizeOf($solicitudes)){
            foreach ($solicitudes as $key => $solicitud) {
                $solicitud->retenciones = Mant_retencion::where('mantenimiento_id','=',$solicitud->id)->get();
            }
        }

        return $solicitudes;
    }

    public function setRecepJefe(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_jefe = Carbon::now();
        $solicitud->save();

        $this->guardarObs($request->id, 'Autorizado por jefe inmediato.');
    }

    public function setRecepRH(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_rh = Carbon::now();
        $solicitud->save();

        $this->guardarObs($request->id, 'Autorizado por departamento de RH.');
    }

    public function setRecepControl(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_control = Carbon::now();
        $solicitud->save();

        $this->guardarObs($request->id, 'Autorizado por Control.');
    }

    public function setRecepDireccion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_direccion = Carbon::now();
        $solicitud->save();

        $this->guardarObs($request->id, 'Autorizado por Dirección.');
    }

    public function changeStatus(Request $request){
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->status = $request->status;;
        $solicitud->save();

        if($request->status == 2)
            $obs = 'Se ha realizado el pago de la solicitud';
        if($request->status == 0)
            $obs = 'La solicitud ha sido rechazada.';

        $this->guardarObs($request->id, $obs);

        if($request == 2){
            $msj = $obs.' para el vehiculo '.$solicitud->vehiculo.' del colaborador '.$solicitud->solicitante;
            $aviso = new NotificacionesAvisosController();
            $usuarios = User::select('id')
                                ->whereIn('usuario',['marce.gaytan','uriel.al','karen.viramontes','shady'])
                                ->get();
            foreach ($usuarios as $index => $user) {
                $aviso->store($user->id,$msj);
            }

        }
    }

    public function guardarObs($mantenimiento_id, $observacion){
        $obs = new Obs_mant_vehiculo();
        $obs->mantenimiento_id = $mantenimiento_id;
        $obs->observacion = $observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    public function storeObs(Request $request){
        $this->guardarObs($request->id, $request->obs);
    }

    public function getObservaciones(Request $request){
        $obs = Obs_mant_vehiculo::where('mantenimiento_id','=',$request->id)->get();
        return $obs;
    }

    public function retenerPago(Request $request){
        $usuario = Personal::select('nombre','apellidos')->where('id','=',Auth::user()->id)->first();
        $pago = Mant_retencion::findOrFail($request->id);
        $pago->status = 1;
        $pago->fecha_real = Carbon::now();
        $pago->autorizacion = $usuario->nombre.' '.$usuario->apellidos;
        $pago->save();

        $monto = number_format((float)$pago->importe, 2, '.', ',');
        $this->guardarObs($pago->mantenimiento_id, 'Ha sido retenido el importe de $'.$monto);

        $pagos = Mant_retencion::select(DB::raw("SUM(mant_retenciones.importe) as total"))
            ->where('status','=',1)->first();

        $solicitud = Mant_vehiculo::findOrFail($pago->mantenimiento_id);
        if($pagos->total == $solicitud->monto_comp){
            $solicitud->status = 3;
            $this->guardarObs($pago->mantenimiento_id, 'La solicitud ha sido liquidada');
            $solicitud->save();
        }   
    }
}
