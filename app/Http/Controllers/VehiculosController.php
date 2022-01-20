<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mant_vehiculo;
use App\Mant_retencion;
use App\Vehiculo;
use App\Personal;
use Carbon\Carbon;
use Auth;

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
            'Suzuki',
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
                                            'vehiculos.marca','vehiculos.modelo');

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
    }

    public function setRecepRH(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_rh = Carbon::now();
        $solicitud->save();
    }

    public function setRecepControl(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_control = Carbon::now();
        $solicitud->save();
    }

    public function setRecepDireccion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->recep_direccion = Carbon::now();
        $solicitud->save();
    }

    public function changeStatus(Request $request){
        $solicitud = Mant_vehiculo::findOrFail($request->id);
        $solicitud->status = $request->status;;
        $solicitud->save();
    }
}
