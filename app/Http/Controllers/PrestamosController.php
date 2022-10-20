<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Prestamos_personales;
use App\Pagos_prestamos;
use App\Personal;
use App\User;
use App\Obs_prestamos_pers;
use Carbon\Carbon;
use Auth;

class PrestamosController extends Controller
{
    public function dataColaborador(Request $request){
        if (!$request->ajax()) return redirect('/');
        $data=Personal::select('nombre','apellidos')->where('id','=',$request->id)->first();
        return $data; // retorna el nombre del colaborador logueado para el modulo de prestamos
    }

    public function getUsers(Request $request){
        //if (!$request->ajax()) return redirect('/');
        $users=User::join('personal','users.id','=','personal.id')->select('nombre','apellidos','personal.id','usuario','usuario')
                    ->where('condicion','=',1)->where('rol_id','!=',11)->where('rol_id','!=',13)->where('rol_id','!=',10)->orderBy('personal.nombre','asc')->get();
        return $users; // retorna el nombre del colaborador logueado para el modulo de prestamos
    }


    public function getDataPrestamos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id_user=$request->idUser;
        $isRHCurrent=$request->isRHCurrent;
        $isDireccionCurrent=$request->isDireccionCurrent;
        $isGerenteCurrent=$request->isGerenteCurrent;
        $b_colaborador=$request->b_colaborador;
        $b_fecha1=$request->b_fecha1;
        $b_fecha2=$request->b_fecha2;
        $b_status=$request->b_status;


        // query donde se hace la peticion de la informacion de la tabla de prestamos
        $query=Prestamos_personales::join('personal','prestamos_personales.user_id','=','personal.id')
            ->select('prestamos_personales.id','prestamos_personales.created_at','user_id','jefe_id','monto_solicitado',
            'fecha_ini','motivo','rh_band','jefe_band','dir_band','status','saldo','desc_quin',
            'personal.nombre','personal.apellidos','status_rh','fecha_status_rh'
        );

        // En las siguientes condicionales se verifica que datos corresponden para la solicitud del usuario logueado

            if($isGerenteCurrent == 'true'){
                $query=$query->where('jefe_id','=',$id_user)->orWhere('user_id','=',$id_user);
            }elseif($isRHCurrent == 'true' || $isDireccionCurrent == 'true'){
                $query=$query;
            }else{
                $query=$query->where('user_id','=',$id_user);
            }
            if($b_colaborador !='' ){
                $query=$query->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $b_colaborador . '%');
            }
            // condicionales de busqueda.
            if($b_fecha1 !='' && $b_fecha2 !='' ){
                $query = $query->whereBetween('fecha_ini', [$b_fecha1, $b_fecha2]);
            }

        else{
            if ($b_fecha2 !='') {
                $query = $query->where('fecha_ini','=', $b_fecha2);
            }
            if ($b_fecha1 !='') {
                $query = $query->where('fecha_ini','=', $b_fecha1);
            }

        }
        if($b_status !=''){
            $query = $query->where('status','=',$b_status);
        }
        $query = $query->orderBy('personal.nombre','asc')->orderBy('prestamos_personales.id','asc')->paginate(8);

        foreach($query as $solicitud){
            $solicitud->pagos = Pagos_prestamos::where('solic_id','=',$solicitud->id)->orderBy('fecha_quincena','asc')->get();
        }

        return $query;
    }

    public function registrarPrestamo(Request $request)
    {

        if (!$request->ajax()) return redirect('/');
        $user_id=$request->id;
        $monto=$request->monto;
        $motivo=$request->motivo;
        $desc_quin=$request->desc_quin;
        $fecha_solic=$request->fecha_solic;
        $idJefe=$request->idJefe;


        $prestamo=new Prestamos_personales; // registra un nuevo registro en la latbla de prestamos personales
        $prestamo->monto_solicitado=$monto;
        $prestamo->user_id=$user_id;
        $prestamo->motivo=$motivo;
        $prestamo->desc_quin =$desc_quin;
        $prestamo->saldo =$monto;
        $prestamo->user_id = $user_id;
        $prestamo->fecha_ini =$fecha_solic;
        $prestamo->jefe_id =$idJefe;
        $prestamo->save();

        return $prestamo->id; // retorna el id del prestamo creado , para posteriormente crear la tabla de pagos con ese Id
    }

    // crea un registro en la tabla de observaciones
    public function observaciones_prestamos(Request $request){
        if (!$request->ajax()) return redirect('/');
        $this->guarda_observaciones($request->id, $request->obs);
    }

    //esta es una funcion reutilizable que guarda las obervaciones
    public function guarda_observaciones($prestamos_id, $observacion)
    {
        $obs=new Obs_prestamos_pers;
        $obs->prestamo_id = $prestamos_id;
        $obs->observacion = $observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

    public function storeNewPago(Request $request){
        $prestamo=Prestamos_personales::findOrFail($request->id);

        $pago = new Pagos_prestamos();
        $pago->monto_pago = 0;
        $pago->monto_pago_extra = $request->monto_pago_extra;
        $pago->solic_id = $request->id;
        $pago->fecha_pago = $request->fecha_pago;
        $pago->fecha_quincena = $pago->fecha_pago;
        $pago->saldo = $prestamo->saldo - $pago->monto_pago_extra;
        $pago->status = 1;
        $pago->save();

        $prestamo->saldo = $pago->saldo;
        if($prestamo->saldo == 0)
            $prestamo->status= 3;
        $prestamo->save();

        $this->updateTabla($request->id, $prestamo->saldo, $prestamo->desc_quin);
    }

    private function updateTabla($solic_id, $saldo, $desc_quin){
        $pagos = Pagos_prestamos::select('id')
            ->where('status','=',0)->where('solic_id','=',$solic_id)->orderBy('fecha_quincena')->get();
        foreach($pagos as $p){
            $pago = Pagos_prestamos::findOrFail($p->id);
            if($saldo > $desc_quin)
                $pago->monto_pago = $desc_quin;
            else
                $pago->monto_pago = $saldo;
            $pago->monto_pago_extra = 0;
            $pago->saldo = $saldo - $pago->monto_pago;
            $saldo = $pago->saldo;
            $pago->save();
        }
    }


    // busca el Id del pago a editar en sus registros  de Status y Saldo
    public function  capturar_pago(Request $request){
        if (!$request->ajax()) return redirect('/');

        $pagos = $request->pagos;
        $solicitud_id = $request->solic_id;

        $monto_retenido = 0;
        foreach($pagos as $monto){
            $pago = Pagos_prestamos::findOrFail($monto['id']);
            $pago->monto_pago= $monto['monto_pago'];
            $pago->monto_pago_extra = $monto['monto_pago_extra'];
            $pago->saldo = $monto['saldo'];
            $pago->fecha_pago = $monto['fecha_pago'];
            if($pago->fecha_pago != NULL){
                $pago->status = 1;
                $monto_retenido += $pago->monto_pago + $pago->monto_pago_extra;
            }
            $pago->save();

        }

        $prestamo=Prestamos_personales::findOrFail($solicitud_id);
        $prestamo->saldo = $prestamo->monto_solicitado - $monto_retenido;
        if($prestamo->saldo == 0)
            $prestamo->status= 3;
        $prestamo->save();
    }

    public function getObservaciones(Request $request){
        $obs = Obs_prestamos_pers::where('prestamo_id','=',$request->id)->orderBy('obs_prestamos_pers.id','desc')->get();
        return $obs;
    }



    public function aprobar_rh(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id=$request->id;
        $band=$request->band; // aprobado o rechazado
        $fecha=$request->fecha_aprob;



                            $prestamo=Prestamos_personales::findOrFail($id);
                                if($band == 0){
                                    $prestamo->status=0;
                                    $prestamo->status_rh=0;
                                    $this->guarda_observaciones($id, 'Se rechazo la solicitud por RH ');
                                }
                                else{
                                    $prestamo->status_rh=2;
                                    $prestamo->status=2;
                                    $this->guarda_observaciones($id, 'Se aprobo la solicitud por RH ');
                                }
                                $prestamo->fecha_status_rh= $fecha;
                                $prestamo->save();




    }
    public function editarPrestamo(Request $request)
    {

        if (!$request->ajax()) return redirect('/');
        $solicitud_id=$request->solicitud_id;
        $monto=$request->monto;
        $motivo=$request->motivo;
        $idJefe=$request->idJefe;
        $fecha_inicio_retencion=$request->fecha_solic;
        $desc_quin=$request->desc_quin;

    //     $index=key($tablaPagos); // obtiene el index del primer elemento del arreglo
        $prestamo=Prestamos_personales::findOrFail($solicitud_id);
        $prestamo->motivo=$motivo;
        $prestamo->fecha_ini= $fecha_inicio_retencion;
        $prestamo->jefe_id= $idJefe;
        $prestamo->monto_solicitado= $monto;
        $prestamo->saldo= $monto;
        $prestamo->desc_quin= $desc_quin;

        $prestamo->save();
    }
    public function guardaTablaPagos(Request $request){

        $tablaPagos=$request->arrPagos;
        $solicitud_id=$request->id;

        $pagos=Pagos_prestamos::where('solic_id','=', $solicitud_id)->select('id')->get();
        if(sizeof($pagos)){
            foreach($pagos as $pago){
                $pago::findOrFail($pago->id);
                $pago->delete();
            }
        }

        foreach ($tablaPagos as $key => $monto) {
            $pago=new Pagos_prestamos;
            $pago->solic_id=$solicitud_id;
            $pago->monto_pago=$monto['monto_pago'];
            $pago->status = 0;
            $pago->monto_pago_extra = $monto['monto_pago_extra'];
            $pago->saldo = $monto['saldo'];
            $pago->fecha_quincena = $monto['fecha_quincena'];
            $pago->save();
        }
    }


    public function generaTablaPagos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

            $solicitud_id=$request->id;

            $pagos=$request->arrPagos;

            foreach ($pagos as $key => $monto) {
                $pago=new Pagos_prestamos;
                $pago->solic_id=$solicitud_id;
                $pago->monto_pago=$monto['monto_pago'];
                $pago->status =0;
                $pago->monto_pago_extra =$monto['monto_pago_extra'];
                $pago->saldo = $monto['saldo'];
                $pago->fecha_quincena = $monto['fecha_quincena'];
                $pago->save();
            }
    }
    public function getTablaPagos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $solicitud_id=$request->id;

        return Pagos_prestamos::where('solic_id','=',$solicitud_id)->orderBy('fecha_quincena','asc')->get();
    }


    public function firmarPrestamo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id=$request->id;
        $firma_de=$request->firma_de;
        $fecha = new Carbon();
        $fecha = $fecha->formatLocalized('%d de %B de %Y');
        $prestamo=Prestamos_personales::findOrFail($id);
        switch ($firma_de)  {

            case 'jefe':
                $firmas=Prestamos_personales::select('rh_band','jefe_band','dir_band')->where('id','=',$id)->first();
                $prestamo->jefe_band = 1;
                $prestamo->save();
                $this->guarda_observaciones($id, 'Se firmo solicitud por gerente ');
                        if( $firmas->rh_band == 1 && $firmas->dir_band == 1){
                            $status=Prestamos_personales::findOrFail($id);
                            $status->status=2;
                            $status->save();

                        }

                break;
            case 'rh':
                $firmas=Prestamos_personales::select('rh_band','jefe_band','dir_band')->where('id','=',$id)->first();
                $prestamo->rh_band = 1;
                $prestamo->save();
                $this->guarda_observaciones($id, 'Se firmo solicitud por RH ');
                        if( $firmas->jefe_band == 1 && $firmas->dir_band == 1){
                            $status=Prestamos_personales::findOrFail($id);
                            $status->status=2;
                            $status->save();
                        }
                break;
            case 'dir':
                $firmas=Prestamos_personales::select('rh_band','jefe_band','dir_band')->where('id','=',$id)->first();
                $prestamo->dir_band = 1;
                $prestamo->save();
                $this->guarda_observaciones($id, 'Se firmo solicitud por Dirección ');
                        if( $firmas->jefe_band == 1 && $firmas->rh_band == 1){
                            $status=Prestamos_personales::findOrFail($id);
                            $status->status=2;
                            $status->save();
                        }
                break;
            default:
                break;
        }


    }


    public function eliminarSolicitud(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $pago = Prestamos_personales::findOrFail($request->id);
        $pago->delete();
    }
}
